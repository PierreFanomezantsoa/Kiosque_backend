<?php

namespace App\Controller;

use App\Entity\BudgetMensuel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class BudgetController extends AbstractController
{
    #[Route('/budget', name: 'app_budget_post', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['mois']) || !isset($data['annee'])) {
            return new JsonResponse(['error' => 'Champs requis: mois et annee'], 400);
        }

        $budget = new BudgetMensuel();
        $budget->setMois($data['mois']);
        $budget->setAnnee($data['annee']);

        $em->persist($budget);
        $em->flush();

        return $this->json([
            'message' => 'Budget mensuel ajouté avec succès',
            'budget' => [
                'id' => $budget->getId(),
                'mois' => $budget->getMois(),
                'annee' => $budget->getAnnee(),
            ]
        ], 201);
    }
}
