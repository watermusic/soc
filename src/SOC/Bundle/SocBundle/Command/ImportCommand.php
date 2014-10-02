<?php

namespace SOC\Bundle\SocBundle\Command;

use SOC\Bundle\SocBundle\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\CssSelector\CssSelector;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Command that lists the file keys of a filesystem
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class ImportCommand extends ContainerAwareCommand
{

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('soc:player:import:ext')
            ->setDescription('Imports Player from the Kicker.de Website in Storage')
            ->setHelp(
                <<<EOT
                The <info>soc:player:import:ext</info> Imports Player from the Kicker.de Website in Storage

    <info>./app/console soc:player:import:ext</info>

    Options:
    - clear
EOT
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $path = $this->getContainer()->get('kernel')->getRootDir() . '/../web';
        $uri = $path . '/assets/player.html';

        $mapping = array(
            "ABW" => "Abwehr",
            "TOR" => "Torwart",
            "MIT" => "Mittelfeld",
            "STU" => "Sturm",
        );

        $decorator = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <div class="list">%s</div>
    </body>
</html>
HTML;

        $doctrine = $this->getContainer()->get('doctrine');
        $om = $doctrine->getManager();
        $connection = $doctrine->getConnection();
        $connection->executeQuery('TRUNCATE TABLE player');

        $body = file_get_contents($uri);
        $html = sprintf($decorator, $body);

        $crawler = new Crawler($html);

        $players = $crawler->filter("tbody tr");

        $progress = new ProgressBar($output, count($players));
        $progress->start();

        foreach($players as $player) {

            $player = new Crawler($player);

            $result = array();
            $result["name"] = $player->filter("td:nth-child(2)")->extract("_text")[0];
            $result["verein"] = $player->filter("td:nth-child(3)")->extract("_text")[0];
            $result["position"] = $mapping[$player->filter("td:nth-child(4)")->extract("_text")[0]];
            $result["note"] = str_replace(",", "", $player->filter("td:nth-child(9)")->extract("_text")[0]) / 10.0;
            $result["vk_preis"] = str_replace(",", "", $player->filter("td:nth-child(5)")->extract("_text")[0]) * 100000;
            $result["punkte"] = filter_var($player->filter("td:nth-child(8)")->extract("_text")[0], FILTER_SANITIZE_NUMBER_INT) * 1.0;


            $entity = new Player();
            $entity
                ->setName(utf8_decode($result["name"]))
                ->setVerein(utf8_decode($result["verein"]))
                ->setNote($result["note"])
                ->setVkPreis($result["vk_preis"])
                ->setEkPreis(0.0)
                ->setKaeufer("")
                ->setPosition($result["position"])
                ->setPunkte($result["punkte"]);

            $om->persist($entity);
            $progress->advance();
        }

        $om->flush();
        $progress->finish();

    }


}
