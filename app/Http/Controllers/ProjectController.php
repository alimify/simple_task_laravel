<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequestValidate;
use App\Models\Project;
use App\Traits\ResponseJsonAble;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ResponseJsonAble;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = Project::indexInstance([
            'show'  => $request->get('show'),
            'order' => [
                'title' => 'asc'
            ]
        ]);
    
        return $this->respond(true,'', $projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\ProjectRequestValidate  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequestValidate $request)
    {
        $project = new Project();
        $formData = $request->only($project->getFillable());
        $project->fill($formData)->save();

        return $this->respond(true,'Project Successfully saved!', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project->load('tasks');
        return $this->respond(true,'', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\ProjectRequestValidate  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequestValidate $request, Project $project)
    {
        $formData = $request->only($project->getFillable());
        $project->update($formData);

        return $this->respond(true,'Project Successfully saved!', $project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $result = Project::destroyInstance($id, $request->get('state'));
        return $this->respond($result);
    }
}
