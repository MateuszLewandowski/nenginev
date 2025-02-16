<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Application\Http\Controller;

use App\ComputationalIntelligence\Model\Regression\TrainingProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(stateless: true)]
final class TrainRegressionModelController extends AbstractController
{
    public function __construct(
       private readonly TrainingProcessor $trainingProcessor,
    ) {
    }

    #[Route(path: '/regression/model/train', methods: Request::METHOD_POST)]
    public function __invoke(Request $request): Response
    {
        $result = ($this->trainingProcessor)($request);

        dd($result);
    }
}