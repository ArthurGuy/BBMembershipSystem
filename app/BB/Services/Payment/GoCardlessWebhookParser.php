<?php namespace BB\Services\Payment;

class GoCardlessWebhookParser
{

    /**
     * @var string
     */
    private $rawResponse = null;

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

    public function parseResponse($paymentPaidPayload)
    {
        $this->rawResponse = $paymentPaidPayload;
        $response = json_decode($this->rawResponse, true);
        if (!is_array($response)) {
            return;
        }
        $this->response = $response;

        $this->action = $this->response['payload']['action'];
        $this->resourceType = $this->response['payload']['resource_type'];

        if ($this->resourceType == 'bill') {
            $this->setBills($this->response['payload']['bills']);
        } else if ($this->resourceType == 'subscription') {
            $this->subscriptions = $this->response['payload']['subscriptions'];
        } else if ($this->resourceType == 'pre_authorization') {
            $this->preAuthList = $this->response['payload']['pre_authorizations'];
        }
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

    private function setBills($bills)
    {
        foreach ($bills as $i=>$bill) {
            if (!isset($bills[$i]['source_type'])) {
                $bills[$i]['source_type'] = '';
            }
            if (!isset($bills[$i]['source_id'])) {
                $bills[$i]['source_id'] = '';
            }
            if (!isset($bills[$i]['paid_at'])) {
                $bills[$i]['paid_at'] = '';
            }
        }
        $this->bills = $bills;
    }
}