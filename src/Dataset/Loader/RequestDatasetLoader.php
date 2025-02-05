<?php

declare(strict_types=1);

namespace App\Dataset\Loader;

use App\Dataset\TimeSeries;

final readonly class RequestDatasetLoader extends DatasetLoader
{
    public function load(): TimeSeries
    {
        return new TimeSeries();
    }
}