<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequestValidate;
use App\Models\Task;
use App\Traits\ResponseJsonAble;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ResponseJsonAble;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tasks = Task::indexInstance([
            'show'  => $request->get('show'),
            'order' => [
                'priority' => 'asc'
            ]
        ]);

        return $this->respond(true,'',$tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequestValidate $request)
    {
     
        $task = new Task();
        $formData = $request->only($task->getFillable());
        $task->fill($formData)->save();

        return $this->respond(true,'Task Successfully saved!', $task);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return $this->respond(true, '', $task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequestValidate $request, $id)
    {
        switch ($id) {
            case 'batch':
                $res = Task::batchUpdate($request->get('batch_update'));
                $task = $res;
                break;
            default:
                $task = Task::findOrFail($id);
                $formData = $request->only($task->getFillable());
                $res = $task->update($formData);
                break;
        }

        return $this->respond($res,'Task Successfully saved!', $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,  $id)
    {
        $result = Task::destroyInstance( $id, $request->get('state'));

        return $this->respond($result);
    }
}
