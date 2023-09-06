<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Controller as BaseController;
use Laravolt\Crud\CrudModel;

abstract class BaseWebUiController extends Controller
{

    abstract public function model(): CrudModel;

    public function index(HttpRequest $request)
    {


        return view("web.ui.basictable", [
            "baseUrl" => url('/'),
            "tableName" => $this->model()->getTable(),
            "specPath" => $this->model()->getPath(),
        ]);
        # code...
    }


}
