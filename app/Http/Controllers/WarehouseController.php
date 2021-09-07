<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Ombor.uzga API orqali JSON so'rov berilganda ishlaydi va natijani qaytaradi.
     *
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
