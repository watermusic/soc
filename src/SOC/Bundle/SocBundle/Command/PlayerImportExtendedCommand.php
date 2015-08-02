<?php

namespace SOC\Bundle\SocBundle\Command;

use SOC\Bundle\SocBundle\Entity\Player;
use SOC\Bundle\SocBundle\Entity\Position;
use SOC\Bundle\SocBundle\Entity\Team;
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
class PlayerImportExtendedCommand extends ContainerAwareCommand
{

    protected $kickerSite = "http://manager.kicker.de/classic/bundesliga/meinteam/meinkader";

    protected $sites = array(
        'Torwart' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=1&verein=0&sort=1&list=0&playerSearchStr=',
        'Abwehr' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=2&verein=0&sort=1&list=0&playerSearchStr=',
        'Mittelfeld' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=3&verein=0&sort=1&list=0&playerSearchStr=',
        'Sturm' => 'http://manager.kicker.de/classic/bundesliga/meinteam/ajax.ashx?ajaxtype=spielerauswahl&position=4&verein=0&sort=1&list=0&playerSearchStr=',
    );

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('soc:player:import2')
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

        $doctrine = $this->getContainer()->get('doctrine');
        $om = $doctrine->getManager();
        $connection = $doctrine->getConnection();

        $connection->executeQuery('SET foreign_key_checks = 0');
        $connection->executeQuery('TRUNCATE soc_player');
        $connection->executeQuery('TRUNCATE soc_team');
        $connection->executeQuery('TRUNCATE soc_position');
        $connection->executeQuery('SET foreign_key_checks = 1');

        $this->updateTeams();
        $this->updatePositions();
        $this->updatePlayers();

    }

    private function getPlayers($position)
    {

        $decorator = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <div class="list">%s</div>
    </body>
</html>
HTML;

        $playerResult = array();

        $uri = $this->sites[$position];
        $body = file_get_contents($uri);
        $html = sprintf($decorator, $body);

        $crawler = new Crawler($html);

        $players = $crawler->filter("div.pli");

        foreach($players as $player) {

            $player = new Crawler($player);

            $result = array();
            $result["name"] = $player->filter("a")->extract("_text")[0];
            $result["verein"] = utf8_decode($player->filter("span.vrn")->extract("_text")[0]);
            $result["note"] = 3.5;
            $result["vk_preis"] = str_replace(",", "", $player->filter("span.wert b")->extract("_text")[0]) * 100000;
            $result["punkte"] = (float)$player->filter("span.pkt b")->extract("_text")[0];
            $result["position"] = $position;

            $result["verein"] = str_replace(
                array("VfB", "HSV", "E. Frankfurt"),
                array("Stuttgart", "Hamburg", "Frankfurt"),
                $result["verein"]
            );

            array_push($playerResult, $result);
        }

        return $playerResult;
    }


    private function updatePlayers()
    {
        $doctrine = $this->getContainer()->get("doctrine");
        $om = $doctrine->getManager();

        $teamRepo = $doctrine->getRepository('SOCSocBundle:Team');
        $teams = $teamRepo->findAll();
        $positionRepo = $doctrine->getRepository('SOCSocBundle:Position');
        $positions = $positionRepo->findAll();

        foreach($this->sites as $position => $url) {

            $players = $this->getPlayers($position);

            foreach($players as $player) {

                $t = null;
                foreach($teams as $team) {
                    if($team->getName() === $player["verein"]) {
                        $t = $team;
                    }
                }

                $p = null;
                foreach($positions as $position) {
                    if($position->getName() === $player["position"]) {
                        $p = $position;
                    }
                }

                $entity = new Player();
                $entity
                    ->setName(utf8_decode($player["name"]))
                    ->setTeam($t)
                    ->setNote($player["note"])
                    ->setVkPreis($player["vk_preis"])
                    ->setEkPreis(0.0)
                    ->setKaeufer("")
                    ->setPosition($p)
                    ->setPunkte($player["punkte"]);

                $om->persist($entity);
            }
            $om->flush();

        }
    }

    private function updateTeams()
    {
        $doctrine = $this->getContainer()->get("doctrine");
        $om = $doctrine->getManager();

        reset($this->sites);
        $position = key($this->sites);

        $players = $this->getPlayers($position);
        $vereine = array();
        foreach($players as $player) {
            if(!in_array($player["verein"], $vereine)) {
                array_push($vereine, $player["verein"]);
            }
        }

        asort($vereine);

        foreach($vereine as $verein) {
            $team = new Team();
            $team->setName($verein);
            $om->persist($team);
        }
        $om->flush();

    }

    private function updatePositions()
    {
        $doctrine = $this->getContainer()->get("doctrine");
        $om = $doctrine->getManager();

        $positions = array_keys($this->sites);

        foreach($positions as $positionName) {
            $position = new Position();
            $position->setName($positionName);
            $om->persist($position);
        }
        $om->flush();
    }

}
