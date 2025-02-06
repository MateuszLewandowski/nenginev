<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder;

interface ContentDecoderArguments
{
    public function intendedFor(): string;
}