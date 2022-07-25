<?php

namespace App\Imports;

use App\Models\Field;
use App\Models\DevelopmentDegree;
use App\Models\FieldPosition;
use App\Models\FieldComposition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FieldImport implements ToCollection, WithStartRow {
	use Importable;

	public function collection(Collection $rows) {
		
		#############################
		# development_degree import #
		#############################
		foreach ($rows as $row) {
			$creationArray = [
				'degree' => trim($row[2])
			];

			$validator = Validator::make($creationArray, DevelopmentDegree::rules());

			if ($validator->fails()) {
				continue;
			}

			DevelopmentDegree::create($creationArray);
		}

		#################
		# fields import #
		#################
		foreach ($rows as $row) {
			$developmentDegree = DB::table('development_degree')
				->select('id')
				->where('degree', '=', $row[2])
				->get()[0]->id;

			$creationArray = [
				'name' => rtrim(explode('(', $row[3])[0]),
				'development_degree' => $developmentDegree,
			];

			$validator = Validator::make($creationArray, Field::rules());

			if ($validator->fails()) {
				continue;
			}

			Field::create($creationArray);
		}
		

		foreach ($rows as $row) {
			#########################
			# field_position import #
			#########################
			$subject_name = trim($row[1]);
			if ($subject_name == 'Субъект не указан') continue;

			$subject = DB::table('rf_subjects')
				->select('id')
				->where('short_name', '=', $subject_name)
				->get()[0]->id;

			$field = DB::table('fields')
				->select('id')
				->where('name', '=', rtrim(explode('(', $row[3])[0]))
				->get()[0]->id;

			$creationArray = [
				'field' => $field,
				'subject' => $subject,
			];

			$validator = Validator::make($creationArray, FieldPosition::rules());

			if (!$validator->fails()) {
				FieldPosition::create($creationArray);
			}

			############################
			# field_composition import #
			############################
			$field_id = DB::table('fields')
				->select('id')
				->where('name', '=', rtrim(explode('(', $row[3])[0]))
				->get()[0]
				->id;

			$license_areas = explode('\r\n', $row[4]);
			foreach($license_areas as $license_area) {
				$license_area = rtrim(explode('(', $license_area)[0]);

				$license_area_id = DB::table('license_areas')
					->select('id')
					->where('name', '=', trim($license_area))
					->get();
				
				if (count($license_area_id) == 0) continue;
				$license_area_id = $license_area_id[0]->id;	

				$creationArray = [
					'license_area' => $license_area_id,
					'field' => $field_id,
				];

				$validator = Validator::make($creationArray, FieldComposition::rules());

				if ($validator->fails()) {
					continue;
				}

				FieldComposition::create($creationArray);
			}
		}
	}

	public function startRow(): int
	{
		return 2;
	}
}
