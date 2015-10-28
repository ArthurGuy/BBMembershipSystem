<?php namespace BB\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JsonPhotoCollectionDoctrineType extends Type
{

    const JSON_ENTITIES_NAME = 'jsonPhotoCollection'; // modify to match your type name

    /**
     * Made to be compatible with Doctrine 2.4 and 2.5; 2.5 added getJsonTypeDeclarationSQL().
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if (method_exists($platform, 'getJsonTypeDeclarationSQL')) {
            return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
        }

        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return array();
        }

        $value = (is_resource($value)) ? stream_get_contents($value) : $value;

        $objects = json_decode($value, true);

        $collection = new ArrayCollection();

        $i = 0;
        foreach ($objects as $object) {
            $collection->add(Photo::fromArray($object, $i));
            $i++;
        }

        return $collection;
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::JSON_ENTITIES_NAME;
    }
}