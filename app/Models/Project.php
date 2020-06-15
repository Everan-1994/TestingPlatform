<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	use HasDateTimeFormatter;

    public function samples()
    {
        return $this->belongsToMany(Sample::class, 'sample_project', 'project_id', 'sample_id');
	}
}
