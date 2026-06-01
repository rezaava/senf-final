<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //

    public function list()
    {
        $categories = Category::all();
        return view("dashboard.category.categories", compact("categories"));
    }

    public function listParent()
    {
        $categories = Category::all();
        return view("dashboard.category.categoryParent", compact("categories"));
    }

    public function new()
    {
        $categories = Category::where('parent_id', null)->get();
        return view("dashboard.category.new", compact('categories'));
    }
    public function newPost(CategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/categories/image';
            $file->move($destination_path, $file_name);
            $category->image = $destination_path . '/' . $file_name;
        }
        $category->parent_id = $request->parent;
        $category->price = $request->price;

        $category->save();
        return redirect()->route('category.list')->with('success', 'دسته بندی با موفقیت ایجاد شد');
    }
    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
        }
        return redirect()->back()->with('success', 'دسته بندی با موفقیت حذف شد');
    }
    public function edit($id)
    {
        $categories = Category::where('parent_id', null)->get();
        $category = Category::findOrFail($id);
        return view("dashboard.category.edit", compact("category",'categories'));
    }
    public function editPost($id, CategoryRequest $request)
    {

        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination_path = 'files/categories/image';
            $file->move($destination_path, $file_name);
            $category->image = $destination_path . '/' . $file_name;
        }
         $category->parent_id = $request->parent;
        $category->price = $request->price;
        $category->save();
        return redirect()->route('category.list')->with('success', 'دسته بندی با موفقیت ویرایش شد');
    }
}
