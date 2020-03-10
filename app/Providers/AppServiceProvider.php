<?php

namespace App\Providers;

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
        $this->app->bind(\App\Services\RewardFactory\RewardFactoryIface::class, \App\Services\RewardFactory\FirstRewardFactory::class);
        $this->app->bind(\App\Services\RewardManager\RewardManagerIface::class, \App\Services\RewardManager\RewardManagerDB::class);
        $this->app->bind(\App\Services\Banking\BankingIface::class, \App\Services\Banking\TrueBanking::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
