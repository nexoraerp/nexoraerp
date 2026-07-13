<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = SupportTicket::query()
            ->with('user')
            ->forTenant($request->user())
            ->latest()
            ->get()
            ->map(fn (SupportTicket $ticket) => $this->present($ticket));

        return Inertia::render('SupportTickets/Index', [
            'tickets' => $tickets,
            'summary' => [
                'total' => $tickets->count(),
                'open' => $tickets->where('status', 'open')->count(),
                'in_progress' => $tickets->where('status', 'in_progress')->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
            ],
            'options' => $this->options(),
            'isAdminView' => false,
        ]);
    }

    public function adminIndex(Request $request)
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $tickets = SupportTicket::query()
            ->with(['user', 'tenantOwner'])
            ->latest()
            ->get()
            ->map(fn (SupportTicket $ticket) => $this->present($ticket));

        return Inertia::render('SupportTickets/Index', [
            'tickets' => $tickets,
            'summary' => [
                'total' => $tickets->count(),
                'open' => $tickets->where('status', 'open')->count(),
                'in_progress' => $tickets->where('status', 'in_progress')->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
            ],
            'options' => $this->options(),
            'isAdminView' => true,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(array_keys($this->options()['categories']))],
            'priority' => ['required', 'string', Rule::in(array_keys($this->options()['priorities']))],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            SupportTicket::create([
                ...$validated,
                'user_id' => $request->user()->id,
                'tenant_user_id' => $request->user()->tenantOwnerId(),
                'ticket_no' => $this->generateTicketNo(),
                'status' => 'open',
            ]);
        });

        return back()->with('success', 'Destek talebiniz oluşturuldu. Ekibimiz admin panelinden takip edecek.');
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys($this->options()['statuses']))],
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'resolved_at' => $validated['status'] === 'resolved'
                ? ($ticket->resolved_at ?? now())
                : null,
        ]);

        return back()->with('success', 'Destek talebi güncellendi.');
    }

    private function generateTicketNo(): string
    {
        $nextId = ((int) SupportTicket::max('id')) + 1;

        return 'DST-' . now()->format('Ymd') . '-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    private function present(SupportTicket $ticket): array
    {
        return [
            'id' => $ticket->id,
            'ticket_no' => $ticket->ticket_no,
            'subject' => $ticket->subject,
            'category' => $ticket->category,
            'category_label' => $this->options()['categories'][$ticket->category] ?? $ticket->category,
            'priority' => $ticket->priority,
            'priority_label' => $this->options()['priorities'][$ticket->priority] ?? $ticket->priority,
            'status' => $ticket->status,
            'status_label' => $this->options()['statuses'][$ticket->status] ?? $ticket->status,
            'message' => $ticket->message,
            'admin_note' => $ticket->admin_note,
            'resolved_at' => $ticket->resolved_at?->format('d.m.Y H:i'),
            'created_at' => $ticket->created_at?->format('d.m.Y H:i'),
            'created_human' => $ticket->created_at?->diffForHumans(),
            'user' => [
                'name' => $ticket->user?->name,
                'email' => $ticket->user?->email,
                'phone' => $ticket->user?->phone,
                'company_name' => $ticket->user?->company_name,
            ],
            'tenant' => [
                'name' => $ticket->tenantOwner?->name,
                'email' => $ticket->tenantOwner?->email,
                'phone' => $ticket->tenantOwner?->phone,
                'company_name' => $ticket->tenantOwner?->company_name,
            ],
        ];
    }

    private function options(): array
    {
        return [
            'categories' => [
                'support' => 'Destek Talebi',
                'suggestion' => 'Çözüm Önerisi',
                'bug' => 'Hata Bildirimi',
                'billing' => 'Lisans / Ödeme',
            ],
            'priorities' => [
                'low' => 'Düşük',
                'normal' => 'Normal',
                'high' => 'Yüksek',
                'critical' => 'Kritik',
            ],
            'statuses' => [
                'open' => 'Açık',
                'in_progress' => 'İnceleniyor',
                'resolved' => 'Çözüldü',
                'closed' => 'Kapandı',
            ],
        ];
    }
}
