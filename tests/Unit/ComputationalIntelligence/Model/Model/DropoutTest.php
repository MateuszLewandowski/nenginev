<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\Network\Coefficient;
use App\ComputationalIntelligence\Model\Network\Dropout;
use App\Math\Tensor\Matrix;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Dropout::class)]
final class DropoutTest extends TestCase
{
    #[DataProvider('dropoutProvider')]
    public function testFeedForward(Matrix $input, Dropout $dropout, array $expected): void
    {
        $result = $dropout->feedForward($input);

        foreach ($result->primitive() as $row) {
            foreach ($row as $value) {
                $this->assertContains(round($value, 3), $expected, sprintf('Dropout value not found %s in expected values ', $value));
            }
        }
    }

    public static function dropoutProvider(): Generator
    {
        yield [
            'input' => Matrix::create([
                [1.0, 1.0],
                [1.0, 1.0],
            ]),
            'dropout' => new Dropout(
                new Coefficient(.25)
            ),
            'expected' => [round(4/3, 3), 0.0],
        ];
    }
}