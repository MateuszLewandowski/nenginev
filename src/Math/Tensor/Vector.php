<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\Operation\Reducible;
use App\Math\Values;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;

final readonly class Vector extends Tensor implements
    Reducible
{
    public function __construct(
        Values $values,
    ) {
        parent::__construct($values, TensorType::VECTOR);
    }

    public static function create(float|array $input): Vector
    {
        return new self(Values::create(is_float($input) ? [$input] : $input));
    }

    public function isCompatible(Tensor $tensor): bool
    {
        return match ($tensor->type()) {
            TensorType::SCALAR => true,
            TensorType::MATRIX, TensorType::VECTOR => $this->dimension() === $tensor->dimension(),
        };
    }

    public function size(): int
    {
        return $this->values->size();
    }

    public function jsonSerialize(): array
    {
        return [
            'vector' => $this->values->data(),
        ];
    }

    public function values(): Values
    {
        return $this->values;
    }

    public function dimension(): int
    {
        return $this->size();
    }

    public function primitive(): array
    {
        return $this->values->data();
    }

    #[WhenNot('production')]
    /* @return Vector [1.0, 2.0] */
    public static function random(): Vector
    {
        return self::create([1.0, 2.0]);
    }

    public function transpose(): self
    {
        $data = $this->primitive();
        array_walk_recursive($data, static function (float|array &$value): void {
            $value = is_array($value) ? $value[0] : [$value];
        });

        return self::create($data);
    }

    public function min(): Scalar
    {
        return Scalar::create(min($this->primitive()));
    }

    public function max(): Scalar
    {
        return Scalar::create(max($this->primitive()));
    }

    public function mean(): Scalar
    {
        return Scalar::create($this->sum()->divide(Scalar::create($this->size()))->primitive());
    }

    public function sum(): Scalar
    {
        return Scalar::create(array_sum($this->primitive()));
    }

    public function product(): Scalar
    {
        return Scalar::create(array_product($this->primitive()));
    }
}