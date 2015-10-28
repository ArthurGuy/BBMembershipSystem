<?php

namespace BB\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Photo
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var
     */
    private $id;

    /**
     * Photo constructor.
     *
     * @param $path
     * @param $id
     */
    public function __construct($path, $id)
    {
        $this->path = $path;
        $this->id = $id;
    }

    public static function fromArray($object, $i)
    {
        return new self($object['path'], $i);
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function photoUrl()
    {
        return 'https://s3-eu-west-1.amazonaws.com/' . getenv('S3_BUCKET') . '/' . \App::environment() . '/equipment-images/' . $this->path();
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }
}