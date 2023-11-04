<?php

#[Attribute]
class Fruit
{
    public string $value;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}

#[Attribute]
class Red
{
}

#[Fruit('demo')]
#[Red]
class Apple
{
    #[Fruit('demo1')]
    public function execute()
    {

    }
}

$class = new ReflectionClass('Apple');
$attributes = $class->getAttributes();
print_r(array_map(function ($attribute){
    $name = $attribute->getName();
    $class = $attribute->newInstance();
    var_dump($class);
    return $name;
}, $attributes));


echo "Methods\n";
foreach ($class->getMethods() as $method) {
    $attributes = $method->getAttributes();

    if (count($attributes) > 0) {
        print_r(array_map(
            function ($attribute){
                $name = $attribute->getName();
                $class = $attribute->newInstance();
                var_dump($class);
                return $name;
            },
            $attributes
        ));
    }
}

