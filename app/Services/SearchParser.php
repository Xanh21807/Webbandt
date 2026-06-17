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

        // Extract price ranges like 'dưới 5 triệu', '5-10 triệu', 'trên 20 triệu'
        if (preg_match('/dưới\s*(\d+)\s*triệu/', $t, $m)) {
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
        } elseif (preg_match('/giá rẻ|giá thấp/', $t)) {
            $filters['price_range'] = 'under_5';
        } elseif (preg_match('/(\d+)\s*-\s*(\d+)\s*triệu/', $t, $m)) {
            $min = (int)$m[1];
            $max = (int)$m[2];
            if ($min >= 5 && $max <= 10) $filters['price_range'] = '5_10';
            elseif ($min >= 10 && $max <= 20) $filters['price_range'] = '10_20';
        } elseif (preg_match('/trên\s*(\d+)\s*triệu/', $t, $m)) {
            $val = (int)$m[1];
            if ($val >= 30) $filters['price_range'] = 'over_30';
            elseif ($val >= 20) $filters['price_range'] = '20_30';
        }

        // Battery intents
        if (preg_match('/pin\s*trâu|pin lớn|pin mạnh|dung lượng pin/', $t)) {
            $filters['battery'] = 'high';
        }

        // RAM like '8GB', '12gb'
        if (preg_match('/(\d+)\s*gb/', $t, $m)) {
            $filters['ram'] = $m[1];
        }

        // Brands (common list)
        $brands = ['iphone','apple','samsung','xiaomi','oppo','vivo','realme','huawei','oneplus'];
        foreach ($brands as $b) {
            if (strpos($t, $b) !== false) {
                // map 'iphone' -> 'Apple'
                if ($b === 'iphone') $filters['brand'] = 'Apple';
                else $filters['brand'] = ucfirst($b);
                break;
            }
        }

        // If none of these, fallback keyword is the whole text
        // but strip stopwords like 'giá', 'rẻ', 'mua'
        $stopwords = ['giá','rẻ','mua','bán','có','và','với','nhanh','nhất','nhỏ','lớn', 'điện thoại', 'điệnthoại', 'đt'];
        $candidate = trim(preg_replace('/\s+/', ' ', $t));
        foreach ($stopwords as $s) {
            $candidate = str_replace($s, '', $candidate);
        }
        $candidate = trim($candidate);
        if ($candidate !== '') {
            $keyword = $candidate;
        }

        return ['keyword' => $keyword, 'filters' => $filters];
    }
}
