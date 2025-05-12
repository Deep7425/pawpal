<?php
   
namespace App\Imports;
   
use App\Models\UsersOnlineData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
    
class UsersOnlineDataImport implements ToModel, WithHeadingRow {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) {
		$dob = null;
		if(!empty($row['dob']) && is_numeric($row['dob'])){
			$dob = strtotime("- ".$row['dob']." year", strtotime("01-01-".date('Y')));
		}
		$created_at = date("Y-m-d H:i:s", time());
		if(isset($row['created_at']) && !empty($row['created_at'])) {
			$created_at = date("Y-m-d H:i:s", strtotime($row['created_at']));
		}
		$no_exists = UsersOnlineData::where(['mobile'=>trim($row['mobile'])])->count();
		if($no_exists == 0) {
			return new UsersOnlineData([
				'name'     => $row['name'],
				'gender'     => $row['gender'],
				'dob'     => $row['dob'],
				'title'    => $row['title'],
				'type'    => $row['type'],
				'mobile'    => $row['mobile'],
				'email'    => $row['email'],
				'com_name'    => $row['com_name'],
				'url'    => $row['url'],
				'data_type'    => $row['data_type'],
				'bp_s'    => $row['bp_s'],
				'bp_d'    => $row['bp_d'],
				'sugar'    => $row['sugar'],
				'weight'    => $row['weight'],
				'dob'    => $dob,
				'weight' => $row['weight'],
				'created_at'    => $created_at,
				'updated_at'    => $created_at,
				'organization_id'    => @$row['organization_id'],
			]);
		}
    }
}
