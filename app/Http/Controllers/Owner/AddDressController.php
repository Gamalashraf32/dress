<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use App\Models\Dressimage;
use App\Traits\ImageUpload;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddDressController extends Controller
{
    use ImageUpload;
    use ResponseTrait;
    public function add(Request $request)
    {
        $admin_id=auth('admin')->user()->value('id');
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'description' => 'required',
            'size' => 'required',
            'color' => 'required',
            'price_per_day' => 'required',
            'images' => 'required',
            'images.*' => 'file|mimes:png,jpg,jpeg|max:4096',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $message) {
                $error = implode($message);
                $errors[] = $error;
            }
            return $this->returnError(implode(' , ', $errors), 400);
        }

         $dress=Dress::create([
            'admin_id' => $admin_id,
            'category_id' => $request->category_id,
            'size' => $request->size,
            'color' => $request->color,
            'description' => $request->description,
            'price_per_day' => $request->price_per_day,
        ]);
        foreach ($request->file('images') as $image) {
            $image_path = $this->uploadImage($image, 'dresses-images', 60);
            Dressimage::create([
                'dress_id' => $dress->id,
                'image' => $image_path
            ]);
        }
        return $this->returnSuccess('Dress saved successfully', 200);
    }
    public function update(Request $request,$id)
    {
        $admin_id=auth('admin')->user()->value('id');
        $dress=Dress::where('admin_id',$admin_id)->find($id);
        if(!$dress)
        {
            return $this->returnError('Dress not found',404);
        }
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'description' => 'required',
            'size' => 'required',
            'color' => 'required',
            'price_per_day' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $message) {
                $error = implode($message);
                $errors[] = $error;
            }
            return $this->returnError(implode(' , ', $errors), 400);
        }

        $dress->update([
            'category_id' => $request->category_id,
            'size' => $request->size,
            'color' => $request->color,
            'description' => $request->description,
            'price_per_day' => $request->price_per_day,
            'status'=>$request->status,
        ]);

        return $this->returnSuccess('Dress updated successfully', 200);
    }
    public function delete($id)
    {
        $admin_id=auth('admin')->user()->value('id');
        $dress=Dress::where('admin_id',$admin_id)->find($id);

        if(!$dress)
        {
            return $this->returnError('Dress not found',404);
        }
        $dress->delete();
        return $this->returnSuccess('Dress Deleted',200);
    }
    public function show()
    {
        $admin_id=auth('admin')->user()->value('id');
        $dresses=Dress::where('admin_id',$admin_id)->get();
        if(is_null($dresses))
        {
            return $this->returnError('no dresses added yet',404);
        }
        return $this->returnData('dresses',$dresses,200);
    }
    public function showid($id)
    {
        $admin_id=auth('admin')->user()->value('id');
        $dress=Dress::where('admin_id',$admin_id)->where('id',$id)->first();
        if(!$dress)
        {
            return $this->returnError('dress not found',404);
        }
        return $this->returnData('dress',$dress,200);
    }


}
