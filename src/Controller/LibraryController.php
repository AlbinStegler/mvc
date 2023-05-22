<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route("/library", name: "library")]
    public function libraryLanding(): Response
    {
        return $this->render('library/library-landing.html.twig');
    }

    #[Route("/library/create", name: "create")]
    public function createPage(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route("/library/createbook", name: "createsql", methods: ['POST'])]
    public function createPagePost(ManagerRegistry $doctrine, Request $request): Response
    {
        $data = ($request->request->all());
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle($data["title"]);
        $book->setIsbn((int)$data["isbn"]);
        $book->setAuthor($data["author"]);
        if (array_key_exists("img", $data)) {
            $book->setImg($data["img"]);
        }
        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('library');
    }

    #[Route("/library/showall", name: "showall")]
    public function showAll(BookRepository $bookRepository, Request $request): Response
    {
        $all = [];
        $all["books"] = $bookRepository->findAll();

        return $this->render('library/show-all.html.twig', $all);
    }



    #[Route('/library/show/{id}', name: 'book_by_id')]
    public function showProductById(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = [];
        $book["book"] = $bookRepository
            ->find($id);

        return $this->render('library/show-one.html.twig', $book);
    }

    #[Route('/library/delete/{id}', name: 'book_delete_by_id')]
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = new Book();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id ' . $id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('showall');
    }

    #[Route("/library/update/{id}", name: "update")]
    public function updateBook(
        BookRepository $bookRepository,
        int $id
    ): Response {

        $book["book"] = $bookRepository
            ->find((int)$id);

        return $this->render('library/update.html.twig', $book);
    }

    #[Route("/library/updatebook", name: "updatePost", methods: ['POST'])]
    public function updateBookPost(ManagerRegistry $doctrine, Request $request): Response
    {

        $data = ($request->request->all());
        $entityManager = $doctrine->getManager();

        $entityManager = $doctrine->getManager();
        /**
         * @var Book $book
         */
        $book = $entityManager->getRepository(Book::class)->find((int)$data["id"]);


        $book->setTitle($data["title"]);
        $book->setIsbn((int)$data["isbn"]);
        $book->setAuthor($data["author"]);

        if (array_key_exists("img", $data)) {
            $book->setImg($data["img"]);
        }
        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('showall');
    }
}
