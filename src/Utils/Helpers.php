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
    public static function saveToSession(SessionInterface $session, array $thisTurn): string
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


    public static function createBlackjackDeckFromSession(SessionInterface $session): DeckOfCards
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
    public static function saveBlackjackDeckToSession(SessionInterface $session, array $thisTurn): string
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

    public static function getPlayerHand(SessionInterface $session): BlackjackHand
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
    public static function savePlayerHand(SessionInterface $session, array $thisTurn): string
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

    public static function getBankHand(SessionInterface $session): BlackjackHand
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
    public static function saveBankHand(SessionInterface $session, array $thisTurn): string
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
