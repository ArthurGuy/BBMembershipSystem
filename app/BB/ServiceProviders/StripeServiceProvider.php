<?php namespace BB\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Stripe;

class StripeServiceProvider extends ServiceProvider
{


    public function boot()
    {
        if (!isset($_ENV['STRIPE_API_KEY'])) {
            return;
        }
        Stripe::setApiKey($_ENV['STRIPE_API_KEY']);

        $publishableKey = $_ENV['STRIPE_API_PUBLIC_KEY'];

        /*
         * Register blade compiler for the Stripe publishable key.
         */
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();
        $blade->extend(function ($view, $compiler) use($publishableKey) {
                $pattern = $compiler->createMatcher('stripeKey');
                return preg_replace($pattern, $publishableKey, $view);
            });
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