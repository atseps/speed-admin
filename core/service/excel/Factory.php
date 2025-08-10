<?php
declare(strict_types=1);

namespace core\service\excel;

use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Exception;

class Factory
{

    public static function make($type, $spreadsheet)
    {
        if ($type === 'xlsx') {
            return app(Xlsx::class)->setSpreadsheet($spreadsheet);
        }

        if ($type === 'xls') {
            return new Xls($spreadsheet);
        }

        if ($type === 'csv') {
            return (new Csv($spreadsheet))->setUseBOM(true);
        }

        throw new Exception(sprintf('Type [%s] not support', $type));
    }
}
