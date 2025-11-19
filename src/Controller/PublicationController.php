<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publication')]
final class PublicationController extends AbstractController
{
    private EntityManagerInterface $em;
    private PublicationRepository $repo;

    public function __construct(EntityManagerInterface $em, PublicationRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    // LISTE DES PUBLICATIONS
    #[Route('/', name: 'app_publication_list', methods: ['GET'])]
    public function index(): Response
    {
        $publications = $this->repo->findAll();
        return $this->json($publications);
    }

    // CRÉER UNE PUBLICATION
    #[Route('/create', name: 'app_publication_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $publication = new Publication();
        $publication->setNom($data['nom'] ?? null);
        $publication->setDescription($data['description'] ?? '');
        $publication->setPrix($data['prix'] ?? null);
        $publication->setPrixPromo($data['prixPromo'] ?? 0);
        $publication->setImage($data['image'] ?? '');

        $this->em->persist($publication);
        $this->em->flush();

        return $this->json([
            'message' => 'Publication créée avec succès',
            'publication' => $publication
        ], Response::HTTP_CREATED);
    }

    // AFFICHER UNE PUBLICATION
    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $publication = $this->repo->find($id);
        if (!$publication) {
            return $this->json(['message' => 'Publication non trouvée'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($publication);
    }

    // MODIFIER UNE PUBLICATION
    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): Response
    {
        $publication = $this->repo->find($id);
        if (!$publication) {
            return $this->json(['message' => 'Publication non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $publication->setNom($data['nom'] ?? $publication->getNom());
        $publication->setDescription($data['description'] ?? $publication->getDescription());
        $publication->setPrix($data['prix'] ?? $publication->getPrix());
        $publication->setPrixPromo($data['prixPromo'] ?? $publication->getPrixPromo());
        $publication->setImage($data['image'] ?? $publication->getImage());

        $this->em->flush();

        return $this->json([
            'message' => 'Publication mise à jour',
            'publication' => $publication
        ]);
    }

    // SUPPRIMER UNE PUBLICATION
    #[Route('/{id}/delete', name: 'app_publication_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $publication = $this->repo->find($id);
        if (!$publication) {
            return $this->json(['message' => 'Publication non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($publication);
        $this->em->flush();

        return $this->json(['message' => 'Publication supprimée']);
    }
}
