<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Utils\Helpers;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;

class CardController extends AbstractController
{
    #[Route("/card", name: "landing-page")]
    public function start(): Response
    {
        return $this->render('card/card-landingpage.html.twig');
    }

    #[Route("/card/deck", name: "show-deck")]
    public function deck(): Response
    {
        $deck = new DeckOfCards();
        $deck->setupDeck();
        $data = ["kort" => $deck->showDeck()];
        return $this->render('card/show-deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "show-shuffled-deck")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->setupDeck();
        $deck->shuffleDeck();
        $data = ["kort" => $deck->showDeck()];
        $session->remove("usedCards");
        return $this->render('card/show-deck.html.twig', $data);
    }
        
    #[Route("/card/deck/draw", name: "show-one-card")]
    public function draw(SessionInterface $session): Response
    {
        $helper = new Helpers();
        $deck = $helper->createDeckFromSession($session);
        $deck->shuffleDeck();
        $card = $deck->drawCard()->showCard();
        $data = ["kort" => $card, "antal" => $deck->getDeckSize()];
        /**
         * @var array<CardGraphic> $drawn
        */
        $drawn = [$card];
        $helper->saveToSession($session, $drawn);
        return $this->render('card/show-drawn-card.html.twig', $data);
    }

    // private function createDeckFromSession(SessionInterface $session) : DeckOfCards{

    //     $deck = new DeckOfCards;
    //     if ($session->has("usedCards")) {
    //         $used = $session->get("usedCards");
    //         $cardArr = [];
    //         foreach ($used as $card) {
    //             $tCard = new CardGraphic;
    //             $tCard->setValue($card["value"]);
    //             $tCard->setType($card["type"]);
    //             $tCard->setStyle();
    //             $cardArr[] = $tCard;
    //         }
    //         $deck->recreateDeck($cardArr);
    //     } else {
    //         $deck->setupDeck();
    //     }

    //     return $deck;
    // }

    // private function saveToSession(SessionInterface $session, array $thisTurn) {
    //     $drawnCards = $session->get("usedCards");
    //     if ($session->has("usedCards")) {
    //         $allUsed = array_merge($drawnCards, $thisTurn);
    //         $session->set("usedCards", $allUsed);
    //     } else {
    //         $session->set("usedCards", $thisTurn);
    //     }
    // }

    #[Route("/card/deck/draw/{num<\d+>}", name: "show-multiple")]
    public function drawAmount(SessionInterface $session, int $num): Response
    {
        $helper = new Helpers();
        $deck = $helper->createDeckFromSession($session);
        $deck->shuffleDeck();
        $thisTurn = [];
        if ($num > $deck->getDeckSize()) {
            $session->clear();
        }
        if ($num <= $deck->getDeckSize()) {
            for ($i = 0; $i < $num; $i++) {
                $thisTurn[] = $deck->drawCard()->showCard();
            }
        }
        /**
         * @var array<CardGraphic> $thisTurn
        */        
        $helper->saveToSession($session, $thisTurn);

        $data = ["kort" => $thisTurn, "antal" => $deck->getDeckSize()];

        return $this->render('card/show-multiple-drawn-card.html.twig', $data);
    }
}
