<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Vite;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        Vite::useCSPNonce();
        $response = $next($request);
        
        if ($this->isStaticAsset($request)) {
            return $response;
        }

        // Strict Transport Security
        $response->headers->set('Strict-Transport-Security', 
            'max-age=31536000; includeSubDomains; preload'
        );
        
        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy
        $response->headers->set('Permissions-Policy', 
            'accelerometer=(), autoplay=(), clipboard-write=(), encrypted-media=(), ' .
            'fullscreen=*, geolocation=(), gyroscope=(), magnetometer=(), ' .
            'microphone=(), camera=(), payment=(), usb=()'
        );
        
        // X-XSS-Protection (legacy)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Rozbudowany Content Security Policy
        $cspDirectives = $this->buildCSPDirectives();
        $response->headers->set('Content-Security-Policy', $cspDirectives);

        if ($linkHeader = $response->headers->get('Link')) {
            $cleanedLink = preg_replace('/;\s*nonce="[^"]*"/', '', $linkHeader);
            $response->headers->set('Link', $cleanedLink);
        }

        return $response;
    }

    private function buildCSPDirectives(): string
    {
        $nonce = Vite::cspNonce();
        $isProduction = app()->environment('production');
        
        $directives = [
            "default-src 'self'",
            
            "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net" . 
            ($isProduction ? "" : " 'unsafe-eval'"),

            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            
            
            "style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            
            
            "img-src 'self' data: blob: https:",
            
            
            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net",
            
            "connect-src 'self'" . 
            ($isProduction ? "" : " ws: wss:") . 
            " https://api.github.com", 
            
            "media-src 'self' data:",
            
            "object-src 'none'",
            
            "base-uri 'self'",
            
            "frame-ancestors 'self'",
            
            "form-action 'self'",
            
            "frame-src 'self' https://www.youtube.com https://www.google.com",
            
            "worker-src 'self' blob:",
            
            "manifest-src 'self'",
            
            "child-src 'self'",
            
            $isProduction ? "upgrade-insecure-requests" : "",
            
            $isProduction ? "block-all-mixed-content" : "",
        ];

        $directives = array_filter($directives);
        
        return implode('; ', $directives);
    }

    private function isStaticAsset(Request $request): bool
    {
        $path = $request->path();
        
        return str_starts_with($path, 'build/') || 
               str_starts_with($path, 'storage/') ||
               str_starts_with($path, 'assets/') ||
               preg_match('/\.(js|css|png|jpg|jpeg|gif|svg|woff|woff2|ttf|ico|map|webp|avif)$/', $path);
    }
}