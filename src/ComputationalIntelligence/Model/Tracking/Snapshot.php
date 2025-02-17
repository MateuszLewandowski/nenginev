<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Tracking;

use App\Math\RealNumber;

final readonly class Snapshot
{
    public function __construct(
        public RealNumber $epoch,
        public RealNumber $step,
        public RealNumber $cost,
        public RealNumber $loss,
    ) {}
}