<?php

namespace Burrough\Burroughs\Data;

/**
 * Interface for DTOs of PayoutDatas
 *
 * Interface PayoutDataInterface
 * @package Burrough\Burroughs\Data
 */
interface PayoutDataInterface {

    /**
     * Return data as array
     *
     * @return mixed
     */
    public function getDataAsArray(): array;

    /**
     * Return header of data as array
     *
     * @return mixed
     */
    public function getHeaderAsArray(): array;
}
