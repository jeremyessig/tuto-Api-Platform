<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostPublishController extends AbstractController
{

    public function __invoke(Post $data): Post
    {
        $data->setOnline(true);
        return $data;
    }
}
