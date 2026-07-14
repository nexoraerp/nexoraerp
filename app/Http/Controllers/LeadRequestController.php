<?php

namespace App\Http\Controllers;

use App\Models\LeadRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class LeadRequestController extends Controller
{
    public function adminIndex(Request $request)
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $leads = LeadRequest::query()
            ->latest()
            ->get()
            ->map(fn (LeadRequest $lead) => $this->present($lead));

        return Inertia::render('LeadRequests/Index', [
            'leads' => $leads,
            'summary' => [
                'total' => $leads->count(),
                'new' => $leads->where('status', 'new')->count(),
                'contacted' => $leads->where('status', 'contacted')->count(),
                'closed' => $leads->where('status', 'closed')->count(),
            ],
            'statuses' => $this->statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        LeadRequest::create([
            ...$validated,
            'status' => 'new',
        ]);

        return back()->with('success', 'Bilgi talebiniz alındı. Nexora ekibi sizinle iletişime geçecek.');
    }

    public function update(Request $request, LeadRequest $leadRequest)
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys($this->statuses()))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $leadRequest->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
            'contacted_at' => $validated['status'] === 'contacted'
                ? ($leadRequest->contacted_at ?? now())
                : $leadRequest->contacted_at,
        ]);

        return back()->with('success', 'Bilgi talebi güncellendi.');
    }

    private function present(LeadRequest $lead): array
    {
        return [
            'id' => $lead->id,
            'name' => $lead->name,
            'company_name' => $lead->company_name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'message' => $lead->message,
            'status' => $lead->status,
            'status_label' => $this->statuses()[$lead->status] ?? $lead->status,
            'admin_note' => $lead->admin_note,
            'contacted_at' => $lead->contacted_at?->format('d.m.Y H:i'),
            'created_at' => $lead->created_at?->format('d.m.Y H:i'),
            'created_human' => $lead->created_at?->diffForHumans(),
        ];
    }

    private function statuses(): array
    {
        return [
            'new' => 'Yeni',
            'contacted' => 'Arandı',
            'qualified' => 'Uygun Müşteri',
            'closed' => 'Kapandı',
        ];
    }
}
