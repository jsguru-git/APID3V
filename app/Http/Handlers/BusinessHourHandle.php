<?php

namespace App\Http\Handlers;

use App\Models\BusinessHour;

class BusinessHourHandler
{
    public function update($id, $request)
    {
        $businessHour = BusinessHour::find($id);
        $businessHour->update($request->except('id'));

    	return $businessHour;
    }

}
