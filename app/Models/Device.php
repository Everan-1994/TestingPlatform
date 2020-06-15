<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
	use HasDateTimeFormatter;

    public function samples()
    {
        return $this->belongsToMany(Sample::class, 'sample_device', 'device_id', 'sample_id');
    }
}
