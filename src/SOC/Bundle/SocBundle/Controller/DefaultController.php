<?php

namespace SOC\Bundle\SocBundle\Controller;

use SOC\Bundle\SocBundle\Entity\Lineup;
use SOC\Bundle\SocBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{

    public function indexAction()
    {
        $scoreRepo = $this->getDoctrine()->getRepository('SOCSocBundle:Score');
        $scores = $scoreRepo->findAll();

        $standings = [];
        $ppd = [];
        $season = [];
        $count = 0;
        foreach($scores as $score) {
            $name = ucfirst($score->getPlayer()->getUsername());
            if(!isset($standings[$name])) {
                $standings[$name] = 0;
            }
            $standings[$name] += $score->getScore();

            if(!isset($ppd[$name])) {
                $ppd[$name] = array();
            }
            $ppd[$name][] = $score->getScore();
            $count = count($ppd[$name]);
        }
        arsort($standings);

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $view = array(
            'user' => $user,
            'standings' => $standings,
            'news' => array(
                'template' => file_get_contents(__DIR__ . '/../Resources/views/Dashboard/timeline-item.html.mustache'),
            ),
            'ppd' => array(
                'labels' => json_encode(range(1, $count)),
                //'data' => json_encode($ppd[ucfirst($user->getUsername())]),
                'data' => json_encode($ppd),
            ),
        );

        return $this->render('SOCSocBundle:Dashboard:index.html.twig', $view);
    }


    public function newsAction()
    {

        $rss_feed = "http://rss.kicker.de/news/bundesliga";
        $rss_data = file_get_contents($rss_feed);

        $dom = new \DOMDocument();
        $dom->loadXML($rss_data);
        $items = $dom->getElementsByTagName('item');

        $data = array();
        foreach ($items as $node) {

            /** @var \DOMDocument $node */
            $item = array (
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
            );
            array_push($data, $item);
        }

        return new JsonResponse($data);
    }


    public function lineupAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $doctrine = $this->getDoctrine();
        $teamRepository = $doctrine->getRepository('SOCSocBundle:Team');
        $playerRepository = $doctrine->getRepository('SOCSocBundle:Player');
        $lineupRepository = $doctrine->getRepository('SOCSocBundle:Lineup');

        $teams = $teamRepository->findAll();

        $lineups = $lineupRepository->findAll();

        $allPlayers = $playerRepository->findBy(array('user' => $user));
        $positionen = array();

        foreach ($allPlayers as $player) {

            $posName = $player->getPosition()->getName();
            if(!isset($positionen[$posName])) {
                $positionen[$posName] = array();
            }
            array_push($positionen[$posName], $player);
        }

        $view = array(
            'user' => $user,
            'lineup' => $lineups[0],
            'teams' => $teams,
            'positionen' => $positionen,
            'template' => file_get_contents(__DIR__ . '/../Resources/views/Default/lineup-item.html.mustache'),
        );

        return $this->render('SOCSocBundle:Default:lineup.html.twig', $view);
    }

    public function teamAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $doctrine = $this->getDoctrine();
        $playerRepository = $doctrine->getRepository('SOCSocBundle:Player');

        $allPlayers = $playerRepository->findBy(array('user' => $user));

        $playersNeeded = $this->getParameter('soc_players_needed');
        $budget = $this->getParameter('soc_budget');

        $moneySpend = 0;
        foreach ($allPlayers as $player) {
            $moneySpend += $player->getEkPreis();
        }

        $moneyLeft = ($budget - $moneySpend >= 0) ? $budget - $moneySpend : 0;
        $playersLeft = $playersNeeded - count($allPlayers);

        $moneyPerPlayer = $moneyLeft / $playersLeft;

        $view = array(
            'user' => $user,
            'players' => $allPlayers,
            'title' => 'Team von ' . ucfirst($user->getUsername()),
            'players_needed' => $playersNeeded,
            'money_spend' => $moneySpend,
            'money_left' => $moneyLeft,
            'money_per_player' => $moneyPerPlayer,
            'budget' => $budget,
        );

        return $this->render('SOCSocBundle:Default:team.html.twig', $view);
    }

    /**
     * @return Response
     */
    public function testAction()
    {

        $doctrine = $this->getDoctrine();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $om = $doctrine->getManager();

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

        $view = array(
            "slug" => '',
            "_format" => '',
        );

        return $this->render('SOCSocBundle:Default:test.html.twig', $view);
    }


    public function templateAction()
    {
    }

}
