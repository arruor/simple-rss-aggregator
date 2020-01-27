<?php

namespace App\Providers;

use App\Service\FeedReaderService;
use App\Service\FeedReaderServiceInterface;
use Illuminate\Support\ServiceProvider;

class FeedReaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(FeedReaderServiceInterface::class, FeedReaderService::class);
    }
}
