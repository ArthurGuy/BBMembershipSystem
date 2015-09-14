<?php namespace BB\Providers;

use BB\Domain\Infrastructure\Device;
use BB\Domain\Infrastructure\DeviceRepository;
use BB\Domain\Infrastructure\Room;
use BB\Domain\Infrastructure\RoomRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\AnsiQuoteStrategy;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\Facades\Doctrine;

class DoctrineServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(EntityManager $em)
	{
		/** @var $em \Doctrine\ORM\EntityManager */
		$platform = $em->getConnection()->getDatabasePlatform();

		//register the enum type
		$platform->registerDoctrineTypeMapping('enum', 'string');

        Doctrine::extendAll(function($configuration) {
            $configuration->setQuoteStrategy(new AnsiQuoteStrategy());
        });
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->singleton(RoomRepository::class, function($app) {
			$em = $app->make(EntityManager::class);
			return $em->getRepository(Room::class);
		});

		$this->app->singleton(DeviceRepository::class, function($app) {
			$em = $app->make(EntityManager::class);
			return $em->getRepository(Device::class);
		});
	}

}
