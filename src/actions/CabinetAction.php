<?php

namespace App\Actions;

use App\Models\Cabinet;

class CabinetAction {

    private $cabinet;

    public function __construct(Cabinet $cabinet)
    {
        $this->cabinet = $cabinet;
    }

    public function run(callable $fn)
    {
        if ($this->checkOperationIsAllowed()) {
            $this->cabinet->setOperationStatus(true);
            $fn();
            $this->cabinet->setOperationStatus(false);
        }
    }

    private function checkOperationIsAllowed(): bool
    {
        return !$this->cabinet->hasOperation();
    }

}