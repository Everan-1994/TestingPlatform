<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	use HasDateTimeFormatter;

	protected $fillable = [
	    'user_id', 'upload_list_id', 'project_id', 'device_id', 'content'
    ];
}
