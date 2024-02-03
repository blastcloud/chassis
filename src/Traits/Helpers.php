<?php

namespace BlastCloud\Chassis\Traits;

use BlastCloud\Chassis\Helpers\Disposition;
use Psr\Http\Message\StreamInterface;

trait Helpers
{
    /**
     * Given an associative array of $fields, this method searches through the
     * $parsed array, verifying that the passed $fields both exist and match
     * the provided values. By default, it does not care if there are extra
     * fields, but passing true as $exclusive will cause extra fields to
     * force a false to return.
     */
    public function verifyFields(array $fields, array $parsed, bool $exclusive = false): bool
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
     */
    public function arrayMissing(string $key, mixed $value, array $haystack): bool
    {
        if (!isset($haystack[$key])) {
            return true;
        }

        $vals = $haystack[$key];

        return (is_array($vals) && !is_array($value) && !in_array($value, $vals))
            || (is_array($vals) && !empty(array_diff($value, $vals)))
            || (!is_array($vals) && $vals != $value);
    }

    /**
     * Given an array of objects or arrays, return every instance of a
     * specified field. Any empty values are eliminated.
     */
    public function pluck(array $collection, string $property): array
    {
        return array_filter(
            array_map(function ($item) use ($property) {
                return ((array)$item)[$property] ?? null;
            }, $collection)
        );
    }

    /**
     * Using the MultipartStream, split all fields and values into an array
     *
     * @param string $body
     * @param string $boundary
     * @return Disposition[]
     */
    protected function parseMultipartBody(string $body, string $boundary): array
    {
        // Remove any dashes at the beginning and end so that the following regex will work.
        $boundary = trim($boundary, '-');

        // Split based on the boundary and any dashes the client adds
        $split = preg_split("/-*\b{$boundary}\b-*/", $body, 0, PREG_SPLIT_NO_EMPTY);

        // Trim line breaks and delete empty values
        $dispositions = array_filter(array_map(function ($dis) { return trim($dis);}, $split));

        // Parse out the parts into keys and values
        return array_map(function ($item) {
            return new Disposition($item);
        }, array_values($dispositions));
    }

    protected function parseHeaderVariables(string $needle, string $headerLine): string | false
    {
        foreach (explode(';', $headerLine) as $item) {
            if (strpos($item, $needle) !== false) {
                $parts = explode($needle.'=', $item);
                return trim(end($parts), '" ');
            }
        }

        return false;
    }
}