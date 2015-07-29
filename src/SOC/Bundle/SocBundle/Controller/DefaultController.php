<?php

namespace SOC\Bundle\SocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{

    public function indexAction()
    {
        $mn = $this->getDoctrine()->getManager();
        $score_repo = $mn->getRepository('SOCSocBundle:Score');
        $scores = $score_repo->findAll();

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


    /**
     * @param string $slug
     * @param string $_format
     * @return Response
     */
    public function testAction($slug, $_format)
    {
        $view = array(
            "slug" => $slug,
            "_format" => $_format,
        );

        return $this->render('SOCSocBundle:Default:test.html.twig', $view);
    }


    public function templateAction()
    {

    }

}
