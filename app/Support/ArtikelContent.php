<?php

namespace App\Support;

class ArtikelContent
{
    public static function sanitize(string $content): string
    {
        $decoded = json_decode($content, true);
        $html = is_string($decoded) ? $decoded : $content;
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $config->set('HTML.Allowed', 'p[style],br,strong,b,em,i,u,s,strike,h2[style],h3[style],h4[style],blockquote,ul,ol,li,a[href|title],img[src|alt|width|height],table,thead,tbody,tr,th[colspan|rowspan],td[colspan|rowspan],hr,span[style],div[style],iframe[src|width|height|frameborder]');
        $config->set('CSS.AllowedProperties', ['color', 'background-color', 'text-align', 'font-size', 'font-weight', 'font-style', 'text-decoration']);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true, 'data' => true]);
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^https://(www\.youtube\.com/embed/|www\.youtube-nocookie\.com/embed/|player\.vimeo\.com/video/)%');

        return (new \HTMLPurifier($config))->purify($html);
    }

    public static function isEmpty(string $html): bool
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');

        return preg_replace('/[\s\x{00A0}\x{200B}]+/u', '', $text) === ''
            && !preg_match('/<(?:table|hr)\b|<(?:img|iframe)\b[^>]*\bsrc=/i', $html);
    }
}
