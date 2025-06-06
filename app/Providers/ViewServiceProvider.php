<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(['layouts.admin_layout', 'layouts.app'], function ($view) {
            $user = Auth::check() ? Auth::user() : null;
            $view->with('user', $user);
        });
    }
}