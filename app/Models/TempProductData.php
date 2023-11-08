<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempProductData extends Model
{

    use HasFactory;
    protected $primaryKey = 'product_id';
    public function getProductName(){
        return $this->hasOne("App\Model\ProductMaster","product_id","temp_product_id");
    }
}
