<?php

namespace App\Providers;

use App\Models\Sale;
use App\Observers\SaleObserver;
use App\Models\SaleItem;
use App\Observers\SaleItemObserver;
use App\Services\Logging\LoggerInterface;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            LoggerInterface::class,
            fn () => \App\Services\Logging\LoggerFactory::create()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sale::observe(SaleObserver::class);
        SaleItem::observe(SaleItemObserver::class);

        Event::listen(Login::class, function (Login $event): void {
            /** @var LoggerInterface $logger */
            $logger = app(LoggerInterface::class);

            $logger->logAudit(
                'login',
                'Usuário autenticado com sucesso.',
                get_class($event->user),
                $event->user->getAuthIdentifier()
            );
        });
    }
}
