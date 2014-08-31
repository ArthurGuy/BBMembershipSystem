<?php namespace BB\Notifications;

use Illuminate\Session\Store;
use Illuminate\Support\MessageBag;

class Notification
{

    /**
     * @var \Illuminate\Session\Store
     */
    private $session;

    /**
     * @var \Illuminate\Support\MessageBag
     */
    private $details;

    private $message;

    private $level;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function build()
    {
        $this->loadData();
    }

    /**
     * @param       $message
     * @param array $details
     * @internal param null $heading
     */
    public function success($message, $details=null)
    {
        $this->message($message, $details, 'success');
    }

    /**
     * @param                                      $message
     * @param array|\Illuminate\Support\MessageBag $details
     * @internal param string $heading
     */
    public function error($message, MessageBag $details=null)
    {
        $this->message($message, $details, 'danger');
    }

    /**
     * @param                                      $message
     * @param array|\Illuminate\Support\MessageBag $details
     * @internal param null $heading
     */
    public function overlay($message, MessageBag $details=null)
    {
        $this->message($message, $details);
    }

    /**
     * @param                                      $message
     * @param array|\Illuminate\Support\MessageBag $details
     * @param string                               $level
     * @internal param $heading
     */
    public function message($message, MessageBag $details=null, $level = 'info')
    {
        $this->message = $message;
        $this->details = $details;
        $this->level = $level;

        $this->saveData();
    }

    private function saveData()
    {
        $this->session->flash('notification_data', (['message'=>$this->message, 'details'=>$this->details, 'level'=>$this->level]));
    }

    private function loadData()
    {
        if ($this->session->has('notification_data'))
        {
            $properties = ($this->session->get('notification_data'));
            $this->message = $properties['message'];
            $this->details = $properties['details'];
            $this->level = $properties['level'];
        }
    }

    public function hasMessage()
    {
        return !empty($this->message);
    }

    public function hasErrorDetail($detail, $response=null)
    {
        if ($this->details && $this->details->has($detail))
        {
            if ($response) {
                return $response;
            } else {
                return true;
            }
        }
        return false;
    }

    public function getErrorDetail($detail, $responseFormat='<span class="help-block">:message</span>')
    {
        if ($this->hasErrorDetail($detail))
        {
            return $this->details->first($detail, $responseFormat);
        }
    }

    public function hasDetails()
    {
        if ($this->details) {
            return true;
        }
        return false;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getLevel()
    {
        return $this->level;
    }
} 