<?php namespace BB\Notifications;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\MessageBag;

class Notification
{

    /**
     * @var \Illuminate\Support\MessageBag
     */
    private $details;

    private $message;

    private $level;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function build()
    {
        $this->loadData();
    }

    /**
     * @param       $message
     * @param MessageBag $details
     */
    public function success($message, MessageBag $details = null)
    {
        $this->message($message, $details, 'success');
    }

    /**
     * @param                                      $message
     * @param MessageBag $details
     */
    public function error($message, MessageBag $details = null)
    {
        $this->message($message, $details, 'danger');
    }

    /**
     * @param                                      $message
     * @param MessageBag $details
     */
    public function overlay($message, MessageBag $details = null)
    {
        $this->message($message, $details);
    }

    /**
     * @param                                      $message
     * @param MessageBag $details
     * @param string                               $level
     */
    public function message($message, MessageBag $details = null, $level = 'info')
    {
        $this->message = $message;
        $this->details = $details;
        $this->level = $level;

        $this->saveData();
    }

    private function saveData()
    {
        $this->request->session()->flash('notification_data', (['message'=>$this->message, 'details'=>$this->details, 'level'=>$this->level]));
    }

    private function loadData()
    {
        if ($this->request->session()->has('notification_data')) {
            $properties = ($this->request->session()->get('notification_data'));
            $this->message = $properties['message'];
            $this->details = $properties['details'];
            $this->level = $properties['level'];
        }
    }

    public function hasMessage()
    {
        $this->loadData();
        return ! empty($this->message);
    }

    public function hasErrorDetail($detail, $response = null)
    {
        $this->loadData();
        if ($this->details && $this->details->has($detail)) {
            if ($response) {
                return $response;
            } else {
                return true;
            }
        }
        return false;
    }

    public function getErrorDetail($detail, $responseFormat = '<span class="help-block">:message</span>')
    {
        if ($this->hasErrorDetail($detail)) {
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