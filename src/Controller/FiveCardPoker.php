<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Project\FiveHandRules;
use App\Project\ProjectDeck;
use App\Project\ProjectHand;
use App\Project\VisualCard;

class FiveCardPoker extends AbstractController
{

    private function getDeck(SessionInterface $session): ProjectDeck
    {
        if ($session->has("deck")) {
            $deck = $session->get("deck");
            return $deck;
        }

        $deck = new ProjectDeck;
        $deck->setupDeck();
        $deck->shuffleDeck();
        return $deck;
    }

    private function getHand(SessionInterface $session, string $sessionName): ProjectHand
    {
        if ($session->has($sessionName)) {
            return $session->get($sessionName);
        }

        $deck = $this->getDeck($session);
        $hand = new ProjectHand;
        $count = 0;
        while ($count < 5) {
            $hand->addToHand($deck->drawCard());
            $count++;
        }
        return $hand;
    }


    #[Route("/proj", name: "proj-start")]
    public function projLanding(): Response
    {
        return $this->render('proj/project-landing.html.twig');
    }

    #[Route("/proj/poker", name: "poker-game")]
    public function pokerGame(SessionInterface $session): Response
    {
        if (!$session->has("round")) {
            $session->set("round", 1);
        }
        $runda = $session->get("round");

        //Hämta kortlek
        $deck = $this->getDeck($session);

        $player = $this->getHand($session, "playerHand");
        $pc = $this->getHand($session, "pc");

        //Om spelet är slut

        $playerRules = new FiveHandRules($player);
        $pcRules = new FiveHandRules($pc);
        if ($runda == 4) {
        }

        $data = [
            "player" => $player->getCards(), "pc" => $pc->getCards(),
            "deck" => $deck, "runda" => $runda,
            "playerRule" => $playerRules,
            "pcRule" => $pcRules
        ];

        $session->set("deck", $deck);
        $session->set("playerHand", $player);
        $session->set("pc", $pc);

        return $this->render('proj/poker-game.html.twig', $data);
    }

    //Skapa get post route där jag tar bort kortet från handen 

    #[Route("/proj/reset", name: "reset-deck")]
    public function resetDeck(SessionInterface $session): Response
    {
        $session->remove("deck");
        $session->remove("playerHand");
        $session->remove("pc");
        $session->remove("round");

        return $this->redirectToRoute('poker-game');
    }

    #[Route("/proj/roundHandler", name: "round-handler", methods: ["POST"])]
    public function roundHandler(SessionInterface $session, Request $request): Response
    {
        $cards = $request->get('cards');
        $playerHand = $this->getHand($session, "playerHand");
        $deck = $this->getDeck($session);

        //Skapar korten som skickats via session och tar bort dem från handen
        if ($cards) {
            foreach ($cards as $card) {
                $c = new VisualCard($card);
                dump($c);
                $playerHand->removeFromHand($c);
                $playerHand->addToHand($deck->drawCard());
            }
        }


        //Datorns korts byts ut

        $pc = $this->getHand($session, "pc");

        foreach ($pc->getCards() as $p) {
            $rand = rand(0, 1);
            if ($rand == 0) {
                $pc->removeFromHand($p);
                $pc->addToHand($deck->drawCard());
            }
        }

        $session->set("pc", $pc);
        $session->set("playerHand", $playerHand);
        $session->set("deck", $deck);

        $runda = $session->get("round");
        $session->set("round", (int)$runda + 1);

        return $this->redirectToRoute('poker-game');
    }
}