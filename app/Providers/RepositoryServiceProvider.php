<?php

namespace App\Providers;

use App\Core\Repositories\AdminRepository;
use App\Core\Repositories\Contracts\AdminRepositoryInterface;
use App\Core\Repositories\Contracts\CustomerRepositoryInterface;
use App\Core\Repositories\CustomerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }
}
