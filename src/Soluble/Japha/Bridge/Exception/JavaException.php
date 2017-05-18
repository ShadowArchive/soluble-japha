<?php

/*
 * Soluble Japha
 *
 * @link      https://github.com/belgattitude/soluble-japha
 * @copyright Copyright (c) 2013-2017 Vanvelthem Sébastien
 * @license   MIT License https://github.com/belgattitude/soluble-japha/blob/master/LICENSE.md
 */

namespace Soluble\Japha\Bridge\Exception;

use Exception;

class JavaException extends Exception implements JavaExceptionInterface
{
    /**
     * @var string
     */
    protected $javaClassName;

    /**
     * @var string
     */
    protected $cause;

    /**
     * @var string
     */
    protected $stackTrace;

    /**
     * @var Exception
     */
    protected $driverException;

    /**
     * Constructor.
     *
     * @param string    $message
     * @param string    $javaCause
     * @param string    $stackTrace
     * @param string    $javaClassName   originating java FQDN
     * @param int       $code
     * @param Exception $driverException
     * @param Exception $previous
     */
    public function __construct($message,
                                $javaCause,
                                $stackTrace,
                                $javaClassName,
                                $code = null,
                                Exception $driverException = null,
                                Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setCause($javaCause);
        $this->setStackTrace($stackTrace);
        $this->setJavaClassName($javaClassName);
        if ($driverException !== null) {
            $this->setDriverException($driverException);
        }
    }

    /**
     * Set original exception as throw by the driver.
     *
     * @param Exception $driverException
     */
    protected function setDriverException(Exception $driverException)
    {
        $this->driverException = $driverException;
    }

    /**
     * Return underlying driver exception.
     *
     * @return Exception|null
     */
    public function getDriverException()
    {
        return $this->driverException;
    }

    /**
     * Set Java cause.
     *
     * @param string $cause
     */
    protected function setCause($cause)
    {
        $this->cause = $cause;
    }

    /**
     * {@inheritdoc}
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * @param string $stackTrace
     */
    protected function setStackTrace($stackTrace)
    {
        $this->stackTrace = $stackTrace;
    }

    /**
     * {@inheritdoc}
     */
    public function getStackTrace()
    {
        return $this->stackTrace;
    }

    /**
     * {@inheritdoc}
     */
    public function getJavaClassName()
    {
        return $this->javaClassName;
    }

    /**
     * @param string $javaClassName
     */
    protected function setJavaClassName($javaClassName)
    {
        $this->javaClassName = $javaClassName;
    }
}
