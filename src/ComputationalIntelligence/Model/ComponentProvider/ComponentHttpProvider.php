<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use Symfony\Component\HttpFoundation\Request;

interface ComponentHttpProvider
{
    public static function fromHttpRequest(Request $request): mixed;
}