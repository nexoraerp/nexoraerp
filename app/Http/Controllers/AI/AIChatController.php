<?php

namespace App\Http\Controllers\AI;

use App\AI\AIManager;
use App\Http\Controllers\Controller;
use App\Services\AI\NexoraBriefingService;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    public function __construct(
        protected AIManager $ai,
        protected NexoraBriefingService $briefing
    ) {}

    public function chat(Request $request)
    {
        try {

            $request->validate([
                'message' => ['required', 'string'],
            ]);

            $response = $this->ai->chat([
                [
                    'role' => 'system',
                    'content' => implode("\n", [
                        'Sen Nexora AI Assistant isimli profesyonel ERP asistanısın.',
                        'Türkçe, doğal, samimi ama kurumsal konuş.',
                        'Kullanıcının niyetini doğal dilden anla; satış, tahsilat, stok, cari, rapor ve risk konularında kısa, net ve aksiyon odaklı cevap ver.',
                        'Belirsiz isteklerde önce en mantıklı varsayımı söyle, gerekiyorsa tek kısa soru sor.',
                        'Risk varsa renkli öncelik mantığıyla belirt: kırmızı kritik, turuncu dikkat, yeşil olumlu.',
                        'KDV, kar, tahsilat ve stok yorumlarında rakamları iş sahibinin anlayacağı dille özetle.',
                        'Güncel ERP risk özeti:',
                        $this->briefing->contextText($request->user()?->name),
                    ]),
                ],
                [
                    'role' => 'user',
                    'content' => $request->message,
                ],
            ]);

            return response()->json($response);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);

        }
    }
}
