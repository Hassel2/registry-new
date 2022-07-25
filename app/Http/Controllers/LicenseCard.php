<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LicenseCard extends Controller
{
	public function get(Request $request, $id) {
		$prevLicense = DB::table('licenses')
			->select(DB::raw('id, concat(series, number, view) as number'));

		$federalLicensingAuthority = DB::table('federal_authorities')
			->select('*');

		$result = DB::table('licenses as lic')
			->select(DB::raw(
				'lic.id as id,
				concat(lic.series, lic.number, lic.view) as number,
				FLA.name as federal_licensing_authority,
				status,
				prev_license as prev_license_id,
				prev.number as prev_license,
				receiving_date,
				cancellation_date,
				expiration_date'))
			->leftJoinSub($prevLicense, 'prev', function($join) {
				$join->on('lic.prev_license', '=', 'prev.id');
			})
			->leftJoinSub($federalLicensingAuthority, 'fla', function($join) {
				$join->on('lic.federal_licensing_authority', '=', 'fla.id');
			})
			->where('lic.id', '=', $id)
			->get();

		$fieldId = DB::table('field_composition')
			->select('field')
			->where('license_area', '=', $result[0]->id)
			->get();

		if (count($fieldId) == 0)
			return $this->sendResponse($result->toArray());

		$fieldId = $fieldId[0]->id;

		$developmentDegree = DB::table('development_degree')
			->select('*');

		$field = DB::table('fields')
			->select(DB::raw(
				'fields.id,
				name,
				dd.degree'))
			->leftJoinSub($developmentDegree, 'dd', function($join) {
				$join->on('fields.development_degree', '=', 'dd.id');
			})
			->where('fields.id', '=', $fieldId)
			->get();

		$result[] = ['field' => $field];

		return $this->sendResponse($result->toArray());
	}
}
