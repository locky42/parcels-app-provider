<?php

namespace locky42\ParcelsAppProvider\exceptions;

use Exception;

class ParcelsAppProviderError extends Exception
{
    protected int $status = 400;

    protected $decsription = null;

    /**
     * @param $message
     * @param $status
     */
    public function __construct($message = null, $description = null, $status = null)
    {
        $this->description = $description;
        parent::__construct($message, $status ?? $this->status);
    }

    /**
     * @return mixed|null
     */
    public function getDescription()
    {
        return $this->description;
    }
}
