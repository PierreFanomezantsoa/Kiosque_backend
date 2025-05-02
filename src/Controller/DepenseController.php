<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\BudgetMensuel;
use App\Entity\MembreFamille;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class DepenseController extends AbstractController
{
    #[Route('/depense', name: 'app_depense', methods: ['POST'])]
    public function AjoutDepense(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['description'], $data['date_dep'], $data['budget_mensuel'], $data['membrefamille'], $data['cat_id'])) {
            return new JsonResponse(['error' => 'Champs requis: description, date_dep, budget_mensuel, membrefamille, cat_id'], 400);
        }

        try {
            $date = new \DateTime($data['date_dep']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Format de date invalide. Utiliser YYYY-MM-DD'], 400);
        }

        $budgetMensuel = $em->getRepository(BudgetMensuel::class)->find($data['budget_mensuel']);
        if (!$budgetMensuel) {
            return new JsonResponse(['error' => 'BudgetMensuel non trouvé'], 404);
        }

        $membreFamille = $em->getRepository(MembreFamille::class)->find($data['membrefamille']);
        if (!$membreFamille) {
            return new JsonResponse(['error' => 'MembreFamille non trouvé'], 404);
        }

        $categorie = $em->getRepository(Categorie::class)->find($data['cat_id']);
        if (!$categorie) {
            return new JsonResponse(['error' => 'Catégorie non trouvée'], 404);
        }

        $depense = new Depense();
        $depense->setDescription($data['description']);
        $depense->setMontantDepense($data['montant_depense']);
        $depense->setDateDep($date);
        $depense->setRefDep($data['ref_dep'] ?? 0);
        $depense->setBudgetMensuel($budgetMensuel);
        $depense->setMembrefamille($membreFamille);
        $depense->setCatId($categorie);

        $em->persist($depense);
        $em->flush();

        return $this->json([
            'message' => 'Dépense ajoutée avec succès',
            'depense' => [
                'id'=> $depense->getId(),
                'montant_depense'=> $depense->getMontantDepense(),
                'ref_dep' => $depense->getRefDep(),
                'description' => $depense->getDescription(),
                'budget_mensuel' => $budgetMensuel->getId(),
                'membrefamille' => $membreFamille->getId(),
                'cat_id' => $categorie->getId(),
                'date_dep' => $depense->getDateDep()->format('Y-m-d'),
            ]
        ], 201);
    }

    #[Route('/depense', name: 'liste_depenses', methods: ['GET'])]
    public function ListeDepenses(EntityManagerInterface $em): JsonResponse
    {
        $depenses = $em->getRepository(Depense::class)->findAll();

        $data = array_map(function (Depense $depense) {
            return [
                'id' => $depense->getId(),
                'ref_dep' => $depense->getRefDep(),
                'montant_depense' => $depense->getMontantDepense(),
                'description' => $depense->getDescription(),
                'date_dep' => $depense->getDateDep()->format('Y-m-d'),
                'budget_mensuel' => $depense->getBudgetMensuel()?->getId(),
                'membrefamille' => $depense->getMembrefamille()?->getId(),
                'cat_id' => $depense->getCatId()?->getId(),
            ];
        }, $depenses);

        return $this->json($data);
    }

    // methode pour suppression donnée
    #[Route('/depense/{id}', name: 'delete_depense', methods: ['DELETE'])]
    public function SupprimerDepense(int $id, EntityManagerInterface $em): JsonResponse
    {
        $depense = $em->getRepository(Depense::class)->find($id);

        if (!$depense) {
            return new JsonResponse(['error' => 'Dépense non trouvée'], 404);
        }

        $em->remove($depense);
        $em->flush();

        return new JsonResponse(['message' => 'Dépense supprimée avec succès']);
    }
    // methode pour modifier donnée
    #[Route('/depense/{id}', name: 'update_depense', methods: ['PUT'])]
    public function ModifierDepense(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $depense = $em->getRepository(Depense::class)->find($id);
        if (!$depense) {
            return new JsonResponse(['error' => 'Dépense non trouvée'], 404);
        }

        if (isset($data['description'])) {
            $depense->setDescription($data['description']);
        }

        if (isset($data['date_dep'])) {
            try {
                $date = new \DateTime($data['date_dep']);
                $depense->setDateDep($date);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Format de date invalide. Utiliser YYYY-MM-DD'], 400);
            }
        }

        if (isset($data['ref_dep'])) {
            $depense->setRefDep($data['ref_dep']);
        }
        if(isset($data['montant_depense'])) {
            $depense->setMontantDepense($data['montant_depense']);
        }

        if (isset($data['budget_mensuel'])) {
            $budget = $em->getRepository(BudgetMensuel::class)->find($data['budget_mensuel']);
            if (!$budget) {
                return new JsonResponse(['error' => 'BudgetMensuel non trouvé'], 404);
            }
            $depense->setBudgetMensuel($budget);
        }

        if (isset($data['membrefamille'])) {
            $membre = $em->getRepository(MembreFamille::class)->find($data['membrefamille']);
            if (!$membre) {
                return new JsonResponse(['error' => 'MembreFamille non trouvé'], 404);
            }
            $depense->setMembrefamille($membre);
        }

        if (isset($data['cat_id'])) {
            $categorie = $em->getRepository(Categorie::class)->find($data['cat_id']);
            if (!$categorie) {
                return new JsonResponse(['error' => 'Catégorie non trouvée'], 404);
            }
            $depense->setCatId($categorie);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Dépense mise à jour avec succès']);
    }


}
