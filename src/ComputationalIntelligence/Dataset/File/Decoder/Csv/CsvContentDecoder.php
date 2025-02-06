<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder\Csv;

use App\ComputationalIntelligence\Dataset\File\Decoder\ContentDecoderStrategy;
use App\Math\RealNumber;
use SplFileObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\ComputationalIntelligence\Dataset\TimeSeries;

/** @property CsvFileContentDecoderArguments $arguments */
final readonly class CsvContentDecoder extends ContentDecoderStrategy
{
    private const int RULES = SplFileObject::READ_CSV
        | SplFileObject::SKIP_EMPTY
        | SplFileObject::READ_AHEAD
        | SplFileObject::DROP_NEW_LINE;


    public function __construct(
        array $arguments,
    ) {
        parent::__construct(
            new CsvFileContentDecoderArguments(
                separator: $arguments['separator'],
                dateFormat: $arguments['dateFormat'],
                valueFormat: $arguments['valueFormat'],
                containsHeader: filter_var($arguments['containsHeader'], FILTER_VALIDATE_BOOLEAN),
            )
        );
    }

    public function decode(UploadedFile $file): TimeSeries
    {
        return $this->handleDecoding(
            function () use ($file): TimeSeries {
                $result = new TimeSeries();

                $splFile = new SplFileObject($file->getRealPath());
                $splFile->setFlags(self::RULES);
                $splFile->setCsvControl($this->arguments->separator);
                $headers = null;

                foreach ($splFile as $i => /** @var string[] $row */ $row) {

                    if ($this->isHeaderRow($i)) {
                        $headers = $row;

                        continue;
                    }

                    if ($headers === $row) {
                        continue;
                    }

                    $key = $this->parseDateTime($row[0]);

                    if (!$key) {
                        continue;
                    }

                    $value = (float) sprintf($this->arguments->valueFormat, $row[1]);
                    $currentValue = $this->getCurrentValue($result, $key);
                    $result->offsetSet($key, round($currentValue + $value, RealNumber::PRECISION));
                }

                return $result;
            }
        );
    }

    private function isHeaderRow(int $index): bool
    {
        return $this->arguments->containsHeader
            && 0 === $index;
    }
}