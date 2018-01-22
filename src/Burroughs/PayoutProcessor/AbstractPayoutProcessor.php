<?php

namespace Burrough\Burroughs\PayoutProcessor;

use Psr\Log\LoggerInterface;

/**
 * Class DefaultPayoutProcessor
 * @package Burrough\Burroughs\PayoutProcessor
 */
abstract class AbstractPayoutProcessor
{
    private $name;

    /**
     * @var string
     */
    private $due;

    /**
     * @var array
     */
    private $allowedDays;

    /**
     * @var string
     */
    private $fallback;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Set the configuration
     *
     * @param array $config
     */
    public function __construct(array $config, LoggerInterface $logger)
    {
        $this->name = $config['name'];
        $this->due = $config['due'];
        $this->allowedDays = $config['allowed_days'];
        $this->fallback = $config['fallback'];
        $this->logger = $logger;
    }

    /**
     * Get the processed payout date of the process by a given initial date
     *
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    abstract public function getPayoutDate(\DateTime $date);

    /**
     * Logs a date modification
     *
     * @param $dateBefore
     * @param $dateAfter
     *
     * @return $this
     */
    public function logDateModification(\DateTime $dateBefore, \DateTime $dateAfter): self
    {
        $this->logWarning(
            sprintf(
                '%s is not an allowed weekday. Modifying date to %s',
                $dateBefore->format('Y-m-d'),
                $dateAfter->format('Y-m-d')
            )
        );

        return $this;
    }

    /**
     * Logs a warning message
     *
     * @param string $message The message to log
     *
     * @return $this
     */
    public function logWarning(string $message): self
    {
        $this->getLogger()->addWarning($message);

        return $this;
    }

    /**
     * Get the processor name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAllowedDays(): array
    {
        return $this->allowedDays;
    }

    /**
     * @return string
     */
    public function getDue(): string
    {
        return $this->due;
    }

    /**
     * @return string
     */
    public function getFallback(): string
    {
        return $this->fallback;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
