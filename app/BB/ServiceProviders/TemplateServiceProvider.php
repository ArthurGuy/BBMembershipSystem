<?php namespace BB\ServiceProviders;

use BB\Validators\CustomValidator;
use Illuminate\Support\ServiceProvider;

class TemplateServiceProvider extends ServiceProvider {


    public function boot()
    {
        $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler()->setEscapedContentTags('{{', '}}');
        $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler()->setContentTags('{!!', '!!}');
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