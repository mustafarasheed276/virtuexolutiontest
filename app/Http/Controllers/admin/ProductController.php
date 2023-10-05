<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\TempImage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest();

        if(!empty($request->get('keyword'))){
            $products = $products->where('name','like','%'.$request->get('keyword').'%');
        }
        $products = $products->paginate(10);
        return view('admin.product.list', compact('products'));
    } 

    public function create()
    {
        return view('admin.product.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:products',
        ]);

        if ($validator->passes()) {

            $product = new Product();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->status = $request->status;
            $product->image = $request->image;
            $product->save();

            $request->session()->flash('success', 'Product added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id){
        $product = Product::where('id',$id)->first();
        $imageIds = explode(',', $product->image);

        // Retrieve images from TempImages model using the IDs
        $productImages = TempImage::whereIn('id', $imageIds)->get();

        return view('admin.product.edit')->with(compact('id', 'product', 'productImages'));
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:products,id,'.$id,
        ]);

        if ($validator->passes()) {

            $product = Product::find($id);
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->status = $request->status;
            $product->image = $request->image;
            $product->save();

            $request->session()->flash('success', 'Product updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id){
        $product = Product::find($id);
        $product->delete();
        return back();
    }
}
