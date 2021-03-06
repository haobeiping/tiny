<?php

namespace App\Providers;

use App\Services\CategoryService;
use App\Services\CustomOrder;
use App\Services\PostService;
use App\Services\SettingCacheService;
use App\Services\SlugGenerator;
use App\Services\VisitorService;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->singleton(CategoryService::class, function () {
            return new CategoryService();
        });

        $this->app->singleton(PostService::class, function () {
            return new PostService();
        });

        $this->app->singleton(SettingCacheService::class, function () {
            return new SettingCacheService();
        });

        $this->app->singleton(CustomOrder::class, function () {
            return new CustomOrder();
        });

        $this->app->singleton(SlugGenerator::class, function () {
            return new SlugGenerator();
        });

        $this->app->singleton(VisitorService::class, function ($app) {
            return new VisitorService($app->make('request'));
        });
    }
}
