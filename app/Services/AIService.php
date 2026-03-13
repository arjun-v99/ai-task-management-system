<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $model = 'claude-sonnet-4-20250514';
    /**
     * PROMPT USED:
     * You are a project management assistant. Given the following task details,
     * provide a concise one-sentence summary and suggest a priority level (low, medium, high).
     * Respond ONLY in JSON: { "ai_summary": "...", "ai_priority": "high|medium|low" }
     * Task Title: {title}
     * Task Description: {description}
     */
    public function generateSummary(Task $task): array
    {
        if (!config('services.anthropic.key')) {
            return $this->mockResponse($task);
        }

        try {
            $response = Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])
                ->withoutVerifying()
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => $this->model,
                    'max_tokens' => 256,
                    'messages'   => [
                        [
                            'role'    => 'user',
                            'content' => $this->buildPrompt($task),
                        ]
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('AIService: Claude API call failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return $this->mockResponse($task);
            }

            // Claude returns text in content[0].text
            $text   = $response->json('content.0.text');
            $parsed = json_decode($text, true);

            if (!$parsed || !isset($parsed['ai_summary'], $parsed['ai_priority'])) {
                Log::warning('AIService: Unexpected response format', ['text' => $text]);
                return $this->mockResponse($task);
            }

            return [
                'ai_summary'  => $parsed['ai_summary'],
                'ai_priority' => $parsed['ai_priority'],
            ];
        } catch (\Throwable $e) {
            Log::error('AIService: Exception', ['message' => $e->getMessage()]);
            return $this->mockResponse($task);
        }
    }

    private function buildPrompt(Task $task): string
    {
        return <<<PROMPT
You are a project management assistant. Given the following task details,
provide a concise one-sentence summary and suggest a priority level (low, medium, high).
Respond ONLY in raw JSON with no extra text or markdown:
{ "ai_summary": "...", "ai_priority": "high|medium|low" }

Task Title: {$task->title}
Task Description: {$task->description}
PROMPT;
    }

    private function mockResponse(Task $task): array
    {
        return [
            'ai_summary'  => "This task involves: {$task->title}. Ensure it is completed on time.",
            'ai_priority' => 'medium',
        ];
    }
}
