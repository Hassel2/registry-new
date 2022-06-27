<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class Import implements WithMultipleSheets, WithChunkReading, WithProgressBar {
	use Importable;

	public function sheets(): array {
        return [
           'Недропользователь (компания)' => new SubsoilUserImport(),
           'Субъект РФ' => new FederalDistrictImport(),
           'Лицензия' => new LicenseImport(),
           'Месторождение' => new FieldImport(),
        ];
    }

	public function chunkSize(): int
	{
		return 500;
	}
}

