<?php

declare(strict_types = 1);

namespace App\Logger;

use Monolog\Formatter\JsonFormatter as BaseFormatter;
use Monolog\LogRecord;

use function is_array;

class JsonFormatter extends BaseFormatter
{
    /**
     * @var array<string, string>
     */
    private array $tags;

    /**
     * @param array<string, string> $tags
     */
    public function __construct(array $tags)
    {
        parent::__construct();

        $this->tags = $tags;
    }

    public function format(LogRecord $record): string
    {
        $this->clearArgs($record);

        $record->offsetSet('extra', $this->tags);

        return parent::format($record);
    }

    private function clearArgs(LogRecord $record): void
    {
        foreach ($record->context as &$item) {
            if (!is_array($item) || !isset($item['args'])) {
                continue;
            }

            $item['args'] = [];
        }
    }
}
