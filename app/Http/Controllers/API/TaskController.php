<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        // Sorting
        if ($request->has('sort')) {
            $sortField = $request->input('sort');
            $query->orderBy($sortField);
        }

        // Categorizing
        if ($request->has('category')) {
            $category = $request->input('category');
            $query->where('category', $category);
        }

        $feedback = $query->paginate(10);
        $data = array(
            'task' => TaskResource::collection($feedback)
        );
        return $this->response(true, $data, 'Feedback Data .....');
    }

    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'title' => 'required',
                'status' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = Auth::user();
            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->contents;
            $task->due_date = $request->due;
            $task->status = $request->status;
            $task->save();
            $data = array(
                'task' => $task
            );
            return  $this->response(true, $data,'Task Inserted Successfully');

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $task = Task::where('id',$id)->first();
            $data = array(
                'task' => $task
            );
            return  $this->response(true, $data,'Task Get Successfully');

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request,$id)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'title' => 'required',
                'status' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = Auth::user();
            $task = Task::where('id',$id)->first();
            $task->title = $request->title;
            $task->description = $request->contents;
            $task->due_date = $request->due;
            $task->status = $request->status;
            $task->save();
            $data = array(
                'task' => $task
            );
            return  $this->response(true, $data,'Task Updated Successfully');

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $task = Task::where('id',$id)->first();
            $task->delete();
            $data = array(
                'task' => $task
            );
            return  $this->response(true, $data,'Task Deleted Successfully');

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
