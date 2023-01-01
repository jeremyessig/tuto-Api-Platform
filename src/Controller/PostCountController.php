<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostCountController extends AbstractController
{
    public function __construct(private PostRepository $postRepository)
    {
    }

    public function __invoke(): int
    {
        //return $this->postRepository->count([]);
        return 10;
    }
}
