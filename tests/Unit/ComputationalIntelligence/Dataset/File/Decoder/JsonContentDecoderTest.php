<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Dataset\File\Decoder;

use App\ComputationalIntelligence\Dataset\File\Decoder\Exception\ContentDecodingException;
use App\ComputationalIntelligence\Dataset\File\Decoder\Json\JsonContentDecoder;
use App\ComputationalIntelligence\Dataset\File\Decoder\Json\JsonContentDecoderArguments;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[CoversClass(JsonContentDecoder::class)]
#[CoversClass(JsonContentDecoderArguments::class)]
final class JsonContentDecoderTest extends TestCase
{
    private JsonContentDecoderArguments $arguments;

    public function setUp(): void
    {
        // see defaults
        $this->arguments = new JsonContentDecoderArguments();
    }

    #[DataProvider('contentProvider')]
    public function testCreateValidTimeSeries(string $content): void
    {
        $decoder = new JsonContentDecoder($this->arguments->jsonSerialize());
        $filename = 'test.json';
        $tmp = $this->saveFile($filename, $content);

        $uploadedFile = new UploadedFile($tmp, $filename, 'application/json', null, true);

        $timeSeries = $decoder->decode($uploadedFile);

        $this->assertCount(2, $timeSeries);
        $this->assertSame('2024-01-01 12:00', $timeSeries->key());
        $this->assertSame(600.00, $timeSeries->current());
    }

    public static function contentProvider(): Generator
    {
        yield 'six dates that will be sum to the minutes' => [
            json_encode([
                '2024-01-01 12:00:00' => 100.00,
                '2024-01-01 12:00:04' => 200.00,
                '2024-01-01 12:00:25' => 300.00,
                '2024-01-01 12:01:00' => 100.00,
                '2024-01-01 12:01:04' => 200.00,
                '2024-01-01 12:01:25' => 300.00,
            ], JSON_THROW_ON_ERROR),
        ];
    }

    public function testTryToCreateTimeSeriesWithNonDateTimeKeyFormattedDefinedInArguments(): void
    {
        $this->expectException(ContentDecodingException::class);

        $decoder = new JsonContentDecoder($this->arguments->jsonSerialize());
        $filename = 'test.json';
        $tmp = $this->saveFile($filename, json_encode(['some-non-datetime-key' => 100.00], JSON_THROW_ON_ERROR));

        $uploadedFile = new UploadedFile($tmp, $filename, 'application/json', null, true);

        $decoder->decode($uploadedFile);
    }

    public function testArgumentsDtoHasBeenIntendedForProperImplementation(): void
    {
        $this->assertSame(JsonContentDecoder::class, $this->arguments->intendedFor());
    }

    private function saveFile(string $filename, string $content): string
    {
        $tmp = tempnam(sys_get_temp_dir(), $filename);
        file_put_contents($tmp, $content, JSON_PRETTY_PRINT);

        return $tmp;
    }
}