// Create a middleware: php artisan make:middleware AJAXMiddleware
<?php
namespace App\Http\Middleware;

use Closure;

class AJAXMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        if ($request->ajax() || $request->header('X-AJAX-Request')) {
            // For AJAX requests, return only the content
            if (method_exists($response, 'getContent')) {
                $content = $response->getContent();
                // Extract just the .content div
                preg_match('/<div class="content"[^>]*>(.*?)<\/div>/s', $content, $matches);
                if (isset($matches[1])) {
                    return response($matches[1]);
                }
            }
        }
        
        return $response;
    }
}