<?php

namespace App\Providers;

use App\Http\Repositories\Interfaces\TaskRepositoryInterface;
use App\Http\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $repositories = [
            TaskRepositoryInterface::class => TaskRepository::class
        ];

        foreach ($repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
