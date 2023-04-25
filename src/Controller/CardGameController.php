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
        //Spelare och bank skapas
        // $session->clear();
        $hand = Helpers::getPlayerHand($session);
        $bankHand = Helpers::getBankHand($session);
        $lost = False;
        $player = new Player($hand);
        //Finns korten i sessionen för spelarna återskapas de

        //Spelare får val att dra kort om korten är under 21
        $playerRoll = $player->canIDraw($hand->getSum());
        //Sparar draget kort till session och laddar om sidan med de nya värdena
        if (!$playerRoll && $hand->getSum() > 21) {
            $lost = True;
        }

        //OM SPELARE STANNAR

        //Banken drar kort sparas till session och laddas in.

        //Värdet kontrolleras om bank får dra mer

        //Vinnare räknas ut
        $data = ["cards" => $hand->getCards(), "value" => $hand->getSum(),
        "player" => $playerRoll, "lost" => $lost, "bankHand" => $bankHand->getCards(), "bankSum" => $bankHand->getSum()];
        dump($hand);
        dump($bankHand);

        return $this->render('cardgame/start.html.twig' , $data);
    }

    #[Route("/game/start/player-draw", name: "player-draw", methods: ['POST'])]
    public function drawPlayer(SessionInterface $session): Response
    {
        $deck = Helpers::createBlackjackDeckFromSession($session);
        $hand = Helpers::getPlayerHand($session);
        $deck->shuffleDeck();
        $drawn = $deck->drawCard();
        $hand->add($drawn);


        Helpers::savePlayerHand($session, [$drawn->showCard()]);
        Helpers::saveBlackjackDeckToSession($session, [$drawn->showCard()]);
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
        $deck = Helpers::createBlackjackDeckFromSession($session);
        $hand = Helpers::getBankHand($session);
        $deck->shuffleDeck();

        while ($hand->getSum() < 21) {
            $drawn = $deck->drawCard();
            $hand->add($drawn);
        }


        Helpers::saveBankHand($session, [$drawn->showCard()]);
        Helpers::saveBlackjackDeckToSession($session, [$drawn->showCard()]);
        return $this->redirectToRoute('start');
    }
}
