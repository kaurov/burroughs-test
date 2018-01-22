<?php

namespace Burrough\Burroughs\PayoutProcessor;

/**
 * Class DefaultPayoutProcessor
 * @package Burrough\Burroughs\PayoutProcessor
 */
class DefaultPayoutProcessor extends AbstractPayoutProcessor implements PayoutInterface
{
    /**
     * Get the processed payout date of the process by a given initial date
     *
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    public function getPayoutDate(\DateTime $date): \DateTime
    {
        $date->modify($this->getDue());
        $currentWeekday = $date->format('w');

        if (in_array($currentWeekday, $this->getAllowedDays())) {
            return $date;
        }
        $dateBefore = clone $date;
        $date->modify($this->getFallback());

        $this->logDateModification($dateBefore, $date);

        return $date;
    }
}
