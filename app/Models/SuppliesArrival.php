<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class SuppliesArrival extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'supplies_arrivals';
    

}
