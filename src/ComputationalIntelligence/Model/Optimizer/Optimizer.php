<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Optimizer;

use App\Math\RealNumber;
use App\Math\Tensor\Tensor;
use Symfony\Component\Uid\Uuid;

interface Optimizer extends \JsonSerializable
{
    public function initialize(Tensor $tensor): void;
    public function optimize(Uuid $id, Tensor $gradient, RealNumber $epoch): Tensor;
}