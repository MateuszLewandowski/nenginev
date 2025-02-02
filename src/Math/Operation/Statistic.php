<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\RealNumber;
use App\Math\Tensor\Tensor;

interface Statistic
{
    public function mean(): Tensor|float;

    /**
     * @see https://pogotowiestatystyczne.pl/slowniki/wariancja/
     * @see https://www.skarbiec.pl/slownik/wariancja/
     */
    public function variance(?RealNumber $mean = null): Tensor;

    /** @see https://zpe.gov.pl/a/przeczytaj/DQzJKaV85 */
    public function quantile(RealNumber $q): Tensor;
}
