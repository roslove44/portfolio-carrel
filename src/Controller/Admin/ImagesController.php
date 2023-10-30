<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Service\PicturesServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class ImagesController extends AbstractController
{
    #[Route('admin/images/delete/ajax/{id}', name: 'app_admin_images', methods: ["DELETE"])]
    public function deleteImages(Images $image, EntityManagerInterface $em, Request $request, PicturesServices $picturesServices): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // Le token csrf est valide
            $name = $image->getName();
            if ($picturesServices->delete($name, 'projects')) {
                $em->remove($image);
                $em->flush();
                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Erreur de suppression']);
        }

        return new JsonResponse(['error' => 'Token Invalide']);
    }
}
