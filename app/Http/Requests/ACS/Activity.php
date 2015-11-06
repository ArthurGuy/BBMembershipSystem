<?php namespace BB\Http\Requests\ACS;

use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(@SWG\Xml(name="Activity"))
 */
class Activity
{
    /**
     * @SWG\Property()
     * @var string
     */
    private $tagId;

    /**
     * @SWG\Property()
     * @var string
     */
    private $device;

    /**
     * Activity constructor.
     */
    public function __construct(Request $request)
    {
        $this->tagId = $request->get('tagId');
        $this->device = $request->get('device');
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
}