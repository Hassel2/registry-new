<?php

namespace App\Imports;

use App\Models\License;
use App\Models\LicenseArea;
use App\Models\FederalAuthority;
use App\Models\SubsoilUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class LicenseImport implements ToCollection, WithStartRow {
	use Importable;

	public function collection(Collection $rows) {

		foreach ($rows as $row) {
			########################
			# license_areas import #
			########################
			$subsoil_user = DB::table('subsoil_users')
				->select('id')
				->where('company', '=', trim($row[0]))
				->get();//[0]
				//->id;
			
			if (count($subsoil_user) == 0) {
				SubsoilUser::create([
					'company' => trim($row[0]),
					'address' => null,
					'INN' => null,
					'OKPO' => null,
					'OKATO' => null,
					'OGRN' => null,
					'comments' => null,
					'status' => null,
				]);
				continue; 
			}
			
			$subsoil_user = $subsoil_user[0]->id;

			$creationArray = [
				'name' => trim($row[1]),
				'subsoil_user' => $subsoil_user
			];

			$validator = Validator::make($creationArray, LicenseArea::rules());

			if (!$validator->fails()) {
				LicenseArea::create($creationArray);
			}

			##############################
			# federal_authotities import #
			##############################
			$creationArray = [
				'name' => trim($row[13])
			];

			$validator = Validator::make($creationArray, FederalAuthority::rules());

			if (!$validator->fails()) {
				FederalAuthority::create($creationArray);
			}
		}

		/* echo "Number of unknown companies: ".$counter.PHP_EOL; */

		###################
		# licenses import #
		###################
		foreach ($rows as $row) {
			$license_area = DB::table('license_areas')
				->select('id')
				->where('name', '=', trim($row[1]))
				->get();//[0]->id;

			if (count($license_area) > 0) $license_area = $license_area[0]->id;
			else $license_area = null;

			$federal_authority = DB::table('federal_authorities')
				->select('id')
				->where('name', '=', trim($row[13]))
				->get();//[0]->id;

			if (count($federal_authority) > 0) $federal_authority = $federal_authority[0]->id;
			else $federal_authority = null;

			$creationArray = [
				'series' => trim($row[2]),
				'number' => trim($row[3]),
				'view' => trim($row[4]),
				'status' => trim($row[8]),
				'license_area' => $license_area, 
				'receiving_date' => Date::excelToDateTimeObject($row[10])->format('Y-m-d'), #Y-m-d
				'cancellation_date' => Date::excelToDateTimeObject($row[12])->format('Y-m-d'),
				'expiration_date' => Date::excelToDateTimeObject($row[11])->format('Y-m-d'),
				'federal_licensing_authority' => $federal_authority,
			];

			$validator = Validator::make($creationArray, License::rules());

			if ($validator->fails()) {
				continue;
			}

			License::create($creationArray);
		}

		foreach ($rows as $row) {
			$prev_license = DB::table('licenses') 
				->select('id')
				->where(DB::raw('CONCAT(series, number, view)'), '=', $row[5])
				->get();

			if (count($prev_license) == 0) continue;
			$prev_license = $prev_license[0]->id;

			License::where('series', '=', trim($row[2]))
				->where('number', '=', trim($row[3]))
				->where('view', '=', trim($row[4]))
				->update(['prev_license' => $prev_license]);	
		}
	}

	public function startRow(): int
	{
		return 2;
	}
}
