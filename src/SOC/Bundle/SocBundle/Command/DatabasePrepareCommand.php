<?php

namespace SOC\Bundle\SocBundle\Command;

use SOC\Bundle\SocBundle\Entity\Lineup;
use SOC\Bundle\SocBundle\Entity\Player;
use SOC\Bundle\SocBundle\Entity\Position;
use SOC\Bundle\SocBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\CssSelector\CssSelector;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Command that lists the file keys of a filesystem
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class DatabasePrepareCommand extends ContainerAwareCommand
{

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('soc:database:prepare')
            ->setDescription('Fills the storage with standard data. All data will be erased.')
            ->addOption('only-clear', null, InputOption::VALUE_NONE, 'Only clear the database tables')
            ->addOption('with-fake-team', null, InputOption::VALUE_NONE, 'With Fake Team')
            ->addOption('with-lineup', null, InputOption::VALUE_NONE, 'With Fake Lineup')
            ->setHelp(
                <<<EOT
                The <info>soc:database:prepare</info> Fills the storage with standard data. All data will be erased.

    <info>./app/console soc:database:prepare</info>

EOT
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $onlyClear = $input->getOption('only-clear');
        $withFakeTeam = $input->getOption('with-fake-team');
        $withLineup = $input->getOption('with-lineup');

        $this->clear();

        if($onlyClear === false) {
            $this->fill();
        }

        if($withFakeTeam === true) {
            $this->fakeTeam();
        }

        if($withLineup === true) {
            $this->lineUp();
        }

    }


    protected function fill()
    {
        $commands = array(
            'soc:player:import',
            'soc:user:import',
        );

        $output = new NullOutput();

        foreach ($commands as $commandName) {
            $command = $this->getApplication()->find($commandName);
            $arguments = array(
                'command' => $commandName,
            );

            $input = new ArrayInput($arguments);
            $returnCode = $command->run($input, $output);
        }
    }


    protected function clear()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $om = $doctrine->getManager();
        $connection = $doctrine->getConnection();

        $connection->executeQuery('SET foreign_key_checks = 0');
        $connection->executeQuery('TRUNCATE soc_lineup');
        $connection->executeQuery('TRUNCATE soc_player');
        $connection->executeQuery('TRUNCATE soc_position');
        $connection->executeQuery('TRUNCATE soc_score');
        $connection->executeQuery('TRUNCATE soc_team');
        $connection->executeQuery('TRUNCATE soc_user');
        $connection->executeQuery('SET foreign_key_checks = 1');
    }

    private function fakeTeam()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $om = $doctrine->getManager();

        $playerRepository = $doctrine->getRepository('SOCSocBundle:Player');
        $userRepository = $doctrine->getRepository('SOCSocBundle:User');

        $players = array(
            'lutz' => array(
                '1',
                '57',
                '58',
                '93',
                '107',
                '124',
                '171',
                '204',
                '228',
                '253',
                '281',
                '292',
                '297',
                '374',
                '410',
                '424',
                '499',
                '504',
            ),
            'christian' => array(
                '453',
                '496',
                '484',
                '458',
                '245',
                '389',
                '315',
                '309',
                '283',
                '293',
                '277',
                '261',
                '267',
                '279',
                '59',
                '60',
                '73',
                '64',
                '67',
                '66',
                '6',
                '9',
            )
        );

        foreach ($players as $name => $ids) {

            foreach ($ids as $id) {
                $player = $playerRepository->find($id);

                $user = $userRepository->findOneBy(array("username" => $name));

                $preis = 500000 * rand(3, 14);

                $player->setEkPreis($preis);
                $player->setUser($user);
                $om->persist($player);

            }

        }

        $om->flush();

    }

    private function lineUp()
    {

        $doctrine = $this->getContainer()->get('doctrine');
        $om = $doctrine->getManager();

        $userRepository = $doctrine->getRepository('SOCSocBundle:User');
        $user = $userRepository->find(1);

        $data = array(
            "lineup" => array(1, 57, 58, 93, 228, 253, 281, 292, 297, 499, 504),
        );

        $lineup = new Lineup();
        $lineup
            ->setUser($user)
            ->setMatchday(1)
            ->setData($data)
        ;

        $om->persist($lineup);
        $om->flush();

    }

}
