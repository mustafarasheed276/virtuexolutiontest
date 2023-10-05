<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;

class TempImagesController extends Controller
{
	public function create(Request $request)
    {
    	$image = $request->file('image');
    	$fileInfo = $image->getClientOriginalName();
    	$filename = pathinfo($fileInfo, PATHINFO_FILENAME);
    	$extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
    	$file_name= $filename.'-'.time().'.'.$extension;
    	$image->move(public_path('uploads/gallery'),$file_name);
    	 
    	$imageUpload = new TempImage;
    	$imageUpload->product_id = '';
    	$imageUpload->original_filename = $fileInfo;
    	$imageUpload->filename = $file_name;
    	$imageUpload->save();
    	return response()->json(['success'=>$file_name,'id'=> $imageUpload->id]);
    }
}
