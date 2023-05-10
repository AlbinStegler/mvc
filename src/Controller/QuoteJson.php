<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;

use App\Entity\Book;
use App\Repository\BookRepository;
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
    public function jsonStart(BookRepository $bookRepository): Response
    {
        $allbooks = [];
        $allbooks["books"] = $bookRepository->findAll();

        return $this->render('json/json.html.twig', $allbooks);;
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
        $helper = new Helpers();
        $deck = $helper->createDeckFromSession($session);
        $deck->shuffleDeck();
        $drawn = $deck->drawCard()->showCard();
        $data = [
            "draget-kort" => $drawn,
            "antal-kvar" => $deck->getDeckSize()
        ];
        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        /**
         * @var CardGraphic $drawn
         */
        $helper->saveToSession($session, [$drawn]);
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "draw-mul-Json", methods: ['POST'])]
    public function drawMultiple(int $num, SessionInterface $session): Response
    {
        $helper = new Helpers();
        $deck = $helper->createDeckFromSession($session);
        $deck->shuffleDeck();
        $thisTurn = [];
        if ($num > $deck->getDeckSize()) {
            $session->clear();
        }
        if ($num < $deck->getDeckSize()) {
            for ($i = 0; $i < $num; $i++) {
                $thisTurn[] = $deck->drawCard()->showCard();
            }
        }
        /**
         * @var array<CardGraphic> $thisTurn
         */
        $helper->saveToSession($session, $thisTurn);
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
        $data = [
            "quote" => $quotes[$number],
            "date" => $date,
            "time" => $time
        ];
        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/game", name: "game-stats", methods: ['POST'])]
    public function game(SessionInterface $session): Response
    {
        $helper = new Helpers();
        //Hämtar kort från session
        $bank = $helper->getBankHand($session);
        $player = $helper->getPlayerHand($session);
        $deck = $helper->createDeckFromSession($session);
        $deck->shuffleDeck();

        dump($bank);
        dump($player);

        $data = ["bank" => $bank->getCards(), "spelare" => $player->getCards(), "deck" => $deck->showDeck()];

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/books", name: "showallApi")]
    public function showAllBooks(BookRepository $bookRepository): Response
    {
        $all = $bookRepository->findAll();

        $response = $this->json($all);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    // Route to show specific book
    #[Route('/api/show/{bookid}', name: 'book_by_id_api')]
    public function showProductById(
        BookRepository $bookRepository,
        int $bookid
    ): Response {
        $book = $bookRepository
            ->find($bookid);

        $response = $this->json($book);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/showbook", name: "showbook", methods: ["POST"])]
    public function showOne(Request $request): Response
    {
        $bookId = ($request->request->get("id"));
        return $this->redirectToRoute('book_by_id_api', ["bookid" => (int)$bookId]);
    }
}