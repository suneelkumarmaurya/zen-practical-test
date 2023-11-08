<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\ProductMaster;
use App\Models\TempProductData;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{

    public function index(){
        $products = ProductMaster::orderBy("product_id","desc")->get();
        // dd($products);
        return view("main",compact("products"));
    }

    public function productChange(Request $request){
        ($request->productId);
        $product = ProductMaster::where('product_id',$request->productId)->first();
       return response()->json([
        'status'=> true,
        'product'=> $product,
       ]);
    }



    public function productDelete(Request $request){
        $tempData = null;
        if($request->tmpId) {
            $tempData = TempProductData::find($request->tmpId)->delete();
            return response()->json([
                "status"=> true,

            ]);
        } else {
            return response()->json([
                "status"=> false,
            ]);
        }
    }

     public function productAdd(Request $request){
        $tempData = null;
        if($request->tmpId) {
            $tempData = TempProductData::where('product_id',$request->tmpId)->first();
        } else {
            $tempData=new TempProductData();
            $tempData->customer_name=$request->customer_name;
        }

        $product=productMaster::where("product_id",$request->product)->first();

        $tempData->temp_product_id=$request->product;
        $tempData->product_name=$product->product_name;
        $tempData->rate=$product->rate;
        $tempData->unit=$product->unit;

        $tempData->qty=$request->qty;
        $discount = $this->calculatePercentage($product->rate, $request->disc);
        $tempData->net_amt=$discount;
        $tempData->total_amt=$discount*$tempData->qty;
        $tempData->disc=$request->disc;

        $tempData->save();

        if($request->tmpId) {
            return response()->json([
                "status"=> true,

            ]);
        } else {
            return redirect()->to('products');
        }

     }


     public function products(Request $request){
        $data=[];
        $products=ProductMaster::all();


      // fetch data from tempProductData
      $tempData=tempProductData::all();
      $data["tempDatas"]=$tempData;
      $data["products"]=$products;

        return view("tempProductList",$data);
     }

     function calculatePercentage($netAmount, $percentage){
        return $netAmount - ($netAmount*$percentage/100);
    }

    public function dataStore(){
        $tempDatas=tempProductData::all();
        $count = InvoiceMaster::count();
        $invoceMaster=new InvoiceMaster();
        $invoceMaster->Invoice_No="INV_00".$count+1;
        $invoceMaster->Invoice_Date = Carbon::now();
        $invoceMaster->CustomerName = $tempDatas[0]->customer_name;
        $totalAmt = 0;
        foreach($tempDatas as $data){
            $totalAmt = $totalAmt + $data->total_amt;
        }
        $invoceMaster->TotalAmount = $totalAmt;
        $invoceMaster->save();
        // dd( $invoceMaster);
        foreach($tempDatas as $data){
            $invoice =new InvoiceDetail();
            $invoice->Invoice_id =  $invoceMaster->id;
            $invoice->product_id =  $data->temp_product_id;
            $invoice->Rate =  $data->rate;
            $invoice->Qty =  $data->qty;
            $invoice->Unit =  $data->unit;
            $invoice->NetAmount =  $data->net_amt;
            $invoice->TotalAmount =  $data->total_amt;
            $invoice->Disc_percentage	 =  $data->disc;
            $invoice->save();
        }
        TempProductData::truncate();
        return response()->json([
            "status"=> true,
        ]);
    }



}
