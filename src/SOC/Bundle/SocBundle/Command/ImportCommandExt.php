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
class ImportCommandExt extends ContainerAwareCommand
{


    protected $sites = array(
        'torwart' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=1&verein=0&sort=1&list=0&playerSearchStr=',
        'abwehr' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=2&verein=0&sort=1&list=0&playerSearchStr=',
        'mittelfeld' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=3&verein=0&sort=1&list=0&playerSearchStr=',
        'sturm' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=4&verein=0&sort=1&list=0&playerSearchStr=',
    );

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('soc:player:import')
            ->setDescription('Imports Player from the Kicker.de Website in Storage')
            ->setHelp(
                <<<EOT
                The <info>soc:player:import</info> Imports Player from the Kicker.de Website in Storage

    <info>./app/console soc:player:import</info>

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
        $connection->executeQuery('TRUNCATE TABLE Player');

        foreach($this->sites as $position => $uri) {

            $body = file_get_contents($uri);
            $html = sprintf($decorator, $body);

            $crawler = new Crawler($html);

            $players = $crawler->filter("div.pli");

            foreach($players as $player) {

                $player = new Crawler($player);

                $result = array();
                $result["name"] = $player->filter("a")->extract("_text")[0];
                $result["verein"] = $player->filter("span.vrn")->extract("_text")[0];
                $result["note"] = 3.5;
                $result["vk_preis"] = str_replace(",", "", $player->filter("span.wert b")->extract("_text")[0]) * 100000;
                $result["punkte"] = filter_var($player->filter("span.pkt")->extract("_text")[0], FILTER_SANITIZE_NUMBER_INT);

                $entity = new Player();
                $entity
                    ->setName(utf8_decode($result["name"]))
                    ->setVerein(utf8_decode($result["verein"]))
                    ->setNote($result["note"])
                    ->setVkPreis($result["vk_preis"])
                    ->setEkPreis(0.0)
                    ->setKaufer("")
                    ->setPosition(ucfirst($position))
                    ->setPunkte($result["punkte"]);

                $om->persist($entity);
            }

            $om->flush();

        }

    }


}
