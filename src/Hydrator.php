<?php

namespace Firehose;

use ReflectionClass;
use ReflectionProperty;

class Hydrator
{
    public static function newInstance($classname, array $data = [])
    {
        $refl = new ReflectionClass($classname);

        $instance = $refl->newInstanceWithoutConstructor();

        return static::mutate($instance, $data);
    }

    public static function mutate($instance, array $data = [])
    {
        $classname = get_class($instance);

        foreach($data as $key => $value){
            $reflProp = new ReflectionProperty($classname, $key);
            $reflProp->setAccessible(true);
            $reflProp->setValue($instance, $value);
        }

        unset($reflProp);

        return $instance;
    }

    public static function extract($instance, array $properties = [])
    {   
        $data = [];
        $classname = get_class($instance);

        if(empty($properties)){
            $properties = static::getProperties($instance);
        }

        foreach($properties as $key){
            $reflProp = new ReflectionProperty($classname, $key);
            $reflProp->setAccessible(true);
            $data[$key] = $reflProp->getValue($instance);
        }

        unset($reflProp);
        
        return $data;
    }

    public static function getProperties($instance)
    {
        $refl = new ReflectionClass($instance);

        return array_map(function($reflProperty){
            return $reflProperty->getName();
        }, $refl->getProperties());
    }
}