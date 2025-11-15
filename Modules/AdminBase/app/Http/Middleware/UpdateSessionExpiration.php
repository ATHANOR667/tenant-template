<?php

namespace Modules\AdminBase\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\AdminBase\Services\LogUserConnectionService;
use Symfony\Component\HttpFoundation\Response;

class UpdateSessionExpiration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = $this->getAuthenticatedUser();

        if ($data) {
            // Stocker les données pour terminate
            $request->attributes->set('auth_data', $data);
        }

        return $next($request);
    }

    /**
     * Terminate is executed after the response is sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        $data = $request->attributes->get('auth_data');

        if ($data) {
            LogUserConnectionService::updateSessionExpiration(
                $data['user'],
                $data['guard']
            );
        }
    }

    /**
     * Retrieve authenticated user and guard.
     */
    private function getAuthenticatedUser(): ?array
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            $auth = Auth::guard($guard);
            if ($auth->check()) {
                return ['guard' => $guard, 'user' => $auth->user()];
            }
        }

        Log::warning("Aucun utilisateur authentifié trouvé");
        return null;
    }
}
