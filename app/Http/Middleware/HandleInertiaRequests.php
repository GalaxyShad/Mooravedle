<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $role_name = Auth::user()?->role->name;

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'is_techaer' => $role_name == 'Преподаватель'
            ],
        ];
    }
}
