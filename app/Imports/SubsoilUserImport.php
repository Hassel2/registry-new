<?php

namespace App\Imports;

use App\Models\SubsoilUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubsoilUserImport implements ToCollection, WithStartRow {
	use Importable;

	public function collection(Collection $rows) {
		
		foreach ($rows as $row) {
			$creationArray = [
				'company' => trim($row[0]),
				'address' => $row[1],
				'INN' => $row[2],
				'OKPO' => $row[3],
				'OKATO' => $row[4],
				'OGRN' => $row[7],
				'comments' => $row[8],
				'status' => $row[9],
			];

			$validator = Validator::make($creationArray, SubsoilUser::rules());

			if ($validator->fails()) {
				continue;
			}

			SubsoilUser::create($creationArray);
		}

		foreach ($rows as $row) {
			if (trim($row[10]) == 'Самостоятельные' || 
				trim($row[10]) == '') continue;

			/* $subsoil_user = DB::table('subsoil_users') */
				/* ->where('company', '=', trim($row[0])) */
				/* ->first(); */

			$management_company = DB::table('subsoil_users')
				->select('id')
				->where('company', '=', trim($row[10]))
				->get();//[0]
				//->id;
			
			if (count($management_company) == 0) continue;
			$management_company = $management_company[0]->id;

			SubsoilUser::where('company', '=', trim($row[0]))->update(['management_company' => $management_company]);
			
			/* $subsoil_user->management_company = $management_company; */
			/* $subsoil_user->save(); */
			/* $subsoil_user->update(['management_company' => $management_company]); */
			
		}
	}

	public function startRow(): int
	{
		return 2;
	}
}
