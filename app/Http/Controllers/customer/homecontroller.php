<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dress;
use App\Models\requestorder;
use Illuminate\Http\Request;

use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class homecontroller extends Controller
{
    use ResponseTrait;


    public function viewdresses()
    {
        $dress =Dress::all();
        return $this->returnData('dress',$dress,200);

    }

public function viewcategories()
    {
        $category =Category::all();
        return $this->returnData('category',$category,200);

    }
public function showspacificdress($id)
{
    $dress =Dress::all()->where('category_id',$id);
    return $this->returnData('dress',$dress,200);
}

public function showdressdetails($id)
{
    $dress =Dress::where('id',$id)->with('admin')->first();
    return $this->returnData('dress',$dress,200);
}


public function requestorder(Request $request,$dressid)
{

$dress= Dress::find($dressid);
$data= $request->validate([
'dress_id' ,
'customer_id',
'startdate' ,
'enddate',
'price',
'dayes'
]);
$data['dress_id']=$dressid;
$data['customer_id']= auth('api')->user()->id;
$data['startdate']=Carbon::createFromFormat('d/m/Y', $request->startdate);
$data['enddate']=Carbon::createFromFormat('d/m/Y', $request->enddate);
$dayes = $data['startdate']->diffInDays($data['enddate']);
$data['dayes']=$dayes;
$data['price']=$dress->price_per_day *$dayes ;


requestorder::create($data);
return $this->returnSuccess(['your request successfully'], 200);


}

}
