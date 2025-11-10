<?php

namespace App\Controller;

use App\Entity\MenuItem;
use App\Repository\MenuItemRepository; // ← à ajouter
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/menu', name: 'app_menu')]
final class MenuController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(MenuItemRepository $repo): JsonResponse
    {
        $menuItems = $repo->findAll();
        $data = [];

        foreach ($menuItems as $item) {
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'price' => $item->getPrice(),
                'image' => $item->getImage() // URL relative ou absolute
            ];
        }

        return $this->json(['menu' => $data]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['description'], $data['price'])) {
            return new JsonResponse(['error' => 'Champs requis: name, description, price'], 400);
        }

        $item = new MenuItem();
        $item->setName($data['name'])
             ->setDescription($data['description'])
             ->setPrice($data['price'])
             ->setImage($data['image'] ?? null);

        $em->persist($item);
        $em->flush();

        return $this->json([
            'message' => 'Produit ajouté',
            'item' => [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'price' => $item->getPrice(),
                'image' => $item->getImage(),
            ]
        ], 201);
    }
}
