<?php

namespace App;
use Illuminate\Support\Facades\DB;

class PipelinesTasks extends \Eloquent
{
    protected $table = 'pipelines_tasks';

    public static function deleteAllPipelinesTasksByPipelineId($id) {
        return DB::table('pipelines_tasks')->where('pipeline_id', $id)->delete();
    }
}
