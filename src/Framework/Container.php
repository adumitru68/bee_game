<?php

namespace App\Framework;

use Exception;
use ReflectionClass;
use ReflectionParameter;

class Container
{
    private static $instance;
    private array $storedInstances = [];

    public function init(array $initConfig)
    {
        $this->storedInstances = $initConfig;
    }

    public function get($class)
    {
        if (array_key_exists($class, $this->storedInstances)) {
            return $this->storedInstances[$class];
        }

        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("[$class] is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            $this->storedInstances[$class] = new $class;
            return $this->storedInstances[$class];
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        $this->storedInstances[$class] = $reflector->newInstanceArgs($dependencies);

        return $this->storedInstances[$class];
    }

    public function has(string $class): bool
    {
        return array_key_exists($class, $this->storedInstances);
    }

    private function getDependencies($parameters)
    {
        $dependencies = array();

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            if (is_null($dependency)) {
                $dependencies[] = $this->resolveNonClass($parameter);
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }

    private function resolveNonClass(ReflectionParameter $parameter)
    {
        if(array_key_exists($parameter->getName(), $this->storedInstances)) {
            return $this->storedInstances[$parameter->getName()];
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new Exception("Erm.. Cannot resolve the unkown!?");
    }

    public static function getInstance(): Container
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}