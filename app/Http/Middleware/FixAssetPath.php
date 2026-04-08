<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixAssetPath
{
    /**
     * Handle an incoming request.
     *
     * Mengganti semua relative path "buyer-file/" menjadi absolute "/buyer-file/"
     * agar asset tidak mengikuti URL nested seperti /promo/{id}.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        // Hanya proses response HTML
        $contentType = $response->headers->get('Content-Type', '');
        if (str_contains($contentType, 'text/html')) {
            $content = $response->getContent();

            // Ganti semua relative "buyer-file/" menjadi absolute "/buyer-file/"
            // Pola: cocokkan di dalam atribut href=", src=", url(' dan url("
            $content = preg_replace(
                '/(["\'])buyer-file\//i',
                '$1/buyer-file/',
                $content
            );

            // Juga tangani kasus tanpa kutip (misal: url(buyer-file/...)
            $content = preg_replace(
                '/\burl\(buyer-file\//i',
                'url(/buyer-file/',
                $content
            );

            $response->setContent($content);
        }

        return $response;
    }
}
