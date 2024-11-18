<?php

namespace App\Imports;

use App\Models\City;
use App\Models\State;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StateImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        foreach ($collection as $key => $value) {
            # code...
;
            $state=State::where(['name'=>$value['state']])->first();
            if(!empty($value['city'])){
                City::create([
                    'name' => $value['city'],
                    'state_id'=>$state->id,
                ]);
            }


        }
    }
}
