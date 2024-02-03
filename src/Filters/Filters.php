<?php

namespace BlastCloud\Chassis\Filters;

use BlastCloud\Chassis\Interfaces\With;

trait Filters
{
    protected array $filters = [];
    protected static array $namespaces = [__NAMESPACE__];

    /**
     * Add a namespace to look through when dynamically looking for filters.
     */
    public static function addNamespace(string $namespace): void
    {
        if (!in_array($namespace, static::$namespaces)) {
            static::$namespaces = [$namespace, ...static::$namespaces];
        }
    }

    public static function namespaces(): array
    {
        return static::$namespaces;
    }

    /**
     * Determine if the method called is a filter, a.k.a. starts with "with".
     */
    protected function isFilter(string $name): With|false
    {
        $parts = preg_split('/(?=[A-Z])/',$name);
        if ($parts[0] == 'with') {
            return $this->findFilter([$parts[1], rtrim($parts[1], 's')]);
        }

        return false;
    }

    /**
     * Iterate through all namespaces to find a matching class.
     */
    protected function findFilter(array $names): With|false
    {
        foreach ($names as $name) {
            if (isset($this->filters[$name])) {
                return $this->filters[$name];
            }

            foreach (self::$namespaces as $namespace) {
                $class = rtrim($namespace, '\\'). "\\With" . $name;

                if (class_exists($class)) {
                    $this->filters[$name] = $filter = new $class;
                    return $filter;
                }
            }
        }

        return false;
    }
}