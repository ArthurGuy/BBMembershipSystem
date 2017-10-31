<?php namespace BB\Services\Payment;

class GoCardlessWebhookParser
{

    /**
     * @var array
     */
    private $response;

    /**
     * @var string
     */
    private $action = null;

    /**
     * @var string
     */
    private $resourceType = null;

    /**
     * @var array
     */
    private $bills = [];

    /**
     * @var array
     */
    private $subscriptions = [];

    /**
     * @var array
     */
    private $preAuthList = [];

    public function parseResponse(array $response)
    {
        $this->response = $response;

        $this->action = $this->response['action'];
        $this->resourceType = $this->response['resource_type'];
    }

    /**
     * @return null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return null
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * @return array
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * @return array
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @return array
     */
    public function getPreAuthList()
    {
        return $this->preAuthList;
    }
}
