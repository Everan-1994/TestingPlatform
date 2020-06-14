<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;

class ProjectController extends BaseController
{
    public function index()
    {
        $list = Project::query()->select('id', 'name')->latest()->get();

        if ($list->isNotEmpty()) {
            return $this->success($list);
        }

        return $this->success([], '没有数据');
    }
}
