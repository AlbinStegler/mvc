<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Utils\Helpers;
use App\Card\DeckOfCards;
use App\Card\BlackjackHand;
use App\Card\CardGraphic;
use App\Game\Player;
use App\Game\Bank;

use PHPUnit\TextUI\Help;

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
        $helper = new Helpers();
        //Korten hämtas från session
        $hand = $helper->getPlayerHand($session);
        $bankHand = $helper->getBankHand($session);
        $player = new Player($hand);

        $playerLost = false;
        $stop = false;

        //Spelare får val att dra kort om korten är under 21
        $playerRoll = $player->canIDraw($hand->getSum());
        if (!$playerRoll && $hand->getSum() > 21) {
            $playerLost = true;
        }

        //Vinnare räknas ut
        if ($session->has("stop") || $hand->getSum() == 21) {
            $stop = true;
            $session->set("stop", true);
            $playerWon = $player->playerWon($bankHand);
            $data = ["cards" => $hand->getCards(),
            "value" => $hand->getSum(),
            "player" => $playerRoll,
            "lost" => $playerLost,
            "playerWon" => $playerWon,
            "bankHand" => $bankHand->getCards(),
            "bankSum" => $bankHand->getSum(),
            "stop" => $stop];
        } if (!$session->has("stop")) {
            $data = ["cards" => $hand->getCards(),
            "value" => $hand->getSum(),
            "player" => $playerRoll,
            "lost" => $playerLost,
            "bankHand" => $bankHand->getCards(),
            "bankSum" => $bankHand->getSum(),
            "stop" => $stop];
        }



        return $this->render('cardgame/start.html.twig', $data);
    }

    #[Route("/game/start/player-draw", name: "player-draw", methods: ['POST'])]
    public function drawPlayer(SessionInterface $session): Response
    {
        $helper = new Helpers();
        //Hämtar från Session
        $deck = $helper->createBlackjackDeckFromSession($session);
        $hand = $helper->getPlayerHand($session);
        $deck->shuffleDeck();
        $drawn = $deck->drawCard();
        $hand->add($drawn);

        //Sparar draget kort till session och redirectar till startsida

        $helper->savePlayerHand($session, [$drawn->showCard()]);
        $helper->saveBlackjackDeckToSession($session, [$drawn->showCard()]);
        return $this->redirectToRoute('start');
    }

    #[Route("/game/start/reset", name: "reset", methods: ['POST'])]
    public function reset(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('start');
    }

    #[Route("/game/start/bank", name: "bank-draw", methods: ['POST'])]
    public function bankDraw(SessionInterface $session): Response
    {
        //OM SPELARE STANNAR
        $helper = new Helpers();
        $deck = $helper->createBlackjackDeckFromSession($session);
        $hand = $helper->getBankHand($session);
        $bank = new Bank($hand);
        $deck->shuffleDeck();
        $session->set("stop", true);

        $allDrawn = [];
        //Banken drar kort sparas till session och laddas in.
        while ($bank->canIDraw($hand->getSum())) {
            $drawn = $deck->drawCard();
            $hand->add($drawn);
            $allDrawn[] = $drawn->showCard();
        }


        $helper->saveBankHand($session, $allDrawn);
        $helper->saveBlackjackDeckToSession($session, [$drawn->showCard()]);
        return $this->redirectToRoute('start');
    }
}
