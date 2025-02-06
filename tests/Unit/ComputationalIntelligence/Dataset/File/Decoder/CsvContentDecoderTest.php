<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Dataset\File\Decoder;

use App\ComputationalIntelligence\Dataset\File\Decoder\Csv\CsvContentDecoder;
use App\ComputationalIntelligence\Dataset\File\Decoder\Csv\CsvFileContentDecoderArguments;
use App\ComputationalIntelligence\Dataset\File\Decoder\Exception\ContentDecodingException;
use App\ComputationalIntelligence\Dataset\File\Decoder\Json\JsonContentDecoder;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[CoversClass(CsvContentDecoder::class)]
#[CoversClass(CsvFileContentDecoderArguments::class)]
#[CoversClass(ContentDecodingException::class)]
final class CsvContentDecoderTest extends TestCase
{
    private CsvFileContentDecoderArguments $arguments;

    public function setUp(): void
    {
        // see defaults
        $this->arguments = new CsvFileContentDecoderArguments();
    }

    #[DataProvider('contentProvider')]
    public function testCreateValidTimeSeries(array $content): void
    {
        $decoder = new CsvContentDecoder($this->arguments->jsonSerialize());
        $filename = 'test.csv';
        $tmp = $this->saveFile($filename, $content);

        $uploadedFile = new UploadedFile($tmp, $filename, 'application/csv', null, true);
        $timeSeries = $decoder->decode($uploadedFile);

        $this->assertSame(2, $timeSeries->count());
        $this->assertSame('2024-01-01 12:00', $timeSeries->key());
        $this->assertSame(600.00, $timeSeries->current());
    }

    public static function contentProvider(): Generator
    {
        yield 'six dates that will be sum to the minutes' => [
            [
                'date' => 'value',
                '2024-01-01 12:00:00' => 100.00,
                '2024-01-01 12:00:04' => 200.00,
                '2024-01-01 12:00:25' => 300.00,
                '2024-01-01 12:01:00' => 100.00,
                '2024-01-01 12:01:04' => 200.00,
                '2024-01-01 12:01:25' => 300.00,
            ],
        ];
    }

    public function testTryToCreateTimeSeriesWithNonDateTimeKeyFormattedDefinedInArguments(): void
    {
        $this->expectException(ContentDecodingException::class);

        $decoder = new JsonContentDecoder($this->arguments->jsonSerialize());
        $filename = 'test.csv';
        $tmp = $this->saveFile($filename, ['some-non-datetime-key' => 100.00]);

        $uploadedFile = new UploadedFile($tmp, $filename, 'application/json', null, true);

        $decoder->decode($uploadedFile);
    }

    public function testArgumentsDtoHasBeenIntendedForProperImplementation(): void
    {
        $this->assertSame(CsvContentDecoder::class, $this->arguments->intendedFor());
    }

    private function saveFile(string $filename, array $content): string
    {
        $tmp = tempnam(sys_get_temp_dir(), $filename);

        $stream = fopen($tmp, 'wb');
        foreach ($content as $key => $value) {
            fputcsv($stream, [$key, $value], ',');
        }
        fclose($stream);

        return $tmp;
    }
}