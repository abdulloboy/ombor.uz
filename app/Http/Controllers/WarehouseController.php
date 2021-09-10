<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /** 
     * Ombor.uzga API orqali JSON so'rov berilganda ishlaydi va natijani qaytaradi.
     * Misol:  
     * [
     *  { 
     *      "product_id": 1, 
     *      "quantity": 30 
     *  },
     *  { 
     *      "product_id": 2, 
     *      "quantity": 2
     *  }
     * ]
     * 
     * 
     * @bodyParam products object[] required Mahsulot ro'yhati. 
     * @bodyParam products.product_id integer required So'ralayotgan mahsulot Id si yoziladi.
     * @bodyParam products.quantity integer required So'ralayotgan mahsulotdan nechta kerakligi yoziladi. 
     * @response {"result":[{"product_name":"Ko'ylak","product_qty":30,"product_materials":[{"warehouse_id":1,"material_name":"Mato","qty":12,"price":1500},{"warehouse_id":2,"material_name":"Mato","qty":12,"price":1600},{"warehouse_id":3,"material_name":"Ip","qty":40,"price":500},{"warehouse_id":4,"material_name":"Ip","qty":260,"price":550},{"warehouse_id":5,"material_name":"Tugma","qty":150,"price":300}]},{"product_name":"Shim","product_qty":20,"product_materials":[{"warehouse_id":2,"material_name":"Mato","qty":28,"price":1600},{"warehouse_id":4,"material_name":"Ip","qty":40,"price":550},{"warehouse_id":null,"material_name":"Ip","qty":260,"price":null},{"warehouse_id":6,"material_name":"Zamok","qty":20,"price":2000}]}]}
     * @return \Illuminate\Http\Response
    */
    
    public function index(Request $request)
    {
        $result=[];
        $warehouses=[];
        foreach ($request->all() as $req)
        {
            $product=Product::find($req['product_id']);
            $result2['product_name']=$product->name;
            $result2['product_qty']=$req['quantity'];
            $result2['product_materials']=[];
            
            foreach ($product->materials as $material)
            {
                $total=$req['quantity']*$material->pivot->quantity;
                foreach ($material->warehouses as $warehouse)
                {
                    if (isset($warehouses[$warehouse->id]))
                        $warehouse=$warehouses[$warehouse->id];
                    $min=min($total,$warehouse->remainder);
                    if($min==0)
                        continue;
                    $warehouse2['warehouse_id']=$warehouse->id;
                    $warehouse2['material_name']=$warehouse->material->name;
                    $warehouse2['qty']=$min;
                    $warehouse2['price']=$warehouse->price;

                    $result2['product_materials'][]=$warehouse2;
                    $total-=$min;
                    $warehouse->remainder-=$min;
                    $warehouses[$warehouse->id]=$warehouse;
                    if($total==0)
                        break;
                }
                if ($total>0)
                {
                    $warehouse2['warehouse_id']=null;
                    $warehouse2['material_name']=$material->name;
                    $warehouse2['qty']=$total;
                    $warehouse2['price']=null;

                    $result2['product_materials'][]=$warehouse2;
                }
            }

            $result[]=$result2;
        }
        return array('result' => $result);
    }

    
}
