<?php

require __DIR__.'/support/ViewHelpers.php';

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    use ViewHelpers;

    /**
     * Default preparation for each test
     *
     */
    public function setUp()
    {
        parent::setUp(); // Don't forget this!

        $this->prepareForTests();
    }

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		require __DIR__.'/../bootstrap/autoload.php';
        return require __DIR__.'/../bootstrap/app.php';
	}

    /**
     * Migrates the database and set the mailer to 'pretend'.
     * This will cause the tests to run quickly.
     *
     */
    private function prepareForTests()
    {
        //Artisan::call('migrate');
        Mail::pretend(true);
    }

}
