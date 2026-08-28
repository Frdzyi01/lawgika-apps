<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectUsersTo(fn () => auth()->check() && auth()->user()->hasAdminAccess() ? '/admin' : '/dashboard');
        $middleware->redirectGuestsTo('/login');
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'logout',
            '/logout',
            'admin/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            return back()->withInput()->withErrors([
                'file' => 'Ukuran file atau data yang dikirim terlalu besar. Silakan kurangi ukuran lampiran Anda.'
            ]);
        });

        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                \Illuminate\Support\Facades\Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($request->is('logout') || $request->is('*/logout') || $request->routeIs('logout')) {
                return redirect('/?login=1');
            }

            return redirect('/?login=1')->with('error', 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan login kembali.');
        });
    })->create();
