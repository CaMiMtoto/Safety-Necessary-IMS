<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
class CustomValueBinder extends DefaultValueBinder implements WithCustomValueBinder
{
    /**
     * Override bindValue method with the correct signature for PHP 8.
     */
    public function bindValue($cell,  $value): bool
    {
        // Here you can handle custom binding if needed
        return parent::bindValue($cell, $value);
    }
}
