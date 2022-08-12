<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'category')]
    public function index(): Response
    {
        return $this->render('admin/categoryAdmin.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
