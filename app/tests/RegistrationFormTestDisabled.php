<?php 

class RegistrationFormTestDisabled extends TestCase {


    public function testRegisterUrl()
    {
        $crawler = $this->client->request('GET', '/account/create');

        $this->assertTrue($this->client->getResponse()->isOk());
    }


    public function testShortRegisterUrl()
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertTrue($this->client->getResponse()->isOk());
    }
} 