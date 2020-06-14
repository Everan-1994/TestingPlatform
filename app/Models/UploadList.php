<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class UploadList extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'upload_lists';

    protected $fillable = ['user_id', 'ss_name', 'sample_num'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'upload_list_id', 'id');
    }

}
