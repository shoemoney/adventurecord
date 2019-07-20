<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Update;
use App\Http\Requests\PostUpdateRequest;

class UpdateController extends Controller
{
    public function data()
    {
        return Update::orderby('created_at', 'desc')->get();
    }
    public function get($id)
    {
        return Update::findOrFail($id);
    }
    public function store(PostUpdateRequest $request)
    {
        Update::create($request->only('title', 'body'));
    }
    public function update(PostUpdateRequest $request, $id)
    {
        Update::findOrFail($id)->update($request->only('title', 'body'));
    }
}
