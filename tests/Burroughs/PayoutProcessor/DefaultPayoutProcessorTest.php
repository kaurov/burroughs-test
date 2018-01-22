<?php
declare(strict_types=1);
namespace Burrough\Tests\Burroughs\PayoutCalculator;

use Burrough\Burroughs\PayoutProcessor\DefaultPayoutProcessor;

/**
 * Class Driver
 *
 */
final class DefaultPayoutProcessorTest extends \PHPUnit\Framework\TestCase
{
    private $payoutProcessor;

    public function setUp()
    {
        parent::setUp();

        $config = [
            'name'          => 'salary',
            'due'           => 'last day of this month',
            'allowed_days'  => [1, 2, 3, 4, 5],
            'fallback'      => 'last friday'
        ];

        $logger = $this->getMockedLogger();
        $this->payoutProcessor = new DefaultPayoutProcessor($config, $logger);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->payoutProcessor = null;
    }

    public function testGetName()
    {
        static::assertEquals($this->payoutProcessor->getName(), 'salary');
    }

    public function testLastDayOfMonthOnSaturday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 5, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-05-30'
        );
    }

    public function testLastDayOfMonthOnSunday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 11, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-11-28'
        );
    }

    public function testLastDayOfMonthOnFriday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 2, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-02-28'
        );
    }

    public function testLastDayOfMonthOnMonday()
    {
        $date = new \DateTime();
        $date->setDate(2014, 3, 1);
        $dateCalculated = $this->payoutProcessor->getPayoutDate($date);

        static::assertEquals(
            $dateCalculated->format('Y-m-d'),
            '2014-03-31'
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
