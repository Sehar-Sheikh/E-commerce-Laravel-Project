<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages= Page::latest();
        if ($request->keyWord != '' ) {
            $pages = $pages->where('name','like','%'.$request->keyWord.'%');
        }
        $pages = $pages->paginate(10);
        return view('admin.pages.list',[
            'pages' => $pages
        ]);
    }
    public function create()
    {
        return view('admin.pages.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page = new Page();
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        $message = 'Page added successfully.';

        session()->flash('success',$message);
        return response()->json([
            'status' => true,
            'success' => $message
        ]);


    }
    public function edit()
    {
    }
    public function update()
    {
    }
    public function destroy()
    {
    }
}
