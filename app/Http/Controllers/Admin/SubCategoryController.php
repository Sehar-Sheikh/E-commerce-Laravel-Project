<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SubCategoryController extends Controller
{

    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftJoin(
                'categories',
                'categories.id',
                'sub_categories.category_id'
            );

        if (!empty($request->get('keyWord'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyWord') . '%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%' . $request->get('keyWord') . '%');
        }

        $subCategories = $subCategories->paginate(10);

        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create()
    {
        $subCategories = Category::orderBy('name', 'ASC')->get();
        $categories = Category::all();
        return view('admin.sub_category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:sub_categories', // Ensure the slug is unique in sub_categories table
                'category' => 'required',
                'status' => 'required'
            ]);

            if ($validator->passes()) {
                $subCategory = new SubCategory();
                $subCategory->name = $request->name;
                $subCategory->slug = $request->slug;
                $subCategory->status = $request->status;
                $subCategory->showHome = $request->showHome;
                $subCategory->category_id = $request->category;
                $subCategory->save();

                session()->flash('success', 'Sub Category created successfully');

                return response([
                    'status' => true,
                    'message' => 'Sub Category created successfully'
                ]);
            } else {
                return response([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        }
        catch (\Exception $e) {
            // Log the exception details
            Log::error('Error creating sub category: ' . $e->getMessage());

            return response([
                'status' => false,
                'message' => 'Error creating sub category. Please check the logs for details.'
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            session()->flash('error', 'Record not found');
            return redirect()->route('sub-categories.index');
        }

        $subCategories = Category::orderBy('name', 'ASC')->get();
        $categories = Category::all();

        return view('admin.sub_category.edit', compact('categories', 'subCategories', 'subCategory'));
    }

    public function update($id, Request $request)
    {
        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            session()->flash('error', 'Record not found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            // return redirect()->route('sub-categories.index');
        }
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                // 'slug' => 'required|unique:sub_categories',
                'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
                'category' => 'required',
                'status' => 'required'
            ]);

            if ($validator->passes()) {

                $subCategory->name = $request->name;
                $subCategory->slug = $request->slug;
                $subCategory->status = $request->status;
                $subCategory->showHome = $request->showHome;
                $subCategory->category_id = $request->category;
                $subCategory->save();

                session()->flash('success', 'Sub Category updated successfully');

                return response([
                    'status' => true,
                    'message' => 'Sub Category updated successfully'
                ]);
            } else {
                return response([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error creating sub category: ' . $e->getMessage());

            return response([
                'status' => false,
                'message' => 'Error creating sub category. Please check the logs for details.'
            ]);
        }
    }

    public function destroy($id, Request $request){
        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            session()->flash('error', 'Record not found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $subCategory->delete();
        session()->flash('success','Sub Category deleted successfully.');

        return response([
            'status' => true,
            'message' => 'Sub Category deleted successfully.'

        ]);
    }
}
