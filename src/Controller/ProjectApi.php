<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Project\FiveHandRules;
use App\Project\ProjectDeck;
use App\Project\ProjectHand;
use App\Project\VisualCard;

class ProjectApi extends AbstractController
{
    #[Route("/project/api", name: "proj-api")]
    public function projectApiStart(): Response
    {
        return $this->render('proj/projJson.html.twig');
    }

    #[Route("/project/api/showPlayerhand", name: "proj-show-playerhand", methods: ["POST"])]
    public function showPlayerHand(SessionInterface $session): Response
    {
        $hand = $session->get("playerHand");
        $response = new JsonResponse($hand->getHand());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/project/api/showPcHand", name: "proj-show-pchand", methods: ["POST"])]
    public function showPcHand(SessionInterface $session): Response
    {
        $hand = $session->get("pc");
        $response = new JsonResponse($hand->getHand());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/project/api/showDeck", name: "proj-show-deck", methods: ["POST"])]
    public function showDeck(SessionInterface $session): Response
    {
        $hand = $session->get("deck");
        $response = new JsonResponse($hand->getAsArray());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/project/api/showShuffledDeck", name: "proj-show-deck-shuffled", methods: ["POST"])]
    public function showShuffledDeck(SessionInterface $session): Response
    {
        $hand = $session->get("deck");
        $hand->shuffleDeck();
        $session->set("deck", $hand);
        $response = new JsonResponse($hand->getAsArray());
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/project/api/showRule", name: "proj-show-rule", methods: ["POST"])]
    public function showCurrentRules(SessionInterface $session): Response
    {
        $hand = $session->get("playerHand");
        $pc = $session->get("pc");

        $rule = new FiveHandRules($hand);
        $pcRule = new FiveHandRules($pc);

        $data = ["Spelare" => $rule->getRule(), "Pc" => $pcRule->getRule()];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
