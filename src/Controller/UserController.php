<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    ) {}

    /**
     * GET /api/users - Récupérer tous les utilisateurs
     */
    #[Route('', name: 'api_users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        
        $data = array_map(fn(User $user) => $user->toArray(), $users);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * GET /api/users/{id} - Récupérer un utilisateur par ID
     */
    #[Route('/{id}', name: 'api_users_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Utilisateur non trouvé'],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse($user->toArray(), Response::HTTP_OK);
    }

    /**
     * POST /api/users - Créer un nouvel utilisateur
     */
    #[Route('', name: 'api_users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['email'])) {
            return new JsonResponse(
                ['error' => 'Les champs "name" et "email" sont requis'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Vérifier si l'email existe déjà
        $existingUser = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(
                ['error' => 'Un utilisateur avec cet email existe déjà'],
                Response::HTTP_CONFLICT
            );
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $this->userRepository->save($user);

        return new JsonResponse(
            $user->toArray(),
            Response::HTTP_CREATED
        );
    }

    /**
     * PUT /api/users/{id} - Mettre à jour un utilisateur
     */
    #[Route('/{id}', name: 'api_users_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Utilisateur non trouvé'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['email'])) {
            // Vérifier si l'email n'est pas déjà utilisé par un autre utilisateur
            $existingUser = $this->userRepository->findOneBy(['email' => $data['email']]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                return new JsonResponse(
                    ['error' => 'Un utilisateur avec cet email existe déjà'],
                    Response::HTTP_CONFLICT
                );
            }
            $user->setEmail($data['email']);
        }

        $this->entityManager->flush();

        return new JsonResponse($user->toArray(), Response::HTTP_OK);
    }

    /**
     * DELETE /api/users/{id} - Supprimer un utilisateur
     */
    #[Route('/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Utilisateur non trouvé'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->userRepository->remove($user);

        return new JsonResponse(
            ['message' => 'Utilisateur supprimé avec succès'],
            Response::HTTP_OK
        );
    }
}
