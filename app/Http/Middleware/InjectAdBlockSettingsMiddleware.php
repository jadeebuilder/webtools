<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AdBlockService;

class InjectAdBlockSettingsMiddleware
{
    /**
     * Le service AdBlock
     *
     * @var AdBlockService
     */
    protected $adBlockService;

    /**
     * Créer une nouvelle instance du middleware.
     *
     * @param AdBlockService $adBlockService
     * @return void
     */
    public function __construct(AdBlockService $adBlockService)
    {
        $this->adBlockService = $adBlockService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ignorer les requêtes AJAX et les requêtes d'API
        if ($request->ajax() || $request->is('api/*')) {
            return $next($request);
        }

        // Ignorer les requêtes d'admin
        if ($request->is('*/admin*')) {
            return $next($request);
        }

        // Obtenir la réponse
        $response = $next($request);

        // Ne traiter que les réponses HTML
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // Injecter le script dans le contenu de la réponse
        $content = $response->getContent();
        $script = $this->adBlockService->getScript();

        if ($script && $content) {
            $bodyPosition = strripos($content, '</body>');
            
            if ($bodyPosition !== false) {
                $content = substr($content, 0, $bodyPosition) . $script . substr($content, $bodyPosition);
                $response->setContent($content);
            }
        }

        return $response;
    }

    /**
     * Détermine si la réponse est au format HTML
     *
     * @param  Response  $response
     * @return bool
     */
    protected function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('content-type');
        
        return $contentType && (
            str_contains($contentType, 'text/html') || 
            str_contains($contentType, 'application/xhtml+xml')
        );
    }
}
