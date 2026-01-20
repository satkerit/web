<?php

namespace App\Helpers;

/**
 * Simple HTML Sanitizer for WYSIWYG content
 * Allows safe HTML tags while removing potentially dangerous content
 */
class HtmlSanitizer
{
    /**
     * Allowed HTML tags
     */
    protected static array $allowedTags = [
        'p',
        'br',
        'strong',
        'b',
        'em',
        'i',
        'u',
        's',
        'strike',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'ul',
        'ol',
        'li',
        'a',
        'img',
        'blockquote',
        'pre',
        'code',
        'table',
        'thead',
        'tbody',
        'tr',
        'th',
        'td',
        'div',
        'span',
        'hr',
    ];

    /**
     * Allowed attributes per tag
     */
    protected static array $allowedAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'class'],
        'div' => ['class', 'style'],
        'span' => ['class', 'style'],
        'p' => ['class', 'style'],
        'table' => ['class', 'border', 'cellpadding', 'cellspacing'],
        'th' => ['colspan', 'rowspan', 'class'],
        'td' => ['colspan', 'rowspan', 'class'],
        '*' => ['class'], // Allow class on all elements
    ];

    /**
     * Dangerous patterns to remove
     */
    protected static array $dangerousPatterns = [
        '/<script\b[^>]*>(.*?)<\/script>/is',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/on\w+\s*=/i', // onclick, onload, onerror, etc.
        '/data\s*:/i',
        '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
        '/<object\b[^>]*>(.*?)<\/object>/is',
        '/<embed\b[^>]*>/is',
        '/<form\b[^>]*>(.*?)<\/form>/is',
        '/<input\b[^>]*>/is',
        '/<button\b[^>]*>(.*?)<\/button>/is',
        '/<textarea\b[^>]*>(.*?)<\/textarea>/is',
        '/<select\b[^>]*>(.*?)<\/select>/is',
        '/expression\s*\(/i', // CSS expression
        '/url\s*\(\s*["\']?\s*javascript/i',
    ];

    /**
     * Sanitize HTML content
     */
    public static function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Remove dangerous patterns first
        foreach (self::$dangerousPatterns as $pattern) {
            $html = preg_replace($pattern, '', $html);
        }

        // Strip tags except allowed ones
        $allowedTagsString = '<' . implode('><', self::$allowedTags) . '>';
        $html = strip_tags($html, $allowedTagsString);

        // Clean attributes
        $html = self::cleanAttributes($html);

        // Ensure links are safe
        $html = self::sanitizeLinks($html);

        return $html;
    }

    /**
     * Alias for clean method
     */
    public static function sanitize(?string $html): string
    {
        return self::clean($html);
    }

    /**
     * Clean attributes from HTML tags
     */
    protected static function cleanAttributes(string $html): string
    {
        // Match all HTML tags with attributes
        return preg_replace_callback(
            '/<(\w+)([^>]*)>/i',
            function ($matches) {
                $tag = strtolower($matches[1]);
                $attributes = $matches[2];

                if (empty(trim($attributes))) {
                    return "<{$tag}>";
                }

                // Get allowed attributes for this tag
                $allowed = array_merge(
                    self::$allowedAttributes['*'] ?? [],
                    self::$allowedAttributes[$tag] ?? []
                );

                if (empty($allowed)) {
                    return "<{$tag}>";
                }

                // Parse and filter attributes
                $cleanAttributes = [];
                preg_match_all('/(\w+)\s*=\s*["\']([^"\']*)["\']|(\w+)\s*=\s*(\S+)/i', $attributes, $attrMatches, PREG_SET_ORDER);

                foreach ($attrMatches as $attr) {
                    $name = strtolower($attr[1] ?: $attr[3]);
                    $value = $attr[2] ?: $attr[4];

                    if (in_array($name, $allowed)) {
                        // Additional sanitization for specific attributes
                        $value = self::sanitizeAttributeValue($name, $value);
                        if ($value !== null) {
                            $cleanAttributes[] = $name . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
                        }
                    }
                }

                $attrString = empty($cleanAttributes) ? '' : ' ' . implode(' ', $cleanAttributes);
                return "<{$tag}{$attrString}>";
            },
            $html
        );
    }

    /**
     * Sanitize attribute values
     */
    protected static function sanitizeAttributeValue(string $name, string $value): ?string
    {
        // Check for javascript: in href/src
        if (in_array($name, ['href', 'src'])) {
            if (preg_match('/^\s*(javascript|vbscript|data):/i', $value)) {
                return null;
            }
        }

        // Clean style attribute
        if ($name === 'style') {
            // Remove dangerous CSS
            $value = preg_replace('/expression\s*\(/i', '', $value);
            $value = preg_replace('/javascript\s*:/i', '', $value);
            $value = preg_replace('/url\s*\([^)]*\)/i', '', $value);
        }

        return $value;
    }

    /**
     * Sanitize links to add rel="noopener noreferrer" for external links
     */
    protected static function sanitizeLinks(string $html): string
    {
        return preg_replace_callback(
            '/<a\s+([^>]*)>/i',
            function ($matches) {
                $attributes = $matches[1];

                // Check if it's an external link
                if (preg_match('/href\s*=\s*["\']https?:\/\//i', $attributes)) {
                    // Add target="_blank" if not present
                    if (!preg_match('/target\s*=/i', $attributes)) {
                        $attributes .= ' target="_blank"';
                    }
                    // Add rel="noopener noreferrer" if not present
                    if (!preg_match('/rel\s*=/i', $attributes)) {
                        $attributes .= ' rel="noopener noreferrer"';
                    }
                }

                return "<a {$attributes}>";
            },
            $html
        );
    }
}
