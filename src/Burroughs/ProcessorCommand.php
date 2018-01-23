<?php

namespace Burrough\Burroughs;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Burrough\Burroughs\Data\PayoutData;
use Burrough\Burroughs\Writer\CsvWriter;

class ProcessorCommand extends Command
{
    /**
     * Configure Console Comand
     */
    protected function configure()
    {
        $this
            ->setName('payout:generate')
            ->setDescription('Generation of a payout plan')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Specify an output filename!'
            );
    }

    /**
     * Executes command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = Yaml::parse(__DIR__ . '/../config/config.yml');
        $fullLogPath = __DIR__ . '/../../tmp/';
        if (!file_exists($fullLogPath)) {
            mkdir($fullLogPath);
        }

        $logger = new Logger($config['monolog']['name']);
        $logger->pushHandler(new StreamHandler($fullLogPath  . $config['monolog']['path'], Logger::WARNING));

        //initialize config and file paths, csv writer
        $filename   = $input->getArgument('filename');
        $csvWriter  = new CsvWriter($filename);

        $output->writeln('*****************************');
        $output->writeln('Payout Report Processor');
        $output->writeln('*****************************');
        $output->writeln(sprintf('Generating and Saving Payout Report to: <info>%s</info>', CsvWriter::PATH . $filename));

        $processes = $this->getProcesses($config, $logger);

        //set start date and timezone
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone($config['datetime']['timezone']));

        $isFirstLine = true;

        for ($i = 0; $i < $config['datetime']['month_count']; $i++) {
            $payoutDataItem = $this->getPayoutData($processes, $date);
            if ($isFirstLine) {
                $csvWriter->appendData($payoutDataItem->getHeaderAsArray());
            }
            $csvWriter->appendData($payoutDataItem->getDataAsArray());
            $isFirstLine = false;

            $date->modify('+1 month');
        }

        $output->writeln(sprintf('Total Processes: <info>%s</info>', count($processes)));
        $output->writeln(sprintf('Total Months processed: <info>%s</info>', $config['datetime']['month_count']));
    }

    /**
     * Instantiates and returns processes given in configuration file
     *
     * @param $parameters Parameters given in configuration file
     *
     * @return array Array of processes
     */
    protected function getProcesses(array $parameters, LoggerInterface $logger): array
    {
        $processes = [];
        foreach ($parameters['processes'] as $process) {
            $class = $process['class'];
            //check if class implements Payout Interface
            $interfaces = class_implements($class);
            if (!isset($interfaces['Burrough\Burroughs\PayoutProcessor\PayoutInterface'])) {
                continue;
            }

            $processes[] = new $class($process['config'], $logger);
        }

        return $processes;
    }

    /**
     * Return a PayoutData object (DTO) containing
     * the month and multiple payout calculation items as defined in the config file
     *
     * @param $processes Array of processes to be executed
     * @param $date The current datetime
     *
     * @return PayoutData The DTO Object containing the data
     */
    protected function getPayoutData($processes, $date)
    {
        $payoutData = new PayoutData();
        $payoutData->setDate($date->format('Y-m'));

        foreach ($processes as $process) {
            $currentDate = clone $date;
            $dateCalculated = $process->getPayoutDate($currentDate);
            $payoutData->addItem($process->getName(), $dateCalculated->format('Y-m-d'));
        }

        return $payoutData;
    }
}
