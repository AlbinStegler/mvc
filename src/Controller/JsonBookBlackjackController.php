<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Card\BlackjackHand;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JsonBookBlackjackController extends AbstractController
{
    #[Route("/api/game", name: "game-stats", methods: ['POST'])]
    public function game(SessionInterface $session): Response
    {
        //Hämtar kort från session
        $bank = $this->getBankHand($session);
        $player = $this->getPlayerHand($session);
        $deck = $this->createDeckFromSession($session);
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
    // Post route for showing one book redirects to book_by_id_api
    #[Route("/api/showbook", name: "showbook", methods: ["POST"])]
    public function showOne(Request $request): Response
    {
        $bookId = ($request->request->get("id"));
        return $this->redirectToRoute('book_by_id_api', ["bookid" => (int)$bookId]);
    }


    // Functions

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

    private function getBankHand(SessionInterface $session): BlackjackHand
    {
        $hand = new BlackjackHand();
        if ($session->has("bankHand")) {
            $used = $session->get("bankHand");
            if (is_array($used)) {
                foreach ($used as $card) {
                    if (is_array($card) && array_key_exists('value', $card) && array_key_exists('type', $card)) {
                        $tCard = new CardGraphic();
                        $tCard->setValue($card["value"]);
                        $tCard->setType($card["type"]);
                        $tCard->setStyle();
                        $hand->add($tCard);
                    }
                }
            }
        }
        return $hand;
    }

    private function getPlayerHand(SessionInterface $session): BlackjackHand
    {
        $hand = new BlackjackHand();
        if ($session->has("blackjackHand")) {
            $used = $session->get("blackjackHand");
            if (is_array($used)) {
                foreach ($used as $card) {
                    if (is_array($card) && array_key_exists('value', $card) && array_key_exists('type', $card)) {
                        $tCard = new CardGraphic();
                        $tCard->setValue($card["value"]);
                        $tCard->setType($card["type"]);
                        $tCard->setStyle();
                        $hand->add($tCard);
                    }
                }
            }
        }
        return $hand;
    }
}
