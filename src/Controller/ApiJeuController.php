<?php

namespace App\Controller;

use App\Entity\Jeu;
use App\Repository\JeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiJeuController extends AbstractController
{
    /**
     * @Route("/api/jeu", name="api_jeu_index", methods={"GET"})
     */
    public function index(JeuRepository $jeuRepository)
    {
        return $this->json($jeuRepository->findAll(), 200, [], ['groups' => 'post:read']);
    }

    /**
     * @Route("/api/jeu", name="api_jeu_store", methods={"POST"})
     */
    public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonRecu = $request->getContent();

        try {
            $jeu = $serializer->deserialize($jsonRecu, Jeu::class, 'json');
            $jeu->setCreatedAt(new \DateTime());

            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            
            $em->persist($jeu);
            $em->flush();
            
            return $this->json($jeu, 201, [], ['groups' => 'post:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}