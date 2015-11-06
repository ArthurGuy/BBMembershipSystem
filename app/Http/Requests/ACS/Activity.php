<?php namespace BB\Http\Requests\ACS;

use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(@SWG\Xml(name="Activity"))
 */
class Activity
{
    /**
     * @SWG\Property(description="The RFID Tag ID")
     * @var string
     */
    private $tagId;

    /**
     * @SWG\Property(description="The String of the device being controlled")
     * @var string
     */
    private $device;

    /**
     * @SWG\Property(description="Date Time of the event, YYYY-MM-DD HH:mm:ss")
     * @var string
     */
    private $occurredAt;

    /**
     * Activity constructor.
     */
    public function __construct(Request $request)
    {
        $this->tagId      = $request->get('tagId');
        $this->device     = $request->get('device');
        $this->occurredAt = $request->get('occurredAt');
    }

    /**
     * @return string
     */
    public function getTagId()
    {
        return $this->tagId;
    }

    /**
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @return string
     */
    public function getOccurredAt()
    {
        return $this->occurredAt;
    }
}