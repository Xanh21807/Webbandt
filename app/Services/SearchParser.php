<?php

namespace App\Services;

class SearchParser
{
    /**
     * Parse a free-text search into a keyword and structured filters.
     * Returns ['keyword' => string|null, 'filters' => array]
     */
    public static function parse(string $text): array
    {
        $t = mb_strtolower($text);
        $filters = [];
        $keyword = null;
        $remainingText = $t;

        // Extract price ranges like 'dưới 5 triệu', '5-10 triệu', 'trên 20 triệu'
        if (preg_match('/dưới\s*(\d+)\s*triệu/', $remainingText, $m)) {
            $val = (int) $m[1];
            if ($val <= 5) {
                $filters['price_range'] = 'under_5';
            } elseif ($val <= 10) {
                $filters['price_range'] = '5_10';
            } elseif ($val <= 20) {
                $filters['price_range'] = '10_20';
            } elseif ($val <= 30) {
                $filters['price_range'] = '20_30';
            }
            $remainingText = str_replace($m[0], '', $remainingText);
        } elseif (preg_match('/giá rẻ|giá thấp/', $remainingText, $m)) {
            $filters['price_range'] = 'under_5';
            $remainingText = str_replace($m[0], '', $remainingText);
        } elseif (preg_match('/(\d+)\s*-\s*(\d+)\s*triệu/', $remainingText, $m)) {
            $min = (int)$m[1];
            $max = (int)$m[2];
            if ($min >= 5 && $max <= 10) $filters['price_range'] = '5_10';
            elseif ($min >= 10 && $max <= 20) $filters['price_range'] = '10_20';
            $remainingText = str_replace($m[0], '', $remainingText);
        } elseif (preg_match('/trên\s*(\d+)\s*triệu/', $remainingText, $m)) {
            $val = (int)$m[1];
            if ($val >= 30) $filters['price_range'] = 'over_30';
            elseif ($val >= 20) $filters['price_range'] = '20_30';
            $remainingText = str_replace($m[0], '', $remainingText);
        }

        // Battery intents
        if (preg_match('/pin\s*(trâu|lớn|mạnh|khủng)|dung lượng pin/', $remainingText, $m)) {
            $filters['battery'] = 'high';
            $remainingText = str_replace($m[0], '', $remainingText);
        }

        // RAM like '8GB', '12gb'
        if (preg_match('/(\d+)\s*gb/', $remainingText, $m)) {
            $filters['ram'] = $m[1];
            $remainingText = str_replace($m[0], '', $remainingText);
        }

        // Brands (common list)
        $brands = ['iphone','apple','samsung','xiaomi','oppo','vivo','realme','huawei','oneplus'];
        foreach ($brands as $b) {
            if (strpos($remainingText, $b) !== false) {
                // map 'iphone' -> 'Apple'
                if ($b === 'iphone') $filters['brand'] = 'Apple';
                else $filters['brand'] = ucfirst($b);
                $remainingText = str_replace($b, '', $remainingText);
                break;
            }
        }

        // If none of these, fallback keyword is the remaining text
        // but strip stopwords like 'giá', 'rẻ', 'mua'
        $stopwords = ['giá','rẻ','mua','bán','có','và','với','nhanh','nhất','nhỏ','lớn', 'điện thoại', 'điệnthoại', 'đt', 'ram', 'máy', 'điện', 'thoại', 'khủng', 'tốt'];
        $candidate = trim(preg_replace('/\s+/', ' ', $remainingText));
        foreach ($stopwords as $s) {
            $candidate = preg_replace('/\b' . preg_quote($s, '/') . '\b/u', '', $candidate) ?? str_replace($s, '', $candidate);
        }
        
        // Final clean of double spaces
        $candidate = trim(preg_replace('/\s+/', ' ', $candidate));
        if ($candidate !== '') {
            $keyword = $candidate;
        }

        return ['keyword' => $keyword, 'filters' => $filters];
    }
}
