<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\BlackjackHand;
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

        $deck = $this->createDeckFromSession($session);
        $deck->shuffleDeck();
        $card = $deck->drawCard()->showCard();
        $data = ["kort" => $card, "antal" => $deck->getDeckSize()];
        /**
         * @var array<CardGraphic> $drawn
         */
        $drawn = [$card];
        $this->saveToSession($session, $drawn);
        return $this->render('card/show-drawn-card.html.twig', $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "show-multiple")]
    public function drawAmount(SessionInterface $session, int $num): Response
    {
        $deck = $this->createDeckFromSession($session);
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
        $this->saveToSession($session, $thisTurn);

        $data = ["kort" => $thisTurn, "antal" => $deck->getDeckSize()];

        return $this->render('card/show-multiple-drawn-card.html.twig', $data);
    }

    //Functions

    private function createDeckFromSession(SessionInterface $session): DeckOfCards
    {
        $deck = new DeckOfCards();
        if ($session->has("usedCards")) {
            $used = $session->get("usedCards");
            $cardArr = [];
            if (is_array($used)) {
                foreach ($used as $card) {
                    $tCard = new CardGraphic();
                    $tCard->setValue($card["value"]);
                    $tCard->setType($card["type"]);
                    $tCard->setStyle();
                    $cardArr[] = $tCard;
                }
            }
            $deck->recreateDeck($cardArr);
            return $deck;
        }
        $deck->setupDeck();

        return $deck;
    }
    /**
     * @param array<CardGraphic> $thisTurn
     */
    private function saveToSession(SessionInterface $session, array $thisTurn): string
    {
        $drawnCards = $session->get("usedCards");
        $hand = new BlackjackHand();
        if ($session->has("usedCards")) {
            /**
             * @var array<CardGraphic> $drawnCards
             */
            $hand->setHand($drawnCards);
            $allUsed = $hand->mergeCards($thisTurn);
            $session->set("usedCards", $allUsed);
            return "session exists";
        }
        $session->set("usedCards", $thisTurn);
        return "session created";
    }
}
