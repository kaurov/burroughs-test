<?php

namespace Burrough\Burroughs\Writer;

/**
 * Class CsvWriter
 * @package Burrough\Burroughs\Writer
 */
class CsvWriter
{
    /**
     * @var string
     */
    private $filename;

    private $fullFilePath;
    
    const PATH = 'output/';

    /**
     * @var resource
     */
    private $fileHandle;

    /**
     * @param $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->fullFilePath = __DIR__ . '/../../../' . static::PATH . $filename;

        //clean up (remove file if exists)
        if (file_exists($this->fullFilePath)) {
            unset($fullFilePath);
        }
        $this->fileHandle = fopen($this->fullFilePath, 'w');
    }

    /**
     * Append data to the file
     *
     * @param $data
     */
    public function appendData(array $data): bool
    {
        fputcsv($this->fileHandle, $data);
        return true;
    }
}
