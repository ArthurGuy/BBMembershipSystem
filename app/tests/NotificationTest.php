<?php

class NotificationTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        Session::start();
    }

    public function testTheDefaultNotificationIsDisplayed()
    {
        Notification::message('General Message');

        $this->call('GET', '/');

        $this->see('General Message', '.alert-info');
    }

    public function testTheSuccessNotificationIsDisplayed()
    {
        Notification::success('Success Message');

        $this->call('GET', '/');

        $this->see('Success Message', '.alert-success');
    }

    public function testTheErrorNotificationIsDisplayed()
    {
        Notification::error('Error Message');

        $this->call('GET', '/');

        $this->see('Error Message', '.alert-danger');
    }

    public function testTheErrorDetailNotificationIsDisplayed()
    {
        $details = new \Illuminate\Support\MessageBag(['line1'=>'Error Line 1', 'line2'=>'Error Line 2']);
        Notification::error('Error Message', $details);

        $this->call('GET', '/');

        $this->see('Error Message', '.alert-danger');
        $this->see('Error Line 1', '.alert-danger');
        $this->see('Error Line 2', '.alert-danger');
    }

    public function testNotificationPassesThrough()
    {
        Notification::message('General Message');

        $this->assertEquals('General Message', Notification::getMessage());
    }


} 