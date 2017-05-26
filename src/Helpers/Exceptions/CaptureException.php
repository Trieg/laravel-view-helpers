<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @license https://mit-license.org MIT License
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace Cubes\View\Helpers\Exceptions;

use Throwable;

class CaptureException extends \Exception
{
    /**
     * Used to fill messages array.
     * @var array
     */
    protected $messages = [];

    /**
     * @var string
     */
    protected $message  = '';

    /**
     * CaptureException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        is_array($message)
        ? $this->messages[] = $message
        : $this->message    = $message;

        parent::__construct($message, $code, $previous);
    }
}