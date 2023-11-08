<?php

namespace App\Controller\Admin;

use App\Entity\Projects;
use App\Repository\ProjectsRepository;
use App\Service\PicturesServices;
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

    #[Route('/admin/delete/{slug}', name: 'app_delete_project', methods: ['DELETE'])]
    public function deleteProject(Projects $project, PicturesServices $picturesServices, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $data['_token'])) {
            // Le token csrf est valide
            if ($picturesServices->delete($project->getProjImage(), 'thumbnails')) {
                $images = $project->getImages();
                foreach ($images as $image) {
                    $picturesServices->delete($image->getFileSrc(), 'projects');
                }
                $em->remove($project);
                $em->flush();
                return new JsonResponse(['success' => true], 200);
            } else {
                return new JsonResponse(['error' => 'Erreur lors de la suppression des images !']);
            }
        }
        return new JsonResponse(['error' => 'Token Invalide !']);
    }
}
