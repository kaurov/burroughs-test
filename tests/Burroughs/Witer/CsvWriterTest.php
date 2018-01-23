<?php

declare(strict_types = 1);

namespace Burrough\Tests\Burroughs\Writer;

use Burrough\Burroughs\Writer\CsvWriter;

/**
 * @covers Burrough\Burroughs\Writer\CsvWriter
 *
 */
final class CsvWriterTest extends \PHPUnit\Framework\TestCase
{

    private $filename;

    public function setUp()
    {
        parent::setUp();
        $this->filename = 'unittest.csv';
    }

    public function testCsvWriter()
    {
        $csvWriter = new CsvWriter($this->filename);
        static::assertInstanceOf(CsvWriter::class, $csvWriter);
    }
    
    public function testAppendDataExpectsArray()
    {
        static::expectException(\TypeError::class);
        $csvWriter = new CsvWriter($this->filename);
        $csvWriter->appendData('some string');
    }

    /**
     * @dataProvider provider
     * @group csv
     */
    public function testAppendData( $data)
    {
        $csvWriter = new CsvWriter($this->filename);
        $csvWriter->appendData($data);

        static::assertEquals([$data], $this->csvReader());
    }


    /**
     * @return array
     */
    public function provider(): array
    {

        $data[] = [
            [
                0 => 'month',
                1 => 'salary',
                2 => 'bonus',
            ]
        ];


        $data[] = [
            [
                0 => '2018-01',
                1 => '2018-01-31',
                2 => '2018-01-15',
            ]
        ];

        return $data;
    }

    /**
     * @return array
     */
    private function csvReader(): array
    {
        $file = file_get_contents(CsvWriter::PATH . $this->filename);
        foreach (explode("\n", $file, -1) as $line) {
            $data[] = explode(',', $line);
        }
        return $data;
    }

}
