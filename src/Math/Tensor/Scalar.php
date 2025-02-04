<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\RealNumber;
use App\Math\Values;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;

final readonly class Scalar extends Tensor
{
    public function __construct(
        Values $values,
    ) {
        parent::__construct($values, TensorType::SCALAR);
    }

    public static function create(float|array $input): Scalar
    {
        return new self(Values::create(is_float($input) ? [$input] : $input));
    }

    public function isCompatible(Tensor $tensor): bool
    {
        return true;
    }

    public function values(): Values
    {
        return $this->values;
    }

    public function size(): int
    {
        return 1;
    }

    public function jsonSerialize(): array
    {
        return [
            'scalar' => current($this->values->data())
        ];
    }

    #[WhenNot('production')]
    /** @retrun Scalar 1.0 */
    public static function random(): self
    {
        return new self(Values::create([RealNumber::create(1.0)->value]));
    }

    public function dimension(): int
    {
        return 1;
    }

    public function primitive(): float
    {
        return current($this->values->data());
    }
}