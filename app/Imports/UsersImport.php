<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) {
      $isExist = User::select('id')->where(['parent_id'=>0,'mobile_no'=> $row['mobile_no']])->count();
     						if($isExist == 0){
        return new User([
            'first_name'     => $row['first_name'],
            'last_name'    => $row['last_name'],
            'mobile_no'    => $row['mobile_no'],
			'organization'    => $row['organization'],
            'login_type'    => 2,
            'device_type'    => 3,

        ]);

}
    }
}
