<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Card\DeckOfCards;
use App\Card\BlackjackHand;
use App\Card\CardGraphic;
use App\Game\Player;
use App\Game\Bank;


class CardGameController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function info(): Response
    {
        return $this->render('cardgame/game.html.twig');
    }

    #[Route("/game/doc", name: "game-docs")]
    public function docs(): Response
    {
        return $this->render('cardgame/game-docs.html.twig');
    }
    #[Route("/game/start", name: "start")]
    public function start(SessionInterface $session): Response
    {
        //Korten hämtas från session
        $hand = $this->getPlayerHand($session);
        $bankHand = $this->getBankHand($session);
        $player = new Player($hand);

        $playerLost = false;
        $stop = false;

        //Spelare får val att dra kort om korten är under 21
        $playerRoll = $player->canIDraw($hand->getSum());
        if (!$playerRoll && $hand->getSum() > 21) {
            $playerLost = true;
        }
        $data = [];
        //Vinnare räknas ut
        if ($session->has("stop") || $hand->getSum() == 21) {
            $stop = true;
            $session->set("stop", true);
            $playerWon = $player->playerWon($bankHand);
            $data = [
                "cards" => $hand->getCards(),
                "value" => $hand->getSum(),
                "player" => $playerRoll,
                "lost" => $playerLost,
                "playerWon" => $playerWon,
                "bankHand" => $bankHand->getCards(),
                "bankSum" => $bankHand->getSum(),
                "stop" => $stop
            ];
        }
        if (!$session->has("stop")) {
            $data = [
                "cards" => $hand->getCards(),
                "value" => $hand->getSum(),
                "player" => $playerRoll,
                "lost" => $playerLost,
                "bankHand" => $bankHand->getCards(),
                "bankSum" => $bankHand->getSum(),
                "stop" => $stop
            ];
        }



        return $this->render('cardgame/start.html.twig', $data);
    }

    #[Route("/game/start/player-draw", name: "player-draw", methods: ['POST'])]
    public function drawPlayer(SessionInterface $session): Response
    {

        //Hämtar från Session
        $deck = $this->createBlackjackDeckFromSession($session);
        $hand = $this->getPlayerHand($session);
        $deck->shuffleDeck();
        $drawn = $deck->drawCard();
        $hand->add($drawn);

        //Sparar draget kort till session och redirectar till startsida
        /**
         * @var array<CardGraphic> $card2Show
         */
        $card2Show = [$drawn->showCard()];
        $this->savePlayerHand($session, $card2Show);
        $this->saveBlackjackDeckToSession($session, $card2Show);
        return $this->redirectToRoute('start');
    }

    #[Route("/game/start/reset", name: "reset", methods: ['POST'])]
    public function reset(SessionInterface $session): Response
    {
        //Tömmer session
        $session->clear();
        return $this->redirectToRoute('start');
    }

    #[Route("/game/start/bank", name: "bank-draw", methods: ['POST'])]
    public function bankDraw(SessionInterface $session): Response
    {
        //OM SPELARE STANNAR
        $deck = $this->createBlackjackDeckFromSession($session);
        $hand = $this->getBankHand($session);
        $bank = new Bank($hand);
        $deck->shuffleDeck();
        $session->set("stop", true);

        $allDrawn = [];
        //Banken drar kort sparas till session och laddas in.
        $drawn = new CardGraphic();
        while ($bank->canIDraw($hand->getSum())) {
            $drawn = $deck->drawCard();
            $hand->add($drawn);
            $allDrawn[] = $drawn->showCard();
        }

        //Sparar till session
        /**
         * @var array<CardGraphic> $allDrawn
         */
        $this->saveBankHand($session, $allDrawn);
        /**
         * @var array<CardGraphic> $card2Show
         */
        $card2Show = [$drawn->showCard()];
        $this->saveBlackjackDeckToSession($session, $card2Show);
        return $this->redirectToRoute('start');
    }

    // Functions

    private function createBlackjackDeckFromSession(SessionInterface $session): DeckOfCards
    {
        $deck = new DeckOfCards();
        if ($session->has("blackjackDeck")) {
            $used = $session->get("blackjackDeck");
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
    private function saveBlackjackDeckToSession(SessionInterface $session, array $thisTurn): string
    {
        $drawnCards = $session->get("blackjackDeck");
        $hand = new BlackjackHand();
        if ($session->has("blackjackDeck")) {
            /**
             * @var array<CardGraphic> $drawnCards
             */
            $hand->setHand($drawnCards);
            $allUsed = $hand->mergeCards($thisTurn);
            $session->set("blackjackDeck", $allUsed);
            return "session exists";
        }
        $session->set("blackjackDeck", $thisTurn);
        return "session created";
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
    /**
     * @param array<CardGraphic> $thisTurn
     */
    private function savePlayerHand(SessionInterface $session, array $thisTurn): string
    {
        $drawnCards = $session->get("blackjackHand");
        $hand = new BlackjackHand();
        if ($session->has("blackjackHand")) {
            /**
             * @var array<CardGraphic> $drawnCards
             */
            $hand->setHand($drawnCards);
            $allUsed = $hand->mergeCards($thisTurn);
            $session->set("blackjackHand", $allUsed);
            return "session exists";
        }
        $session->set("blackjackHand", $thisTurn);
        return "session created";
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
    /**
     * @param array<CardGraphic> $thisTurn
     */
    private function saveBankHand(SessionInterface $session, array $thisTurn): string
    {
        $drawnCards = $session->get("bankHand");
        $hand = new BlackjackHand();
        /**
         * @var array<CardGraphic> $drawnCards
         */
        if ($session->has("bankHand")) {
            $hand->setHand($drawnCards);
            $allUsed = $hand->mergeCards($thisTurn);
            $session->set("bankHand", $allUsed);
            return "session exists";
        }

        $session->set("bankHand", $thisTurn);
        return "session created";
    }
}
