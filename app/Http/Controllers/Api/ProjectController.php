<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        $list = Project::query()
            ->when($request->filled('sample_id'), function ($query) use ($request) {
                $query->whereHas('samples', function ($sql) use ($request) {
                    $sql->where('sample_id', '=', $request->input('sample_id'));
                });
            })
            ->select('id', 'name')
            ->latest()
            ->get();

        if ($list->isNotEmpty()) {
            return $this->success($list);
        }

        return $this->success([], '没有数据');
    }
}
