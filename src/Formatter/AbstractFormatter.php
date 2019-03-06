<?php

namespace CrCms\Request\Logger\Formatter;

use CrCms\Request\Logger\Contracts\FormatterContract;

abstract class AbstractFormatter implements FormatterContract
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * messageFormat.
     *
     * @param string $message
     * @return string
     */
    public function message(): string
    {
        $keywords = $this->keywords();
        $instances = $this->instances();

        return preg_replace_callback('/\{(\w+)\}/', function ($keyword) use ($keywords, $instances) {
            foreach ($keywords as $key => $value) {
                if (isset($value[$keyword[1]])) {
                    return call_user_func($value[$keyword[1]], $instances[$key]);
                }
            }

            return $keyword[0];
        }, $this->message);
    }

    /**
     * keywords.
     *
     * @return array
     */
    abstract protected function keywords(): array;

    /**
     * instances.
     *
     * @return array
     */
    abstract protected function instances(): array;
}
