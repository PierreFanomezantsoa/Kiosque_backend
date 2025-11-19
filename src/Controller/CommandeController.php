<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    // LISTE TOUTES LES COMMANDES
    #[Route('', methods: ['GET'])]
    public function index(CommandeRepository $repo): JsonResponse
    {
        $commandes = $repo->findAll();

        $data = array_map(function (Commande $c) {
            return [
                'id' => $c->getId(),
                'table_number' => $c->getTableNumber(),
                'order_number' => $c->getOrderNumber(),
                'total_amount' => $c->getTotalAmount(),
                'payment_method' => $c->getPaymentMethod(),
                'status' => $c->getStatus(),
                'items' => json_decode($c->getItems(), true),
                'created_at' => $c->getCreatedAt()?->format('Y-m-d H:i:s'),
            ];
        }, $commandes);

        return $this->json($data);
    }

    // AFFICHER UNE COMMANDE PAR ID
    #[Route('/{id}', methods: ['GET'])]
    public function show(Commande $commande): JsonResponse
    {
        return $this->json([
            'id' => $commande->getId(),
            'table_number' => $commande->getTableNumber(),
            'order_number' => $commande->getOrderNumber(),
            'total_amount' => $commande->getTotalAmount(),
            'payment_method' => $commande->getPaymentMethod(),
            'status' => $commande->getStatus(),
            'items' => json_decode($commande->getItems(), true),
            'created_at' => $commande->getCreatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    // CRÉER UNE COMMANDE
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(["error" => "Invalid JSON"], 400);
        }

        $commande = new Commande();
        $commande->setTableNumber($data['table_number'] ?? 0);
        $commande->setOrderNumber($data['order_name'] ?? 'Commande');
        $commande->setTotalAmount($data['total_amount'] ?? '0');
        $commande->setPaymentMethod($data['payment_method'] ?? 'Inconnu');
        $commande->setStatus($data['status'] ?? 'En cours');

        // items -> doit être array → converti proprement
        $commande->setItems(json_encode($data['items'] ?? []));

        // 🔥 AJOUT AUTOMATIQUE DE LA DATE ACTUELLE
        $commande->setCreatedAt(new \DateTimeImmutable());

        $em->persist($commande);
        $em->flush();

        return $this->json([
            "message" => "Commande créée",
            "commande_id" => $commande->getId()
        ], 201);
    }

    // METTRE À JOUR UNE COMMANDE
    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Commande $commande, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $commande->setTableNumber($data['table_number'] ?? $commande->getTableNumber());
        $commande->setOrderNumber($data['order_name'] ?? $commande->getOrderNumber());
        $commande->setTotalAmount($data['total_amount'] ?? $commande->getTotalAmount());
        $commande->setPaymentMethod($data['payment_method'] ?? $commande->getPaymentMethod());
        $commande->setStatus($data['status'] ?? $commande->getStatus());

        // items mis à jour proprement
        if (isset($data['items'])) {
            $commande->setItems(json_encode($data['items']));
        }

        // created_at NE CHANGE PAS
        $em->flush();

        return $this->json(["message" => "Commande mise à jour"]);
    }

    // SUPPRIMER UNE COMMANDE
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Commande $commande, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($commande);
        $em->flush();

        return $this->json(["message" => "Commande supprimée"]);
    }
}
