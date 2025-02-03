<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math;

use App\Math\Exception\MissingValuesException;
use App\Math\Values;
use PHPUnit\Framework\TestCase;

final class ValuesTest extends TestCase
{
    public function testCreateValuesWithVectorPrimitiveData(): void
    {
        $input = [0.1, .41, -.12, .33, 1.0];
        $values = Values::create($input);

        $this->assertSame(5, $values->columns());
        $this->assertSame(1, $values->rows());
        $this->assertSame(5, $values->size());
        $this->assertSame($input, $values->data());
    }

    public function testTryToCreateValuesWithEmptyInput(): void
    {
        $this->expectException(MissingValuesException::class);

        Values::create([]);
    }

    public function testMergeTwoValuesLists(): void
    {
        $first = Values::create([1]);
        $second = Values::create([2]);

        $values = $first->merge($second);
        $this->assertSame(2, $values->size());
    }

    public function testGetValueFromTheCell(): void
    {
        $values = Values::create([
            [1, 2, 3],
            [4, 5, 6],
        ]);

        $this->assertSame(6.0, $values->cell(1, 2));
    }

    public function testConvertValuesToTheReportRepresentation(): void
    {
        $list = [1.0, 2.5];

        $this->assertSame(
            ['values' => $list],
            Values::create($list)->jsonSerialize()
        );
    }

    public function testGetRowFromValues(): void
    {
        $expected = [1.0, 2.0, 3.0];
        $values = Values::create([
            $expected,
            [4.0, 5.0, 6.0],
        ]);

        $this->assertSame($expected, $values->row(0)->data());
    }

    public function testGetColumnFromValues(): void
    {
        $expected = [2.0, 5.0];
        $values = Values::create([
            [1.0, 2.0],
            [3.0, 5.0],
        ]);

        $this->assertSame($expected, $values->column(1)->data());
    }

    public function testGetSliceFromValues(): void
    {
        $expected = [1.0, 2.0, 3.0];
        $values = Values::create([
            [4.0, 5.0, 6.0],
            $expected,
            [4.0, 5.0, 6.0],
        ]);

        $this->assertSame([$expected], $values->slice(1, 1)->data());
    }

    public function testSplitValuesIntoChunks(): void
    {
        $values = [1.0, 2.0, 3.0, 4.0, 5.0, 6.0];
        $chunks = Values::create($values)->chunk(2);

        $this->assertCount(3, $chunks);
        $this->assertSame([1.0, 2.0], $chunks[0]->data());
        $this->assertSame([3.0, 4.0], $chunks[1]->data());
        $this->assertSame([5.0, 6.0], $chunks[2]->data());
        $this->assertContainsOnlyInstancesOf(Values::class, $chunks);
    }

    public function testMutateValues(): void
    {
        $values = Values::create([1.0, 2.0, 3.0]);
        $values->mutate(static fn(float $value): float => $value ** 2);

        $this->assertSame([1.0, 4.0, 9.0], $values->data());
    }
}