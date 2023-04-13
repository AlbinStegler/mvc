<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class LuckyController extends AbstractController
{
    #[Route('/lucky', name: "lucky")]
    public function number(): Response
    {
        $number = random_int(0, 100);
        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route("/lucky/hi")]
    public function hi(): Response
    {
        return new Response(
            '<html><body>Hi to you!</body></html>'
        );
    }


}
