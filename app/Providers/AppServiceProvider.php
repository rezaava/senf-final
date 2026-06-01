<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Spatie\FlareClient\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('web.layouts.master', function ($view) {
            $cart = Cart::where('status', 'active')->where('user_id', auth()->id())->first();
            $cartItems = $cart?->reservations()?->count() ?? 0;

            $view->with('cartItems',$cartItems);
        });
    }
}
