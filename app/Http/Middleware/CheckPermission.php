<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $requiredRole)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $user = Auth::user();

        if ($user->roleId !== $requiredRole) {
            return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}