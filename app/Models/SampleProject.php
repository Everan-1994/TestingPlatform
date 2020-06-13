<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class SampleProject extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'sample_project';
    

}
