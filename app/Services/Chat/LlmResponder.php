<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class LlmResponder
{
    public function reply(string $message, array $history = [], array $products = []): array
    {
        $apiKey = trim((string) config('services.chatbot.api_key', ''));

        if ($apiKey === '') {
            throw new RuntimeException('Chatbot LLM is not configured.');
        }

        $response = Http::baseUrl(rtrim((string) config('services.chatbot.base_url', 'https://api.openai.com/v1'), '/'))
            ->withToken($apiKey)
            ->acceptJson()
            ->timeout((int) config('services.chatbot.timeout', 20))
            ->post('chat/completions', [
                'model' => (string) config('services.chatbot.model', 'gpt-4o-mini'),
                'temperature' => 0.6,
                'response_format' => ['type' => 'json_object'],
                'messages' => $this->buildMessages($message, $history, $products),
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Chatbot LLM request failed: ' . $response->status());
        }

        $content = (string) data_get($response->json(), 'choices.0.message.content', '');
        $payload = $this->decodePayload($content);

        return [
            'reply' => $payload['reply'] ?? 'Mình chưa có câu trả lời phù hợp cho câu hỏi này.',
            'suggestions' => $this->sanitizeSuggestions($payload['suggestions'] ?? []),
            'source' => 'llm',
        ];
    }

    private function buildMessages(string $message, array $history, array $products = []): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => $this->systemPrompt(),
            ],
        ];

        if (! empty($products)) {
            $messages[] = [
                'role' => 'system',
                'content' => 'Dữ liệu sản phẩm thực từ DB để tham chiếu: ' . json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ];
        }

        foreach ($this->normalizeHistory($history) as $entry) {
            $messages[] = $entry;
        }

        $messages[] = [
            'role' => 'user',
            'content' => $message,
        ];

        return $messages;
    }

    private function systemPrompt(): string
    {
        return implode("\n", [
            'Bạn là trợ lý tư vấn cho cửa hàng XanhStore.',
            'Trả lời bằng tiếng Việt tự nhiên, ngắn gọn nhưng hữu ích.',
            'Ưu tiên trả lời linh hoạt theo ngữ cảnh người dùng, không nhắc rằng bạn là AI.',
            'Luôn trả về JSON hợp lệ với 2 khóa: reply và suggestions.',
            'reply là chuỗi ngắn gọn. suggestions là mảng tối đa 3 phần tử.',
            'Mỗi suggestion phải có label, type, value.',
            'type chỉ được là message hoặc link.',
            'Nếu type là link, value phải là một trong các đường dẫn hợp lệ sau:',
            '/products/{id}, /products?category=iphone, /products?category=samsung, /products?category=xiaomi, /products?category=oppo, /products?category=vivo, /products?category=accessories, /products?sale=1, /products?featured=1, /products?new=1, /cart, /orders, /favorites, /checkout.',
            'Nếu không chắc đường dẫn nào đúng, dùng message thay vì link.',
            'Nếu người dùng hỏi điều chưa đủ thông tin, hãy hỏi lại một câu ngắn và gợi ý 1-3 lựa chọn.',
            'Nếu có danh sách sản phẩm thực từ DB, ưu tiên gợi ý đúng sản phẩm với link /products/{id}.',
        ]);
    }

    private function normalizeHistory(array $history): array
    {
        $allowedRoles = ['user', 'assistant'];
        $normalized = [];

        foreach (array_slice($history, -8) as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $role = (string) ($entry['role'] ?? '');
            $content = trim((string) ($entry['content'] ?? ''));

            if (! in_array($role, $allowedRoles, true) || $content === '') {
                continue;
            }

            $normalized[] = [
                'role' => $role,
                'content' => $content,
            ];
        }

        return $normalized;
    }

    private function decodePayload(string $content): array
    {
        $content = trim($content);

        if ($content === '') {
            return [];
        }

        if (Str::startsWith($content, '```')) {
            $content = preg_replace('/^```(?:json)?/i', '', $content) ?? $content;
            $content = preg_replace('/```$/', '', trim($content)) ?? $content;
            $content = trim($content);
        }

        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return [
            'reply' => $content,
            'suggestions' => [],
        ];
    }

    private function sanitizeSuggestions(mixed $suggestions): array
    {
        if (! is_array($suggestions)) {
            return [];
        }

        $allowedLinks = [
            '/products?category=iphone',
            '/products?category=samsung',
            '/products?category=xiaomi',
            '/products?category=oppo',
            '/products?category=vivo',
            '/products?category=accessories',
            '/products?sale=1',
            '/products?featured=1',
            '/products?new=1',
            '/cart',
            '/orders',
            '/favorites',
            '/checkout',
        ];

        $result = [];

        foreach (array_slice($suggestions, 0, 3) as $suggestion) {
            if (! is_array($suggestion)) {
                continue;
            }

            $label = trim((string) ($suggestion['label'] ?? ''));
            $type = trim((string) ($suggestion['type'] ?? 'message'));
            $value = trim((string) ($suggestion['value'] ?? ''));

            if ($label === '' || $value === '') {
                continue;
            }

            if ($type !== 'link') {
                $type = 'message';
            }

            if ($type === 'link' && ! preg_match('#^/products/\d+$#', $value) && ! in_array($value, $allowedLinks, true)) {
                $type = 'message';
            }

            $result[] = [
                'label' => $label,
                'type' => $type,
                'value' => $value,
            ];
        }

        return $result;
    }
}