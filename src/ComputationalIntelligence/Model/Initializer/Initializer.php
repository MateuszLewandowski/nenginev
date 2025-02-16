<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Initializer;

use App\ComputationalIntelligence\Model\Network\Neurons;
use App\Math\Tensor\Matrix;

interface Initializer extends \JsonSerializable
{
    public function initialize(Neurons $rows, Neurons $columns): Matrix;
}