<?php

namespace SOC\Bundle\SocBundle\Command;

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
class UserImportCommand extends ContainerAwareCommand
{

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('soc:user:import')
            ->setDescription('Imports User from the parameters file with default password in Storage')
            ->setHelp(
                <<<EOT
                The <info>soc:user:import</info> Imports User from the parameters file with default password in Storage

    <info>./app/console soc:user:import</info>

EOT
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine');
        $om = $doctrine->getManager();
        $connection = $doctrine->getConnection();

        $socParameters = $this->getContainer()->getParameter('soc');
        $users = $socParameters['participants'];

        $connection->executeQuery('SET foreign_key_checks = 0');
        $connection->executeQuery('TRUNCATE soc_user');
        $connection->executeQuery('SET foreign_key_checks = 1');

        $this->createUsers($users);
        $this->promoteUsers($users);
        $this->updateUsers($users);
    }

    /**
     * @param array $users
     */
    protected function createUsers($users)
    {

        $command = $this->getApplication()->find('fos:user:create');
        $output = new NullOutput();

        foreach ($users as $user) {

            $usernameCanonical = strtolower($user['name']);

            $arguments = array(
                'command' => 'fos:user:create',
                'username'    => $user['name'],
                'email'    => $usernameCanonical . '@thebickers.de',
                'password'    => '+' . $usernameCanonical . '+',
            );

            $input = new ArrayInput($arguments);
            $returnCode = $command->run($input, $output);
        }

    }

    /**
     * @param array $users
     */
    protected function promoteUsers($users)
    {
        $command = $this->getApplication()->find('fos:user:promote');
        $output = new NullOutput();

        foreach ($users as $user) {

            $usernameCanonical = strtolower($user['name']);

            $arguments = array(
                'command' => 'fos:user:promote',
                'username'    => $user['name'],
                'role'    => 'ROLE_USER',
            );

            $input = new ArrayInput($arguments);
            $returnCode = $command->run($input, $output);

            if($usernameCanonical === 'lutz') {
                $arguments = array(
                    'command' => 'fos:user:promote',
                    'username'    => $user['name'],
                    'role'    => 'ROLE_ADMIN',
                );

                $input = new ArrayInput($arguments);
                $returnCode = $command->run($input, $output);
                $arguments = array(
                    'command' => 'fos:user:promote',
                    'username'    => $user['name'],
                    'role'    => 'ROLE_SONATA_ADMIN',
                );

                $input = new ArrayInput($arguments);
                $returnCode = $command->run($input, $output);
            }

        }

    }

    /**
     * @param $users
     */
    protected function updateUsers($users) {

        $doctrine = $this->getContainer()->get('doctrine');
        $om = $doctrine->getManager();

        $userRepository = $doctrine->getRepository('SOCSocBundle:User');

        $storageUsers = $userRepository->findAll();

        foreach ($users as $user) {

            foreach ($storageUsers as $storageUser) {

                if($storageUser->getUsernameCanonical() !== $user['name']) {
                    continue;
                }
                $storageUser->setAvatar($user['avatar']);
                $om->persist($storageUser);

            }

        }

        $om->flush();

    }

}
