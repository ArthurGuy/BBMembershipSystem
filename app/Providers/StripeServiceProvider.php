<?php namespace BB\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe;

class StripeServiceProvider extends ServiceProvider
{


    public function boot()
    {
        Stripe::setApiKey(env('STRIPE_API_KEY', ''));

        $publishableKey = env('STRIPE_API_PUBLIC_KEY', '');

        /*
         * Register blade compiler for the Stripe publishable key.
         */
        view()->share('stripeKey', $publishableKey);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}