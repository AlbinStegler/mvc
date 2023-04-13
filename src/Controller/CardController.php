<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Card\DeckOfCards;

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
        $deck = new DeckOfCards;
        $data = ["kort" => $deck->showDeck()];
        return $this->render('card/show-deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "show-shuffled-deck")]
    public function shuffle(): Response
    {
        $deck = new DeckOfCards;
        $deck->shuffleDeck();
        $data = ["kort" => $deck->showDeck()];
        return $this->render('card/show-deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "show-one-card")]
    public function draw(): Response
    {
        $deck = new DeckOfCards;
        $card = $deck->drawCard(); 
        $data = ["kort" => $card->showCard(), "antal" => $deck->getDeckSize()];


        return $this->render('card/show-drawn-card.html.twig', $data);
    }
}
