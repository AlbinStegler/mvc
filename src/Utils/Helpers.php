<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\BlackjackHand;

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
            return $deck;
        }
        $deck->setupDeck();

        return $deck;
    }

    public static function saveToSession(SessionInterface $session, array $thisTurn)
    {
        $drawnCards = $session->get("usedCards");
        if ($session->has("usedCards")) {
            $allUsed = array_merge($drawnCards, $thisTurn);
            $session->set("usedCards", $allUsed);
            return "session exists";
        }
        $session->set("usedCards", $thisTurn);
        return "session created";
    }


    public static function createBlackjackDeckFromSession(SessionInterface $session): DeckOfCards
    {
        $deck = new DeckOfCards();
        if ($session->has("blackjackDeck")) {
            $used = $session->get("blackjackDeck");
            $cardArr = [];
            foreach ($used as $card) {
                $tCard = new CardGraphic();
                $tCard->setValue($card["value"]);
                $tCard->setType($card["type"]);
                $tCard->setStyle();
                $cardArr[] = $tCard;
            }
            $deck->recreateDeck($cardArr);
            return $deck;
        }
        $deck->setupDeck();

        return $deck;
    }
    public static function saveBlackjackDeckToSession(SessionInterface $session, array $thisTurn)
    {
        $drawnCards = $session->get("blackjackDeck");
        if ($session->has("blackjackDeck")) {
            $allUsed = array_merge($drawnCards, $thisTurn);
            $session->set("blackjackDeck", $allUsed);
            return "session exists";
        }
        $session->set("blackjackDeck", $thisTurn);
        return "session created";

    }

    public static function getPlayerHand(SessionInterface $session)
    {
        $hand = new BlackjackHand();
        if ($session->has("blackjackHand")) {
            $used = $session->get("blackjackHand");
            foreach ($used as $card) {
                $tCard = new CardGraphic();
                $tCard->setValue($card["value"]);
                $tCard->setType($card["type"]);
                $tCard->setStyle();
                $hand->add($tCard);
            }
        }
        return $hand;
    }

    public static function savePlayerHand(SessionInterface $session, array $thisTurn)
    {
        $drawnCards = $session->get("blackjackHand");
        if ($session->has("blackjackHand")) {
            $allUsed = array_merge($drawnCards, $thisTurn);
            $session->set("blackjackHand", $allUsed);
            return "session exists";
        }
        $session->set("blackjackHand", $thisTurn);
        return "session created";
    }

    public static function getBankHand(SessionInterface $session)
    {
        $hand = new BlackjackHand();
        if ($session->has("bankHand")) {
            $used = $session->get("bankHand");
            foreach ($used as $card) {
                $tCard = new CardGraphic();
                $tCard->setValue($card["value"]);
                $tCard->setType($card["type"]);
                $tCard->setStyle();
                $hand->add($tCard);
            }
        }
        return $hand;
    }

    public static function saveBankHand(SessionInterface $session, array $thisTurn)
    {
        $drawnCards = $session->get("bankHand");
        if ($session->has("bankHand")) {
            $allUsed = array_merge($drawnCards, $thisTurn);
            $session->set("bankHand", $allUsed);
            return "session exists";
        }

        $session->set("bankHand", $thisTurn);
        return "session created";
    }

}