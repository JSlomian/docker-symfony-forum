<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class AdminController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'app_admin_home')]
    public function admin_index(): Response
    {
        return $this->render("admin/index.html.twig");
    }
}
