<?php

namespace App\Http\Middleware;

use App\Services\InstallerService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstallationMiddleware
{
    public function __construct(protected InstallerService $installer)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        // If not installed and not already on install route, redirect to installer
        if (!$this->installer->isInstalled() && !$request->is('install*')) {
            return redirect()->route('install.welcome');
        }

        // If installed and trying to access installer, redirect to home
        if ($this->installer->isInstalled() && $request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
}
