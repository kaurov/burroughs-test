<?php
declare(strict_types=1);
namespace Burrough\Tests\Burroughs\PayoutCalculator;

use Burrough\Burroughs\PayoutProcessor\CustomDueDatePayoutProcessor;

/**
 * Class Driver
 *
 */
final class CustomDueDatePayoutProcessorTest extends \PHPUnit\Framework\TestCase
{
    protected $payoutProcessor;

    public function setUp()
    {
        parent::setUp();

        $config = [
            'name'          => 'bonus',
            'due'           => '15',
            'allowed_days'  => [1, 2, 3, 4, 5],
            'fallback'      => 'next wednesday'
        ];

        $logger = $this->getMockedLogger();
        $this->payoutProcessor = new CustomDueDatePayoutProcessor($config, $logger);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->payoutProcessor = null;
    }

    public function testGetName()
    {
        static::assertEquals($this->payoutProcessor->getName(), 'bonus');
    }

    public function testDayOfMonthOnSaturday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 2, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-02-19'
        );
    }

    public function testDayOfMonthOnSunday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 6, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-06-18'
        );
    }

    public function testDayOfMonthOnFriday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 8, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-08-15'
        );
    }

    public function testDayOfMonthOnMonday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 9, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-09-15'
        );
    }

    protected function getMockedLogger()
    {
        $mock = $this->getMockBuilder('Monolog\Logger')
            ->disableOriginalConstructor()
            ->setMethods(['__construct', 'addWarning'])
            ->getMock();

        return $mock;
    }
}
