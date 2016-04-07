<?php

namespace App\Providers;

use App\Auth\Registrar;
use App\Auth\Subscriber;
use App\Console\Install;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\TeamRepository;
use App\Billing\EmailInvoiceNotifier;
use App\Contracts\Billing\InvoiceNotifier;
use App\Contracts\Auth\Registrar as RegistrarContract;
use App\Contracts\Auth\Subscriber as SubscriberContract;
use App\Contracts\Repositories\UserRepository as UserRepositoryContract;
use App\Contracts\Repositories\TeamRepository as TeamRepositoryContract;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->defineRoutes();
        });
    }

    /**
     * Define the Spark routes.
     *
     * @return void
     */
    protected function defineRoutes()
    {
        if (! $this->app->routesAreCached()) {
            $router = app('router');

            $router->group(['namespace' => 'App\Http\Controllers'], function ($router) {
                require __DIR__.'/../Http/routes.php';
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->defineServices();
    }

    /**
     * Bind the Spark services into the container.
     *
     * @return void
     */
    protected function defineServices()
    {
        $services = [
            RegistrarContract::class => Registrar::class,
            InvoiceNotifier::class => EmailInvoiceNotifier::class,
            UserRepositoryContract::class => UserRepository::class,
            TeamRepositoryContract::class => TeamRepository::class,
        ];

        foreach ($services as $key => $value) {
            $this->app->bindIf($key, $value);
        }
    }
}
