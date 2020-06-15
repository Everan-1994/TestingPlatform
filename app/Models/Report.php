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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
	}

    public function device()
    {
        return $this->hasOne(Device::class, 'id', 'device_id');
	}

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
	}
}
