<?php

trait ViewHelpers {

    /**
     * Search for text on the page
     *
     * @param        $message
     * @param string $scope
     */
    protected function see($message, $scope = 'body')
    {
        $this->assertCount(
            1,
            $this->client->getCrawler()->filter("{$scope}:contains('{$message}')"),
            'Did not see message: ' . $message
        );
    }

    /**
     * Make sure text does not exist on the page
     * @param $message
     */
    protected function notSee($message)
    {
        $this->assertCount(
            0,
            $this->client->getCrawler()->filter("body:contains('{$message}')"),
            'Did not expect to see message: ' . $message
        );
    }

}