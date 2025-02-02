<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\RealNumber;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;

final readonly class Scalar extends Tensor
{
    public function __construct(
        private RealNumber $value,
    ) {
        parent::__construct(TensorType::SCALAR);
    }

    public static function create(float|array $input): Scalar
    {
        return new self(RealNumber::create($input));
    }

    public function isCompatible(Tensor $tensor): bool
    {
        return true;
    }

    public function value(): RealNumber
    {
        return $this->value;
    }

    public function primitive(): float
    {
        return $this->value->value;
    }

    public function size(): int
    {
        return 1;
    }

    public function jsonSerialize(): array
    {
        return [
            'scalar' => $this->value->jsonSerialize()
        ];
    }

    #[WhenNot('production')]
    public static function random(): self
    {
        return new self(RealNumber::create(7.0));
    }
}