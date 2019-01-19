<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $guarded = ['id'];

    public function setOpenPeriodMinsAttribute($value) {
        $this->attributes['open_period_mins'] = Business::minutesCnt($value);
    }

    public function setClosePeriodMinsAttribute($value) {
        $this->attributes['close_period_mins'] = Business::minutesCnt($value);
    }

    public function getOpenAttribute() {
        if (null === $this->open_period_mins) {
            return null;
        }

        return date('h:ia', mktime(0, $this->open_period_mins));
    }

    public function getCloseAttribute() {
        if (null === $this->close_period_mins) {
            return null;
        }

        return date('h:ia', mktime(0, $this->close_period_mins));
    }

    public function business()
    {
    	return $this->belongsTo(Business::class);
    }
}
