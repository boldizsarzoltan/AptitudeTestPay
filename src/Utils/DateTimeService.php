<?php

namespace Paysera\CommissionTask\Utils;

class DateTimeService
{
    public function getCurrentDateTime(
        ?\DateTime $dateTime = null //todo remove this
    ): \DateTime {
        if ($dateTime !== null) {
            return $dateTime;
        }
        return new \DateTime();
    }
}