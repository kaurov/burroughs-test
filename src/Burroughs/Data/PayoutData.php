<?php
namespace Burrough\Burroughs\Data;

/**
 * The DTP for the processed data
 *
 * Class PayoutData
 * @package Burrough\Burroughs\Data
 */
class PayoutData implements PayoutDataInterface
{
    /**
     * @var
     */
    private $date;

    /**
     * @var
     */
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Get Date
     *
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set Date
     *
     * @param mixed $date
     * @return $this
     */
    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get Items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set Items
     *
     * @param mixed $items
     * @return $this
     */
    public function setItems($items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Add single item to items
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addItem($key, $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Return data as array
     *
     * @return mixed
     */
    public function getDataAsArray(): array
    {
        return array_merge(
            array($this->getDate()),
            $this->items
        );
    }

    /**
     * Return header of data as array
     *
     * @return mixed
     */
    public function getHeaderAsArray(): array
    {
        return array_merge(
            array('month'),
            array_keys($this->items)
        );
    }
}
