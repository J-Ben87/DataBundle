<?php

namespace JBen87\DataBundle\Command;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Faker\Provider\Base as Provider;
use JBen87\DataBundle\Dataset\DatasetInterface;
use JBen87\DataBundle\DependencyInjection\Compiler\DatasetCompilerPass;
use JBen87\DataBundle\DependencyInjection\Compiler\ProcessorCompilerPass;
use Nelmio\Alice\Fixtures;
use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class LoadFixturesCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $fixturesDir;

    /**
     * @var string
     */
    private $culture;

    /**
     * @var DatasetInterface[]
     */
    private $datasets = [];

    /**
     * @var Provider[]
     */
    private $providers = [];

    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];

    /**
     * @param EntityManager $entityManager
     * @param string $fixturesDir
     * @param string $culture
     */
    public function __construct(EntityManager $entityManager, $fixturesDir, $culture)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->fixturesDir = $fixturesDir;
        $this->culture = $culture;
    }

    /**
     * @param string $name
     * @param DatasetInterface $dataset
     *
     * @see DatasetCompilerPass
     */
    public function setDataset($name, DatasetInterface $dataset)
    {
        $this->datasets[$name] = $dataset;
    }

    /**
     * @param string $name
     * @param ProcessorInterface $processor
     *
     * @see ProcessorCompilerPass
     */
    public function setProcessor($name, ProcessorInterface $processor)
    {
        $this->processors[$name] = $processor;
    }

    /**
     * @param string $name
     * @param Provider $provider
     *
     * @see ProviderCompilerPass
     */
    public function setProvider($name, Provider $provider)
    {
        $this->providers[$name] = $provider;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('data:fixtures:load')
            ->setDescription('Load a dataset of fixtures.')
            ->addArgument(
                'dataset',
                InputArgument::REQUIRED,
                sprintf('The dataset to load.')
            )
            ->addOption(
                'append',
                null,
                InputOption::VALUE_NONE,
                'Append the data fixtures instead of deleting all data from the database first.'
            )
            ->addOption(
                'purge-with-truncate',
                null,
                InputOption::VALUE_NONE,
                'Purge data by using a database-level TRUNCATE statement'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('append')) {
            $this->purgeDatabase($input, $output);
        }

        $this->loadFixtures($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function purgeDatabase(InputInterface $input, OutputInterface $output)
    {
        $purgeMode = $input->getOption('purge-with-truncate')
            ? ORMPurger::PURGE_MODE_TRUNCATE
            : ORMPurger::PURGE_MODE_DELETE
        ;

        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode($purgeMode);

        $this->log($output, 'purging database');

        $purger->purge();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function loadFixtures(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('dataset');
        $dataset = $this->getDataset($name);

        $this->log($output, sprintf('loading "%s" dataset', $name));

        Fixtures::load(
            $this->getFiles($name, $dataset),
            $this->entityManager,
            $this->getOptions($dataset),
            $this->getProcessors($dataset)
        );
    }

    /**
     * @param string $name
     *
     * @return DatasetInterface
     *
     * @throws InvalidArgumentException
     */
    private function getDataset($name)
    {
        if (!isset($this->datasets[$name])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid dataset "%s". Must be one of %s.',
                $name,
                json_encode(array_keys($this->datasets))
            ));
        }

        return $this->datasets[$name];
    }

    /**
     * @param string $directory
     * @param DatasetInterface $dataset
     *
     * @return string[]
     */
    private function getFiles($directory, DatasetInterface $dataset)
    {
        return array_map(function ($filename) use ($directory) {
            return sprintf('%s/%s/%s', $this->fixturesDir, $directory, $filename);
        }, $dataset->getFiles());
    }

    /**
     * @param DatasetInterface $dataset
     *
     * @return array
     */
    private function getOptions(DatasetInterface $dataset)
    {
        return [
            'locale' => $this->culture,
            'providers' => $this->getProviders($dataset),
            'seed' => null,
        ];
    }

    /**
     * @param DatasetInterface $dataset
     *
     * @return ProcessorInterface[]
     */
    private function getProcessors(DatasetInterface $dataset)
    {
        if (count($dataset->getProcessors()) > 0) {
            return array_intersect_key($dataset->getProcessors(), $this->processors);
        }

        return $this->processors;
    }

    /**
     * @param DatasetInterface $dataset
     *
     * @return Provider[]
     */
    private function getProviders(DatasetInterface $dataset)
    {
        if (count($dataset->getProviders()) > 0) {
            return array_intersect_key($dataset->getProviders(), $this->providers);
        }

        return $this->providers;
    }

    /**
     * @param OutputInterface $output
     * @param string $message
     */
    private function log(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
    }
}
