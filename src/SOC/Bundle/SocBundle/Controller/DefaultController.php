<?php

namespace SOC\Bundle\SocBundle\Controller;

use SOC\Bundle\SocBundle\Entity\Lineup;
use SOC\Bundle\SocBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;

class DefaultController extends Controller
{

    /**
     * @return Response
     */
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
            'title' => 'Dashboard',
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

    /**
     * @return JsonResponse
     */
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

    /**
     * @return Response
     */
    public function lineupAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $doctrine = $this->getDoctrine();
        $teamRepository = $doctrine->getRepository('SOCSocBundle:Team');
        $playerRepository = $doctrine->getRepository('SOCSocBundle:Player');
        $lineupRepository = $doctrine->getRepository('SOCSocBundle:Lineup');
        $positionenRepository = $doctrine->getRepository('SOCSocBundle:Position');

        $teams = $teamRepository->findAll();
        $lineups = $lineupRepository->findAll();
        $positionen = $positionenRepository->findAll();


        $allPlayers = $playerRepository->findBy(array('user' => $user));
        $positionenGroups = array();

        foreach ($allPlayers as $player) {

            $posName = $player->getPosition()->getName();
            if(!isset($positionenGroups[$posName])) {
                $positionenGroups[$posName] = array();
            }
            array_push($positionenGroups[$posName], $player);
        }

        $view = array(
            'title' => 'Aufstellung',
            'user' => $user,
            'lineup' => array(),
            'teams' => $teams,
            'positionen' => $positionen,
            'positionenGroup' => $positionenGroups,
            'template' => file_get_contents(__DIR__ . '/../Resources/views/Default/lineup-item.html.mustache'),
        );

        return $this->render('SOCSocBundle:Default:lineup.html.twig', $view);
    }

    /**
     * @Pdf(stylesheet="SOCSocBundle:Default:stylesheet.pdf.twig")
     *
     * @param int $matchday
     * @return Response
     */
    public function lineupPrintAction($matchday)
    {
        $doctrine = $this->getDoctrine();
        $userRepository = $doctrine->getRepository('SOCSocBundle:User');
        $playerRepository = $doctrine->getRepository('SOCSocBundle:Player');
        $lineupRepository = $doctrine->getRepository('SOCSocBundle:Lineup');

        $users = $userRepository->findAll();

        $lineups = array();

        foreach ($users as $user) {
            $lineups[$user->getUsername()] = array();
            $lineup = $lineupRepository->findOneBy(array('user' => $user, 'matchday' => $matchday));

            $lineups[$user->getUsername()]['players'] = array();

            if($lineup === null) {
                continue;
            }

            $lineups[$user->getUsername()]['lineup'] = $lineup;
            $data = $lineup->getData();

            foreach ($data["lineup"] as $position) {
                foreach ($position as $name => $playerId ) {
                    $player = $playerRepository->find($playerId);
                    array_push($lineups[$user->getUsername()]['players'], $player);
                }
            }

        }

        $view = array(
            'title' => 'Aufstellung ausdrucken',
            'lineups' => $lineups,
            'matchday' => $matchday,
        );

        return $this->render('SOCSocBundle:Default:lineup-print.pdf.twig', $view);
    }

    /**
     * @return Response
     */
    public function scoreAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $doctrine = $this->getDoctrine();
        $scoreRepository = $doctrine->getRepository('SOCSocBundle:Score');

        $scores = array();

        $view = array(
            'user' => $user,
            'scores' => $scores,
            'title' => 'Punkte',
        );

        return $this->render('SOCSocBundle:Default:score.html.twig', $view);
    }

    /**
     * @return Response
     */
    public function teamAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $doctrine = $this->getDoctrine();
        $playerRepository = $doctrine->getRepository('SOCSocBundle:Player');

        $allPlayers = $playerRepository->findBy(array('user' => $user));

        $socParameter = $this->getParameter('soc');
        $playersNeeded = $socParameter['players_needed'];
        $budget = $socParameter['budget'];

        $moneySpend = 0;
        foreach ($allPlayers as $player) {
            $moneySpend += $player->getEkPreis();
        }

        $moneyLeft = ($budget - $moneySpend >= 0) ? $budget - $moneySpend : 0;
        $playersLeft = ($playersNeeded - count($allPlayers) <= 0) ? 1 : $playersNeeded - count($allPlayers);

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
     * @Pdf(stylesheet="SOCSocBundle:Default:stylesheet.pdf.twig")
     * @return Response
     */
    public function testAction()
    {

        $doctrine = $this->getDoctrine();
        $userRepository = $doctrine->getRepository('SOCSocBundle:User');
        $playerRepository = $doctrine->getRepository('SOCSocBundle:Player');
        $lineupRepository = $doctrine->getRepository('SOCSocBundle:Lineup');

        $users = $userRepository->findAll();

        $lineups = array();
        $matchday = 1;

        foreach ($users as $user) {
            $lineups[$user->getUsername()] = array();
            $lineup = $lineupRepository->findOneBy(array('user' => $user, 'matchday' => $matchday));

            $lineups[$user->getUsername()]['players'] = array();

            if($lineup === null) {
                continue;
            }

            $lineups[$user->getUsername()]['lineup'] = $lineup;
            $data = $lineup->getData();

            foreach ($data["lineup"] as $position) {
                foreach ($position as $name => $playerId ) {
                    $player = $playerRepository->find($playerId);
                    array_push($lineups[$user->getUsername()]['players'], $player);
                }
            }

        }

        $view = array(
            'title' => 'Aufstellung ausdrucken',
            'lineups' => $lineups,
            'matchday' => $matchday,
        );

        return $this->render('SOCSocBundle:Default:lineup-print.pdf.twig', $view);
    }


}
