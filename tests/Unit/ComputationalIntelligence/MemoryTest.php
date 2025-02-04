<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence;

use App\ComputationalIntelligence\Memory;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Vector;
use App\Tests\Unit\ComputationalIntelligence\Exception\MissingMemoryAddressException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class MemoryTest extends TestCase
{
    private Memory $memory;

    public function setUp(): void
    {
        $this->memory = new Memory();
    }

    public function testSimpleMemory(): void
    {
        $id = Uuid::v4();
        $this->memory->set($id, Matrix::random());

        $this->assertInstanceOf(Matrix::class, $this->memory->get($id));
    }

    public function testRemoveFromMemory(): void
    {
        $id = Uuid::v4();
        $this->memory->set($id, Matrix::random());
        $this->memory->remove($id);

        $this->expectException(MissingMemoryAddressException::class);
        $this->memory->get($id);
    }

    public function testGetFirstAndLastFromMemory(): void
    {
        $id = Uuid::v4();
        $this->memory->push($id, Scalar::random());
        $this->memory->push($id, Vector::random());
        $this->memory->push($id, Matrix::random());

        $this->assertInstanceOf(Scalar::class, $this->memory->first($id));
        $this->assertInstanceOf(Matrix::class, $this->memory->last($id));
    }
}