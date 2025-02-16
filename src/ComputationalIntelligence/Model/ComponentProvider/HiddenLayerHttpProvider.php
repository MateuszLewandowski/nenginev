<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\EvaluationFunction\InitializerHttpProvider;
use App\ComputationalIntelligence\Model\Exception\MissingHiddenLayerException;
use App\ComputationalIntelligence\Model\Network\Coefficient;
use App\ComputationalIntelligence\Model\Network\Dense;
use App\ComputationalIntelligence\Model\Network\Dropout;
use App\ComputationalIntelligence\Model\Network\Hidden;
use App\ComputationalIntelligence\Model\Network\Neurons;
use App\ComputationalIntelligence\Model\Network\Sense;
use App\ComputationalIntelligence\Parameter;
use Symfony\Component\HttpFoundation\Request;

final readonly class HiddenLayerHttpProvider implements ComponentHttpProvider
{
    /** @return Hidden[] */
    public static function fromHttpRequest(Request $request): array
    {
        foreach ($request->toArray()['hiddens'] as $hidden) {
            $hiddens[] = new Hidden(
                new Dense(
                    Neurons::create($hidden['dense']['neurons']),
                    new Parameter($hidden['dense']['alpha']),
                    InitializerHttpProvider::fromHashmap($hidden)
                ),
                new Sense(
                    ActivationFunctionProvider::fromHashmap($hidden)
                ),
                new Dropout(
                    new Coefficient($hidden['dropout']['coefficient'])
                )
            );
        }

        if (!isset($hiddens)) {
            throw new MissingHiddenLayerException();
        }

        return $hiddens;
    }
}