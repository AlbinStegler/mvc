<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteJson extends AbstractController
{
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
