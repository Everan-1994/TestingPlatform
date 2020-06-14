<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasDateTimeFormatter;

    public function device()
    {
        return $this->hasOne(Device::class, 'id', 'device_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'sample_project', 'sample_id', 'project_id');
    }
}
