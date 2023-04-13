<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Utils\Helpers;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuoteJson extends AbstractController
{
    #[Route("/api", name: "landing-Json")]
    public function jsonStart(): Response
    {


        return $this->render('json/json.html.twig');
        ;
    }

    #[Route("/api/deck", name: "deck-Json", methods: ['GET'])]
    public function getDeck(): Response
    {
        $deck = new DeckOfCards();
        $deck->setupDeck();
        $response = new JsonResponse($deck->showDeck());

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "shuffle-Json", methods: ['POST'])]
    public function shuffleDeck(): Response
    {
        $deck = new DeckOfCards();
        $deck->setupDeck();
        $deck->shuffleDeck();
        $response = new JsonResponse($deck->showDeck());

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }


    #[Route("/api/deck/draw", name: "draw-Json", methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        $deck = Helpers::createDeckFromSession($session);
        $deck->shuffleDeck();
        $drawn = $deck->drawCard()->showCard();
        $data = ["draget-kort" => $drawn,
                "antal-kvar" => $deck->getDeckSize()];
        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        Helpers::saveToSession($session, [$drawn]);
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "draw-mul-Json", methods: ['POST'])]
    public function drawMultiple(int $num, SessionInterface $session): Response
    {
        $deck = Helpers::createDeckFromSession($session);
        $deck->shuffleDeck();
        $thisTurn = [];
        dump($deck);
        if ($num > $deck->getDeckSize()) {
            $session->clear();
        } else {
            for ($i = 0; $i < $num; $i++) {
                $thisTurn[] = $deck->drawCard()->showCard();
            }
        }

        Helpers::saveToSession($session, $thisTurn);
        $data = ["kort" => $thisTurn, "antal" => $deck->getDeckSize()];

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote", name: "quote")]
    public function jsonNumber(): Response
    {
        $date = date("Y-m-d");
        $time = date("h:i:sa");
        $number = random_int(0, 6);
        $quotes = [
            "Remember that what you see is not all there is",
            'I can fix the world but they wont give me the source code',
            "Programming is 10% writing code and 90% understanding why it's not working",
            "Enjoy life. There's plenty of time to be dead.",
            "Old ways wont open new doors",
            "If it was easy, everyone would do it",
            "Good decisions come from experience. Experience comes from making bad decisions",
        ];
        $data = [ "quote" => $quotes[$number],
                "date" => $date,
                "time" => $time
            ];
        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
