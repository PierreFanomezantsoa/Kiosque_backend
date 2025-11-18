<?php

namespace App\Controller;

use App\Entity\MenuItem;
use App\Repository\MenuItemRepository;
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
                'image' => $item->getImage()
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

    // 🔹 Méthode PUT pour mettre à jour un menu existant
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, MenuItemRepository $repo): JsonResponse
    {
        $item = $repo->find($id);
        if (!$item) {
            return new JsonResponse(['error' => 'Plat non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) $item->setName($data['name']);
        if (isset($data['description'])) $item->setDescription($data['description']);
        if (isset($data['price'])) $item->setPrice($data['price']);
        if (array_key_exists('image', $data)) $item->setImage($data['image']);

        $em->flush();

        return $this->json([
            'message' => 'Plat mis à jour',
            'item' => [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'price' => $item->getPrice(),
                'image' => $item->getImage(),
            ]
        ]);
    }

    // 🔹 Méthode DELETE pour supprimer un plat
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, MenuItemRepository $repo): JsonResponse
    {
        $item = $repo->find($id);
        if (!$item) {
            return new JsonResponse(['error' => 'Plat non trouvé'], 404);
        }

        $em->remove($item);
        $em->flush();

        return $this->json(['message' => 'Plat supprimé']);
    }
}
