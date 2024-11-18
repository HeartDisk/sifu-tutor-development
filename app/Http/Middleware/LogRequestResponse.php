<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ApiLog;

class LogRequestResponse
{
   
    public function handle(Request $request, Closure $next)
    {
       
        $response = $next($request);

        $responseContent = $response->getContent();
        $decodedContent = $this->decodeResponseContent($responseContent);

        ApiLog::create([
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => json_encode($request->header()),
            'body' => json_encode($request->except(['password', 'token'])),
            'status' => $response->status(),
            'response_content' => json_encode($decodedContent),
        ]);
        
        return $response;
    }

    protected function decodeResponseContent($content)
    {
        
        $decoded = json_decode($content, true);
       
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
    
        return $content;
    }
}
