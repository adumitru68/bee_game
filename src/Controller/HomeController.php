<?php

namespace App\Controller;

use App\Domain\Repository\UserRepository;
use App\Framework\AbstractController;

class HomeController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index():string
    {
        return $this->render(
          'home.phtml',
          [
              'users' => $this->userRepository->get(),
          ]
        );
    }
}