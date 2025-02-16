<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\Generator;

final readonly class RandomTimeSeriesGenerator
{
    private const string START_DATE = "2024-02-14 13:00:00";

    public function generate(int $length): string
    {
        $lastDate = new \DateTimeImmutable(self::START_DATE);
        $data = [];

        $lastValue = random_int(9000, 11000) / 10;
        $data[$lastDate->format('Y-m-d H:i:s')] = round($lastValue, 2);

        for ($i = 1; $i < $length; ++$i) {
            $lastDate = $lastDate->modify('+1 hour');
            $lastValue += (random_int(-20000, 20000) / 100);
            $data[$lastDate->format('Y-m-d H:i:s')] = abs(round($lastValue, 2));
        }

        $filename = 'data.json';
        file_put_contents($filename, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

        return $filename;
    }
}