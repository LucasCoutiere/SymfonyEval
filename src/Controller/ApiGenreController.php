<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genre", name="api_genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository)
    {
        return $this->json($genreRepository->findAll(), 200, [], ['groups' => 'post:read']);
    }

    /**
     * @Route("/api/genre", name="api_genre_store", methods={"POST"})
     */
    public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonRecu = $request->getContent();

        try {
            $genre = $serializer->deserialize($jsonRecu, Genre::class, 'json');
            $genre->setCreatedAt(new \DateTime());

            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            
            $em->persist($genre);
            $em->flush();
            
            return $this->json($genre, 201, [], ['groups' => 'post:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}