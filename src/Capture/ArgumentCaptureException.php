<?php

namespace CirrusIdentity\SSP\Test\Capture;

use PHPUnit\Framework\MockObject\Stub\ReturnCallback;

/**
 * Workaround to allow throwing an exception from AspectMock while still capturing the method arguments.
 *
 * Allows us to cause an exception to be thrown from within SSP code prior to reaching hard to mock code, or code
 * that calls exit.
 */
class ArgumentCaptureException extends \Exception
{

    /**
     * @var array The arguments for the method call. $this->arguments[0] is the first argument.
     */
    protected array $arguments;

    /**
     * ArgumentCaptureException constructor.
     * @param string $message
     * @param array $arguments
     */
    public function __construct(string $message, array $arguments = [])
    {
        parent::__construct($message);
        $this->arguments = $arguments;
    }

    /**
     * Return the arguments that a method was called with
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

}
