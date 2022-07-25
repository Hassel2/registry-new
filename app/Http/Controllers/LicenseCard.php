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

		$fieldId = $fieldId[0]->field;

		$developmentDegree = DB::table('development_degree')
			->select('*');

		$field = DB::table('fields')
			->select(DB::raw(
				'fields.id,
				name,
				dd.degree as degree'))
			->leftJoinSub($developmentDegree, 'dd', function($join) {
				$join->on('fields.development_degree', '=', 'dd.id');
			})
			->where('fields.id', '=', $fieldId)
			->get();

		$rf_subjects = DB::table('rf_subjects')
			->select('fd.id', 'rf_subjects.name as subject', 'fd.name as federal_district')
			->leftJoinSub(DB::table('federal_districts')->select('*'), 'fd', function($join) {
				$join->on('fd.id', '=', 'rf_subjects.federal_district');
			});

		$field_position = DB::table('field_position as fp')
			->select('fp.id', 'rs.subject', 'rs.federal_district')
			->leftJoinSub($rf_subjects, 'rs', function($join) {
				$join->on('rs.id', '=', 'fp.subject');
			})
			->where('field', '=', $fieldId)
			->get();

		$field[0]->position = $field_position;

		$result[0]->field = $field;

		return $this->sendResponse($result->toArray());
	}
}
