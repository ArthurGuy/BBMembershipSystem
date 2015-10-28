<?php namespace BB\Domain\Infrastructure;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class DeviceProperties
{
    /**
     * @ORM\Column(type="string")
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="string")
     */
    private $modelNumber;

    /**
     * @ORM\Column(type="string")
     */
    private $serialNumber;

    /**
     * @ORM\Column(type="string")
     */
    private $colour;

    /**
     * DeviceProperties constructor.
     *
     * @param $manufacturer
     * @param $modelNumber
     * @param $serialNumber
     * @param $colour
     */
    public function __construct($manufacturer, $modelNumber, $serialNumber, $colour)
    {
        $this->manufacturer = $manufacturer;
        $this->modelNumber = $modelNumber;
        $this->serialNumber = $serialNumber;
        $this->colour = $colour;
    }

    /**
     * @return mixed
     */
    public function manufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @return mixed
     */
    public function modelNumber()
    {
        return $this->modelNumber;
    }

    /**
     * @return mixed
     */
    public function serialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @return mixed
     */
    public function colour()
    {
        return $this->colour;
    }
}