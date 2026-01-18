<?php

namespace App\Providers;

use App\Contracts\Payments\AlfaApiClientInterface;
use App\Contracts\Payments\BankApiAdapterInterface;
use App\Contracts\Payments\CapturableBankApiAdapterInterface;
use App\Contracts\Payments\SberApiClientInterface;
use App\Contracts\HealthCheckServiceInterface;
use App\Contracts\TokenServiceInterface;
use App\Mocks\Payments\AlphaPaymentApiMock;
use App\Mocks\Payments\SberPaymentApiMock;
use App\Services\HealthCheckService;
use App\Services\Payments\Providers\Adapters\AlfaApiAdapter;
use App\Services\Payments\Providers\Adapters\SberApiAdapter;
use App\Services\Payments\Providers\AlfaBankProvider;
use App\Services\Payments\Providers\Clients\AlfaBankApiClient;
use App\Services\Payments\Providers\Clients\SberBankApiClient;
use App\Services\Payments\Providers\SberBankProvider;
use App\Services\TokenService;
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
        $this->app->when(SberBankProvider::class)
            ->needs(BankApiAdapterInterface::class)
            ->give(SberApiAdapter::class);

        $this->app->when(AlfaBankProvider::class)
            ->needs(CapturableBankApiAdapterInterface::class)
            ->give(AlfaApiAdapter::class);

        if ($this->app->environment('testing')) {
            $this->app->bind(AlfaApiClientInterface::class, AlphaPaymentApiMock::class);
            $this->app->bind(SberApiClientInterface::class, SberPaymentApiMock::class);
        } else {
            $this->app->bind(AlfaApiClientInterface::class, AlfaBankApiClient::class);
            $this->app->bind(SberApiClientInterface::class, SberBankApiClient::class);
        }

        $this->app->bind(TokenServiceInterface::class, TokenService::class);
        $this->app->bind(HealthCheckServiceInterface::class, HealthCheckService::class);
    }
}
