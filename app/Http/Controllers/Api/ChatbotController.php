<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Chat\LlmResponder;
use App\Services\Chat\ProductFinder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ChatbotController extends Controller
{
    public function reply(Request $request, LlmResponder $llmResponder, ProductFinder $productFinder)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = trim($data['message']);
        $normalized = Str::of($message)->ascii()->lower()->toString();
        $sessionId = $this->resolveSessionId($request);
        $history = $this->loadHistory($sessionId);
        $products = $productFinder->find($message, 3);

        try {
            $response = $llmResponder->reply($message, $history, $products);

            if (empty($response['suggestions']) && ! empty($products)) {
                $response['suggestions'] = $this->productSuggestions($products);
            }

            $this->storeHistory($sessionId, $message, (string) ($response['reply'] ?? ''));

            return response()->json([
                'success' => true,
                'data' => $response,
            ]);
        } catch (Throwable $throwable) {
            [$reply, $suggestions] = $this->buildResponse($normalized);
            if (! empty($products)) {
                $reply = $this->appendProductReply($reply, $products);
                $suggestions = array_merge($this->productSuggestions($products), $suggestions);
            }

            $this->storeHistory($sessionId, $message, $reply);

            return response()->json([
                'success' => true,
                'data' => [
                    'reply' => $reply,
                    'suggestions' => $suggestions,
                    'source' => 'local-fallback',
                ],
            ]);
        }
    }

    private function resolveSessionId(Request $request): string
    {
        $sessionId = trim((string) $request->header('X-Chatbot-Session-Id', ''));

        if ($sessionId !== '') {
            return $sessionId;
        }

        return (string) Str::uuid();
    }

    private function loadHistory(string $sessionId): array
    {
        $history = Cache::get($this->historyKey($sessionId), []);

        return is_array($history) ? $history : [];
    }

    private function storeHistory(string $sessionId, string $userMessage, string $assistantReply): void
    {
        $history = $this->loadHistory($sessionId);
        $history[] = ['role' => 'user', 'content' => $userMessage];
        $history[] = ['role' => 'assistant', 'content' => $assistantReply];

        $historyLimit = (int) config('services.chatbot.history_limit', 8);
        $history = array_slice($history, -($historyLimit * 2));

        Cache::put($this->historyKey($sessionId), $history, now()->addHours(12));
    }

    private function historyKey(string $sessionId): string
    {
        return 'chatbot:history:' . $sessionId;
    }

    private function productSuggestions(array $products): array
    {
        return array_map(function (array $product): array {
            return [
                'label' => $product['name'],
                'type' => 'link',
                'value' => $product['url'],
            ];
        }, array_slice($products, 0, 3));
    }

    private function appendProductReply(string $reply, array $products): string
    {
        $names = array_values(array_filter(array_map(static function (array $product): string {
            return (string) ($product['name'] ?? '');
        }, $products)));

        if ($names === []) {
            return $reply;
        }

        return $reply . ' Mình tìm thấy một số sản phẩm phù hợp: ' . implode(', ', $names) . '.';
    }

    private function buildResponse(string $message): array
    {
        if ($this->containsAny($message, ['xin chao', 'chao', 'hello', 'hi', 'hey'])) {
            return [
                'Chào bạn, mình là trợ lý XanhStore. Mình có thể tư vấn sản phẩm, khuyến mãi, bảo hành, đổi trả và thanh toán ngay bây giờ.',
                $this->commonSuggestions(),
            ];
        }

        if ($this->containsAny($message, ['giao hang', 'van chuyen', 'ship', 'phi ship', 'freeship'])) {
            return [
                'XanhStore miễn phí vận chuyển cho đơn hàng từ 500.000đ. Với đơn nhỏ hơn, phí ship sẽ được hiển thị khi thanh toán.',
                [
                    $this->messageSuggestion('Chính sách đổi trả', 'Đổi trả như thế nào?'),
                    $this->messageSuggestion('Phương thức thanh toán', 'Có những cách thanh toán nào?'),
                ],
            ];
        }

        if ($this->containsAny($message, ['bao hanh', 'bao tri', 'warranty'])) {
            return [
                'Sản phẩm tại XanhStore được bảo hành chính hãng từ 12 đến 24 tháng tùy model. Nếu bạn cần mình kiểm tra theo dòng máy, hãy nhắn tên sản phẩm nhé.',
                [
                    $this->messageSuggestion('Xem iPhone', 'Tôi muốn xem iPhone'),
                    $this->messageSuggestion('Xem Samsung', 'Tôi muốn xem Samsung'),
                ],
            ];
        }

        if ($this->containsAny($message, ['doi tra', 'tra hang', 'hoan tien', 'return'])) {
            return [
                'XanhStore hỗ trợ đổi trả trong 30 ngày nếu sản phẩm đáp ứng điều kiện bảo hành và còn đầy đủ phụ kiện. Bạn muốn mình tóm tắt điều kiện đổi trả chi tiết không?',
                [
                    $this->messageSuggestion('Xem chính sách', 'Cho mình xem chính sách đổi trả'),
                    $this->messageSuggestion('Liên hệ hỗ trợ', 'Tôi cần liên hệ hỗ trợ'),
                ],
            ];
        }

        if ($this->containsAny($message, ['thanh toan', 'payment', 'cod', 'momo', 'vnpay', 'chuyen khoan'])) {
            return [
                'Bạn có thể thanh toán khi nhận hàng (COD), chuyển khoản ngân hàng, MoMo hoặc VNPay. Nếu muốn, mình có thể hướng dẫn từng bước đặt hàng.',
                [
                    $this->messageSuggestion('Mua hàng', 'Hướng dẫn mua hàng'),
                    $this->messageSuggestion('Theo dõi đơn', 'Làm sao xem đơn hàng?'),
                ],
            ];
        }

        if ($this->containsAny($message, ['don hang', 'order', 'xem don', 'trang thai'])) {
            return [
                'Bạn có thể xem đơn hàng trong mục Đơn hàng của tôi sau khi đăng nhập. Nếu cần hỗ trợ gấp, hãy gọi hotline 1900 1234.',
                [
                    $this->linkSuggestion('Đơn hàng của tôi', '/orders'),
                    $this->messageSuggestion('Liên hệ hotline', 'Tôi cần hỗ trợ qua hotline'),
                ],
            ];
        }

        if ($this->containsAny($message, ['phu kien', 'accessories', 'tai nghe', 'op lung', 'sac', 'cáp sạc', 'cap sac', 'miếng dán', 'mieng dan', 'giá đỡ', 'gia do'])) {
            return [
                'XanhStore có nhiều phụ kiện như cáp sạc, củ sạc, tai nghe, ốp lưng và miếng dán. Bạn muốn xem nhóm nào trước?',
                [
                    $this->linkSuggestion('Phụ kiện', '/products?category=accessories'),
                    $this->messageSuggestion('Cáp sạc', 'Tôi muốn xem cáp sạc'),
                ],
            ];
        }

        if ($this->containsAny($message, ['iphone', 'apple'])) {
            return [
                'Mình đã mở nhóm iPhone cho bạn. Bạn có thể xem các mẫu đang bán tại đây.',
                [
                    $this->linkSuggestion('Xem iPhone', '/products?category=iphone'),
                    $this->messageSuggestion('So sánh iPhone', 'Tư vấn chọn iPhone'),
                ],
            ];
        }

        if ($this->containsAny($message, ['samsung'])) {
            return [
                'Bạn có thể xem các dòng Samsung đang có sẵn và mình sẽ hỗ trợ chọn theo nhu cầu.',
                [
                    $this->linkSuggestion('Xem Samsung', '/products?category=samsung'),
                    $this->messageSuggestion('Samsung tầm trung', 'Gợi ý Samsung tầm trung'),
                ],
            ];
        }

        if ($this->containsAny($message, ['xiaomi'])) {
            return [
                'Dưới đây là nhóm Xiaomi. Nếu bạn muốn máy pin tốt, cấu hình mạnh, mình sẽ lọc theo nhu cầu tiếp.',
                [
                    $this->linkSuggestion('Xem Xiaomi', '/products?category=xiaomi'),
                    $this->messageSuggestion('Máy pin tốt', 'Gợi ý máy pin tốt'),
                ],
            ];
        }

        if ($this->containsAny($message, ['oppo'])) {
            return [
                'Mình đã sẵn sàng tư vấn các mẫu OPPO. Bạn có thể xem ngay danh sách sản phẩm.',
                [
                    $this->linkSuggestion('Xem OPPO', '/products?category=oppo'),
                    $this->messageSuggestion('Máy chụp đẹp', 'Gợi ý máy chụp ảnh đẹp'),
                ],
            ];
        }

        if ($this->containsAny($message, ['vivo'])) {
            return [
                'Bạn có thể xem các mẫu Vivo và mình sẽ hỗ trợ theo tầm giá hoặc nhu cầu chụp ảnh, pin, hiệu năng.',
                [
                    $this->linkSuggestion('Xem Vivo', '/products?category=vivo'),
                    $this->messageSuggestion('Theo tầm giá', 'Tư vấn theo tầm giá'),
                ],
            ];
        }

        return [
            'Mình có thể hỗ trợ tư vấn sản phẩm, khuyến mãi, đổi trả, bảo hành và thanh toán. Bạn có thể chọn một chủ đề bên dưới hoặc nhắn tên dòng máy bạn đang quan tâm.',
            $this->commonSuggestions(),
        ];
    }

    private function containsAny(string $message, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (Str::contains($message, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function commonSuggestions(): array
    {
        return [
            $this->messageSuggestion('Chính sách bảo hành', 'Bảo hành như thế nào?'),
            $this->messageSuggestion('Chính sách đổi trả', 'Đổi trả như thế nào?'),
            $this->messageSuggestion('Thanh toán', 'Có những cách thanh toán nào?'),
        ];
    }

    private function messageSuggestion(string $label, string $message): array
    {
        return [
            'label' => $label,
            'type' => 'message',
            'value' => $message,
        ];
    }

    private function linkSuggestion(string $label, string $url): array
    {
        return [
            'label' => $label,
            'type' => 'link',
            'value' => $url,
        ];
    }
}