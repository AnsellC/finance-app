<?php

namespace App\Providers;

use App\Contracts\TransactionCalculatorInterface;
use App\Services\TransactionCalculatorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            TransactionCalculatorInterface::class,
            TransactionCalculatorService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
