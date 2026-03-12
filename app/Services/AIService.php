<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Generate an AI summary and priority suggestion for a task.
     * Falls back to mock data if API call fails or key is not set.
     *
     * PROMPT USED:
     * "You are a project management assistant. Given the following task details,
     *  provide a concise one-sentence summary and suggest a priority level (low, medium, high).
     *  Respond only in JSON: { "ai_summary": "...", "ai_priority": "high|medium|low" }
     *  Task Title: {title}
     *  Task Description: {description}"
     */
    public function generateSummary(Task $task): array
    {
        if (!config('services.openai.key')) {
            return $this->mockResponse($task);
        }

        try {
            $prompt = $this->buildPrompt($task);

            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens'      => 150,
                    'response_format' => ['type' => 'json_object'],
                ]);

            if ($response->failed()) {
                Log::warning('AIService: API call failed', ['status' => $response->status()]);
                return $this->mockResponse($task);
            }

            $content = $response->json('choices.0.message.content');
            $parsed  = json_decode($content, true);

            return [
                'ai_summary'  => $parsed['ai_summary']  ?? 'No summary generated.',
                'ai_priority' => $parsed['ai_priority'] ?? 'medium',
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
Respond ONLY in JSON format: { "ai_summary": "...", "ai_priority": "high|medium|low" }

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
