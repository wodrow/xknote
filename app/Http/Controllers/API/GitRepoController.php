<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\GitRepoModel;

class GitRepoController extends Controller
{
    public $model = null;
    public function __construct()
    {
        $this->model = new GitRepoModel();
    }

    public function initClone(Request $request)
    {
        if (
            !$request->has('path') ||
            !$request->has('repo') ||
            !$request->has('initOrClone')
        ) {
            return response(
                ['error' => 'Parameter not found. (path,repo,initOrClone)'],
                400
            );
        }
        $id = $request->user()->id;
        $path = $request->path;
        $repo = $request->repo;
        $code = 500;
        if ($request->initOrClone === 'init') {
            $code = $this->model->init($path, $id, $repo);
        } else {
            $code = $this->model->clone($path, $id, $repo);
        }
        return ['error' => $code === 200 ? true : false];
    }

    public function config(Request $request)
    {
        if (
            !$request->has('path') ||
            !$request->has('name') ||
            !$request->has('email')
        ) {
            return response(
                ['error' => 'Parameter not found. (path,name,email)'],
                400
            );
        }
        $id = $request->user()->id;
        $path = $request->path;
        $this->model->config($path, $id, [
            'git_name' => $request->name,
            'git_email' => $request->email
        ]);
        return ['error' => false];
    }

    public function pull(Request $request)
    {
        if (!$request->has('path')) {
            return response(['error' => 'Parameter not found. (path)'], 400);
        }
        $id = $request->user()->id;
        $this->model->pull($request->path, $id);
        return ['error' => false];
    }

    public function push(Request $request)
    {
        if (!$request->has('path')) {
            return response(['error' => 'Parameter not found. (path)'], 400);
        }
        $id = $request->user()->id;
        $force = $request->has('force') && $request->force == 'true';
        $code = $this->model->push($request->path, $id, $force);
        if ($code === 202) {
            return ['error' => false, 'message' => 'Everything up-to-date'];
        }
        return ['error' => false];
    }
}