<?php

namespace BlastCloud\Chassis\Traits;

trait Helpers
{
    /**
     * Given an associative array of $fields, this method searches through the
     * $parsed array, verifying that the passed $fields both exist and match
     * the provided values. By default, it does not care if there are extra
     * fields, but passing true as $exclusive will cause extra fields to
     * force a false to return.
     *
     * @param array $fields
     * @param array $parsed
     * @param bool $exclusive
     * @return bool
     */
    public function verifyFields(array $fields, array $parsed, $exclusive = false)
    {
        foreach ($fields as $key => $value) {
            if ($this->arrayMissing($key, $value, $parsed)) {
                return false;
            }
        }

        // Only if "exclusive" flag is set to true.
        if ($exclusive && count($parsed) > count($fields)) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if the $haystack passed does not have the $key or it
     * does, and the value does not match $value.
     *
     * @param string $key
     * @param mixed $value
     * @param array $haystack
     * @return bool
     */
    public function arrayMissing(string $key, $value, array $haystack)
    {
        if (!$vals = $haystack[$key] ?? false) {
            return false;
        }

        return (is_array($vals) && !is_array($value) && !in_array($value, $vals))
            || (is_array($vals) && !empty(array_diff($value, $vals)))
            || (!is_array($vals) && $vals != $value);
    }

    /**
     * Given an array of objects or arrays, return every instance of a
     * specified field. Any empty values are eliminated.
     *
     * @param array $collection
     * @param string $property
     * @return array
     */
    public function pluck(array $collection, string $property)
    {
        return array_filter(
            array_map(function ($item) use ($property) {
                return ((array)$item)[$property] ?? null;
            }, $collection)
        );
    }
}