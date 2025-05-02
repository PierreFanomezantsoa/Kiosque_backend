<?php

namespace App\Controller;

use App\Entity\Enseignant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class EnseignantController extends AbstractController
{
    #[Route('/enseignant', name: 'app_enseignant', methods: ['POST'])]
    public function ajoutEnseignant(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['matricule'], $data['nom'], $data['nombre_heure'], $data['tauxhoraire'])) {
            return new JsonResponse(['error' => 'Champs requis: matricule, nom, nombre_heure, tauxhoraire'], 400);
        }

        $enseignant = new Enseignant();
        $enseignant->setMatricule($data['matricule']);
        $enseignant->setNom($data['nom']);
        $enseignant->setNombreHeure($data['nombre_heure']);
        $enseignant->setTauxHoraire($data['tauxhoraire']);

        $em->persist($enseignant);
        $em->flush();

        return $this->json([
            'message' => 'Enseignant ajouté avec succès',
            'enseignant' => [
                'id' => $enseignant->getId(),
                'matricule'=>$enseignant->getMatricule(),
                'nom' => $enseignant->getNom(),
                'tauxhoraire' => $enseignant->getTauxhoraire(),
                'nombre_heure' => $enseignant->getNombreHeure(),
            ]
        ], 201);
    }

    // liste enseignant 
    #[Route('/enseignant', name: 'liste_enseignants', methods: ['GET'])]
    public function listeEnseignants(EntityManagerInterface $em): JsonResponse
    {
        $enseignants = $em->getRepository(Enseignant::class)->findAll();
    
        $data = [];
        $prestationMax = null;
        $prestationMin = null;
    
        foreach ($enseignants as $enseignant) {
            $prestation = $enseignant->getNombreHeure() * $enseignant->getTauxHoraire();
    
            $data[] = [
                'id' => $enseignant->getId(),
                'matricule' => $enseignant->getMatricule(),
                'nom' => $enseignant->getNom(),
                'tauxhoraire' => $enseignant->getTauxHoraire(),
                'nombre_heure' => $enseignant->getNombreHeure(),
                'prestation' => $prestation,
            ];
    
            if ($prestationMax === null || $prestation > $prestationMax['prestation']) {
                $prestationMax = [
                    'id' => $enseignant->getId(),
                    'nom' => $enseignant->getNom(),
                    'prestation' => $prestation,
                ];
            }
    
            if ($prestationMin === null || $prestation < $prestationMin['prestation']) {
                $prestationMin = [
                    'id' => $enseignant->getId(),
                    'nom' => $enseignant->getNom(),
                    'prestation' => $prestation,
                ];
            }
        }
    
        return $this->json([
            'enseignants' => $data,
            'prestation_max' => $prestationMax,
            'prestation_min' => $prestationMin,
        ]);
    }    
    // Suppression de donnée dans table enseignant
    #[Route('/enseignant/{id}', name: 'delete_enseignant', methods: ['DELETE'])]
    public function supprimerEnseignant(int $id, EntityManagerInterface $em): JsonResponse
    {
        $enseignant = $em->getRepository(Enseignant::class)->find($id);

        if (!$enseignant) {
            return new JsonResponse(['error' => 'Enseignant non trouvé'], 404);
        }

        $em->remove($enseignant);
        $em->flush();

        return new JsonResponse(['message' => 'Enseignant supprimé avec succès']);
    }

    // Méthode de modification (update) complète :
    #[Route('/enseignant/{id}', name: 'update_enseignant', methods: ['PUT', 'PATCH'])]
    public function modifierEnseignant(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $enseignant = $em->getRepository(Enseignant::class)->find($id);

        if (!$enseignant) {
            return new JsonResponse(['error' => 'Enseignant non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['matricule'])) {
            $enseignant->setMatricule($data['matricule']);
        }
        if (isset($data['nom'])) {
            $enseignant->setNom($data['nom']);
        }
        if (isset($data['nombre_heure'])) {
            $enseignant->setNombreHeure($data['nombre_heure']);
        }
        if (isset($data['tauxhoraire'])) {
            $enseignant->setTauxHoraire($data['tauxhoraire']);
        }

        $em->flush();

        return $this->json([
            'message' => 'Enseignant modifié avec succès',
            'enseignant' => [
                'id' => $enseignant->getId(),
                'matricule' => $enseignant->getMatricule(),
                'nom' => $enseignant->getNom(),
                'nombre_heure' => $enseignant->getNombreHeure(),
                'tauxhoraire' => $enseignant->getTauxhoraire(),
            ]
        ]);
    }
}
