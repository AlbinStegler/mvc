<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;

class Helpers
{
    public static function createDeckFromSession(SessionInterface $session): DeckOfCards
    {

        $deck = new DeckOfCards();
        if ($session->has("usedCards")) {
            $used = $session->get("usedCards");
            $cardArr = [];
            foreach ($used as $card) {
                $tCard = new CardGraphic();
                $tCard->setValue($card["value"]);
                $tCard->setType($card["type"]);
                $tCard->setStyle();
                $cardArr[] = $tCard;
            }
            $deck->recreateDeck($cardArr);
        } else {
            $deck->setupDeck();
        }

        return $deck;
    }

    public static function saveToSession(SessionInterface $session, array $thisTurn)
    {
        $drawnCards = $session->get("usedCards");
        if ($session->has("usedCards")) {
            $allUsed = array_merge($drawnCards, $thisTurn);
            $session->set("usedCards", $allUsed);
        } else {
            $session->set("usedCards", $thisTurn);
        }
    }

}
