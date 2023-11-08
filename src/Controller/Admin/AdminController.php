<?php

namespace App\Controller\Admin;

use App\Repository\ProjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ProjectsRepository $projectsRepository): Response
    {
        $projects = $projectsRepository->findBy([], ['priority' => 'ASC']);
        return $this->render('admin/index.html.twig', compact('projects'));
    }

    #[Route('/admin/remakeOrder', name: 'app_remake_order', methods: ['PUT'])]
    public function remakeOrder(ProjectsRepository $projectsRepository, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('updateProjectsOrder', $data['_token'])) {
            // Le token csrf est valide
            $idOrdored = json_decode($data['_idOrder']);
            for ($i = 0; $i < count($idOrdored); $i++) {
                $project = $projectsRepository->findOneBy(["id" => $idOrdored[$i]]);
                $project->setPriority($i + 1);
                $em->persist($project);
            }
            $em->flush();
            return new JsonResponse(['success' => true], 200);
        }
        return new JsonResponse(['error' => 'Token Invalide !']);
    }
}
