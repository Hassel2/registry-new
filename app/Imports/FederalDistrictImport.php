<?php

namespace App\Imports;

use App\Models\FederalDistrict;
use App\Models\RfSubject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class FederalDistrictImport implements ToCollection {
	use Importable;

	public function collection(Collection $rows) {
		
		$isFisrt = true;

		############################
		# federal_districts import #
		############################
		foreach ($rows as $row) {
			if ($isFisrt) {
				$isFisrt = false; # To skip the heading row
				continue;
			}	

			$creationArray = [
				'name' => trim($row[2])
			];

			$validator = Validator::make($creationArray, FederalDistrict::rules());

			if ($validator->fails()) {
				continue;
			}

			FederalDistrict::create($creationArray);

		}

		######################
		# rf_subjects import #
		######################
		$isFisrt = true;

		foreach ($rows as $row) {
			if ($isFisrt) {
				$isFisrt = false; # To skip the heading row
				continue;
			}	

			$federalDistrict = DB::table('federal_districts')
								->select('id')
								->where('name', '=', trim($row[2]))
								->get()[0]->id;

			$creationArray = [
				'name' => trim($row[0]),
				'short_name' => trim($row[1]),
				'federal_district' => $federalDistrict,
			];

			$validator = Validator::make($creationArray, RfSubject::rules());

			if ($validator->fails()) {
				continue;
			}
			
			RfSubject::create($creationArray);
		}
	}
}
