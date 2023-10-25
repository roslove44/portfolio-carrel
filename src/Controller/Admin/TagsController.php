<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class TagsController extends AbstractController
{
    #[Route('admin/tags/add/ajax/{label}', name: 'app_admin_tags', methods: ["POST"])]
    public function addTags($label, EntityManagerInterface $em): JsonResponse
    {
        $tag = new Tags();
        $tag->setName(trim(strip_tags($label)));
        $em->persist($tag);
        $em->flush();
        $id = $tag->getId();

        return new JsonResponse(compact('id'));
    }
}
