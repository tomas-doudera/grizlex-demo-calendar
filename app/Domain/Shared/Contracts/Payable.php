<?php

namespace App\Domain\Shared\Contracts;

interface Payable
{
    public function getPayableAmount(): float;

    public function getPayableDescription(): string;
}
