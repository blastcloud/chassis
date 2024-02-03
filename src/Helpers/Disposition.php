<?php

namespace BlastCloud\Chassis\Helpers;

/**
 * Class Disposition
 * @package BlastCloud\Guzzler\Helpers
 * @property-read string|null $contents
 * @property-read string|null $contentType
 * @property-read int $contentLength;
 * @property-read string|null $filename
 * @property-read array|null $headers
 * @property-read string $name
 */
class Disposition
{
    protected ?string $contents;
    protected ?string $contentType;
    protected int $contentLength = 0;
    protected ?string $filename;
    protected array $headers = [];
    protected string $name;

    public function __construct(string $body)
    {
        $lines = preg_split("/((\r?\n)|(\r\n?))/", $body);

        foreach ($lines as $line) {
            // There is a blank line between fields and the value of the disposition.
            if(empty($line)) {
                break;
            }

            $start = strtok($line, ':');
            $method = strtolower(str_replace('-', '_', $start));
            $end = substr($line, strlen($start) + 2);

            // If method for this key exists, pass the rest of the line.
            if (method_exists($this, $method)) {
                $this->$method($end);
                continue;
            }

            // Otherwise, it's a header.
            $this->headers[$start] = $end;
        }

        if (empty($this->contentLength)) {
            $this->content_length(strlen(end($lines)));
        }

        $this->contents = substr($body, strlen($body) - $this->contentLength);
    }

    public function __get(string $name): mixed
    {
        return $this->$name ?? null;
    }

    public function isFile(): bool
    {
        return !empty($this->filename);
    }

    protected function content_disposition($line): void
    {
        foreach (explode(';', $line) as $datum) {
            $parts = explode('=', trim($datum));

            if (property_exists($this, $parts[0])) {
                $this->{$parts[0]} = trim($parts[1], '"');
            }
        }
    }

    protected function content_length($line): void
    {
        $this->contentLength = (int)$line;
    }

    protected function content_type($line): void
    {
        $this->contentType = strtok($line, ';');
    }
}