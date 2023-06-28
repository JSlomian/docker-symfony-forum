<?php

namespace App\Controller\Public;

use App\Repository\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ForumRepository $forumRepository): Response
    {
        $forums = $forumRepository->findAll();
        return $this->render('public/index.html.twig', [
            'forums' => $forums,
        ]);
    }
}
