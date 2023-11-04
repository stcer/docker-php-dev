<?php

declare(strict_types=1);

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_PROPERTY)]
class JsonSerialize
{
    public function __construct(public ?string $fieldName = null)
    {
    }
}

class VersionedObject
{
    #[JsonSerialize]
    public const version = '0.0.1';
}

class UserLandClass extends VersionedObject
{
    protected string $notSerialized = 'nope';

    #[JsonSerialize('foobar')]
    public string $myValue = '';

    #[JsonSerialize('companyName')]
    public string $company = '';

    #[JsonSerialize('userLandClass')]
    protected ?UserLandClass $test;

    public function __construct(?UserLandClass $userLandClass = null)
    {
        $this->test = $userLandClass;
    }
}

class AttributeBasedJsonSerializer
{

    protected const ATTRIBUTE_NAME = 'JsonSerialize';

    public function serialize($object)
    {
        $data = $this->extract($object);

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    protected function reflectProperties(array $data, ReflectionClass $reflectionClass, object $object)
    {
        $reflectionProperties = $reflectionClass->getProperties();
        foreach ($reflectionProperties as $reflectionProperty) {
            $attributes = $reflectionProperty->getAttributes(static::ATTRIBUTE_NAME);
            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();
                $name = $instance->fieldName ?? $reflectionProperty->getName();
                $value = $reflectionProperty->getValue($object);
                if (is_object($value)) {
                    $value = $this->extract($value);
                }
                $data[$name] = $value;
            }
        }

        return $data;
    }

    protected function reflectConstants(array $data, ReflectionClass $reflectionClass)
    {
        $reflectionConstants = $reflectionClass->getReflectionConstants();
        foreach ($reflectionConstants as $reflectionConstant) {
            $attributes = $reflectionConstant->getAttributes(static::ATTRIBUTE_NAME);
            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();
                $name = $instance->fieldName ?? $reflectionConstant->getName();
                $value = $reflectionConstant->getValue();
                if (is_object($value)) {
                    $value = $this->extract($value);
                }
                $data[$name] = $value;
            }
        }

        return $data;
    }

    protected function extract(object $object)
    {
        $data = [];
        $reflectionClass = new ReflectionClass($object);
        $data = $this->reflectProperties($data, $reflectionClass, $object);
        $data = $this->reflectConstants($data, $reflectionClass);

        return $data;
    }
}

$userLandClass = new UserLandClass();
$userLandClass->company = 'some company name';
$userLandClass->myValue = 'my value';

$userLandClass2 = new UserLandClass($userLandClass);
$userLandClass2->company = 'second';
$userLandClass2->myValue = 'my second value';

$serializer = new AttributeBasedJsonSerializer();
$json = $serializer->serialize($userLandClass2);

var_dump(json_decode($json, true));
