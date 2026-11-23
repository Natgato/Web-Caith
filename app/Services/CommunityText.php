<?php

namespace App\Services;

use App\Models\EditorialRecord;
use Illuminate\Support\Str;

class CommunityText
{
    public static function reviewVersion(EditorialRecord $entry): string
    {
        return hash('sha256', json_encode([
            $entry->id, $entry->title, $entry->body, $entry->public_name, $entry->media_id,
            $entry->getRawOriginal('submitted_at'),
        ], JSON_THROW_ON_ERROR));
    }

    public static function render(?string $text): string
    {
        $lines = preg_split('/\R/u', trim($text ?? ''));
        $html = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $tag = match (true) {
                str_starts_with($line, '## ') => 'h2',
                str_starts_with($line, '> ') => 'blockquote',
                default => 'p',
            };
            $content = substr($line, match ($tag) {
                'h2' => 3,
                'blockquote' => 2,
                default => 0,
            });
            $content = preg_replace('/\*\*([^*]+)\*\*/u', '<strong>$1</strong>', e($content));
            $content = preg_replace('/\*([^*]+)\*/u', '<em>$1</em>', $content);
            $html[] = "<{$tag}>{$content}</{$tag}>";
        }

        return implode("\n", $html);
    }

    public static function excerpt(?string $text, int $length): string
    {
        return Str::limit(Str::squish(html_entity_decode(strip_tags(self::render($text)))), $length);
    }
}
