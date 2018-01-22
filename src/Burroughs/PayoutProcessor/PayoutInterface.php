<?php

namespace Burrough\Burroughs\PayoutProcessor;

interface PayoutInterface
{
    /**
     * Get the processor name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the processed payout date of the process by a given initial date
     *
     * @param \DateTime $date
     *
     * @return \DateTime; 
     */
    public function getPayoutDate(\DateTime $date): \DateTime;
}
