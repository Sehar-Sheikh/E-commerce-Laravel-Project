<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();

        if (!empty($request->get('keyWord'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyWord') . '%');
        }
        $categories = $categories->paginate(10);

        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;

            $category->save();

            $oldImage = $category->image;

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                if ($tempImage) {
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray);

                    $timestamp = Carbon::now()->timestamp;
                    $newImageName = $category->id . '-' . $timestamp . '.' . $ext;
                    $sPath = public_path() . '/temp/' . $tempImage->name;
                    $dPath = public_path() . '/uploads/category/' . $newImageName;
                    $dPathThumb = public_path() . '/uploads/category/thumb/' . $newImageName;

                    File::copy($sPath, $dPath);

                    // Generate Image Thumbnail
                    $img = Image::make($sPath);
                    //$img->resize(450, 600);
                    $img->fit(450, 600, function ($constraint) {
                        $constraint->upsize();
                    });
                    $img->save($dPathThumb);

                    $category->image = $newImageName;
                    $category->save();

                    // Delete old images
                    File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                    File::delete(public_path() . '/uploads/category/' . $oldImage);

                    session()->flash('success', 'Category added successfully');

                    return response()->json([
                        'status' => true,
                        'message' => 'Category added successfully',
                    ]);
                }
            }
        }
        // If the validation fails or image handling fails
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }

    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {

            session()->flash('error', 'Category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {
            $oldImage = $category->image;

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);

                if ($tempImage) {
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray);

                    $timestamp = Carbon::now()->timestamp;
                    $newImageName = $category->id . '-' . $timestamp . '.' . $ext;
                    $sPath = public_path() . '/temp/' . $tempImage->name;
                    $dPath = public_path() . '/uploads/category/' . $newImageName;
                    $dPathThumb = public_path() . '/uploads/category/thumb/' . $newImageName;

                    File::copy($sPath, $dPath);

                    // Generate Image Thumbnail
                    $img = Image::make($sPath);
                    //$img->resize(450, 600);
                    $img->fit(450, 600, function ($constraint) {
                        $constraint->upsize();
                    });
                    $img->save($dPathThumb);

                    $category->image = $newImageName;
                    $category->save();

                    // Delete old images
                    File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                    File::delete(public_path() . '/uploads/category/' . $oldImage);

                    session()->flash('success', 'Category updated successfully');

                    return response()->json([
                        'status' => true,
                        'message' => 'Category updated successfully',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ]);
                }
            } else {
                session()->flash('success', 'Category updated successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Category updated successfully',
                ]);
            }
        }
        // If the validation fails or image handling fails
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }

    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
        }
        // Delete old images
        File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
        File::delete(public_path() . '/uploads/category/' . $category->image);

        $category->delete();

        session()->flash('success', 'Category deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully'
        ]);
    }
}
