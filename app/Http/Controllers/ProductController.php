<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function index(Request $request) {
        $user   =   Auth::user();
        $roles  =   $user->role;
        $category = Categories::orderBy('name')->get();

        if ($roles == 'admin') {
            return view('page.product.vAdmin',compact('category'));
        }
        else {
            return abort(404,'Not Found');
        }
    }

    function category(Request $request) {
        $user       =   Auth::user();
        $roles      =   $user->role;
        $category   =   Categories::orderBy('name')->get();
        if ($roles == 'admin') {
            return view('page.product.vAdminCategory',compact('category'));
        }
        else {
            return abort(404,'Not Found');
        }
    }

    function category_store(Request $request) {
        try {
            DB::beginTransaction();
            $category  = new Categories();
            $category->name = $request->name;
            $category->save();

            DB::commit();
            return response()->json(['message' => "Category added successfully!",'title' => "Success"]);

        } catch (\Throwable $th) {
            return response()->json(['message' => "Something Wrong...", 'title' => "Error",'log' => $th->getMessage()],422);
        }
    }

    function category_data(Request $request) {
        $category   =   Categories::orderBy('name')->get();

        $table      = DataTables::of($category)
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);

        return $table;
    }

    function category_show($id) {
        try {
            $category  = Categories::find($id);
            return response()->json(['data' => $category]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Something wrong...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }

    function category_update(Request $request, $id) {
        DB::beginTransaction();
        try {
            $category       = Categories::find($id);
            $category->name = $request->name;
            $category->save();

            DB::commit();
            return response()->json(['message' => "Update category successfully!",'title' => "Success"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Something wrong..",'title' => "Error", 'log' => $th->getMessage()],422);
        }
    }

    function category_delete($id) {
        DB::beginTransaction();
        try {
            $category = Categories::findOrFail($id);
            $category->delete();
            DB::commit();

            return response()->json(['message' => "Category Deleted !",'title' => "Success",]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Something wrong ..." ,'title' => "Error",'log' => $th->getMessage()],422);
        }
    }

    function data(Request $request) {
        $product   =   Products::orderBy('product_code')->get();

        $table      = DataTables::of($product)
        ->addIndexColumn()
        ->addColumn('checkbox', function ($row) {
            $html = '<label class="checkboxs">
                        <input type="checkbox">
                        <span class="checkmarks"></span>
                    </label>';

            return $html;
        })
        ->addColumn('category',function($row) {
            $category  = Categories::find($row->category_id);
            return $category->name ?? "";
        })
        ->rawColumns(['action','checkbox'])
        ->make(true);

        return $table;
    }

    function store(Request $request) {
        $validate = Validator::make($request->all(),[
            'product_code'     => 'required|unique:products,product_code',
            'product_name'     => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'unit'             => 'required|string|max:100',
            'stock'            => 'required|integer|min:0',
            'min_stock'        => 'required|integer|min:0',
            'selling_price'    => 'required',
            'lead_time'        => 'required|integer|min:1',
            'average_demand'   => 'required|integer|min:1',
            'ordering_cost'    => 'nullable|integer|min:1',
            'holding_cost_percent'  => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()->first(),'title' => "Error"],422);
        }

        DB::beginTransaction();

        try {
            $product   = new Products();
            $product->product_code   = $request->product_code;
            $product->name           = $request->product_name;
            $product->unit           = $request->unit;
            $product->stock          = $request->stock;
            $product->min_stock      = $request->min_stock;
            $product->selling_price  = $request->selling_price;
            $product->lead_time      = $request->lead_time;
            $product->category_id    = $request->category_id;
            $product->average_demand = $request->average_demand;
            $product->ordering_cost         = $request->ordering_cost;
            $product->holding_cost_percent  = $request->holding_cost_percent;
            $product->save();
            DB::commit();
            return response()->json(['message' => "Product add successfully!",'title' => 'Success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Something wrong ...','title' => "Error",'Log' => $th->getMessage],422);
        }
    }

    function edit(Request $request,$id) {
        $validate = Validator::make($request->all(),[
            'product_code'     => 'required|unique:products,product_code,'.$id,
            'product_name'     => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'unit'             => 'required|string|max:100',
            'stock'            => 'required|integer|min:0',
            'min_stock'        => 'required|integer|min:0',
            'selling_price'    => 'required',
            'lead_time'        => 'required|integer|min:1',
            'average_demand'   => 'required|integer|min:1',
            'ordering_cost'    => 'nullable|integer|min:1',
            'holding_cost_percent'  => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()->first(),'title' => "Error"],422);
        }

        DB::beginTransaction();

        try {
            $product   = Products::find($id);
            $product->product_code   = $request->product_code;
            $product->name           = $request->product_name;
            $product->unit           = $request->unit;
            $product->stock          = $request->stock;
            $product->min_stock      = $request->min_stock;
            $product->lead_time      = $request->lead_time;
            $product->selling_price      = $request->selling_price;
            $product->category_id    = $request->category_id;
            $product->average_demand = $request->average_demand;
            $product->ordering_cost         = $request->ordering_cost;
            $product->holding_cost_percent  = $request->holding_cost_percent;
            $product->save();
            DB::commit();
            return response()->json(['message' => "Product update successfully!",'title' => 'Success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Something wrong ...','title' => "Error",'Log' => $th->getMessage],422);
        }
    }

    function show($id) {
        try {
            $data = Products::find($id);
            $data->selling_price = (int)$data->selling_price;
            $category = Categories::all();
            return response()->json(['data' => $data,'category' => $category]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Something wrong ..." ,'title' => "Error",'log' => $th->getMessage()],422);
        }
    }

    function delete($id) {
        DB::beginTransaction();
        try {
            $product = Products::findOrFail($id);
            $product->delete();

            DB::commit();
            return response()->json(['message' => "Product deleted!",'title' => "Success"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Something wrong...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }

    function search(Request $request) {
        $search = $request->input('q');

        $products = Products::where('name', 'like', "%{$search}%")
            ->orWhere('product_code', 'like', "%{$search}%")
            ->get();

        return response()->json($products);
    }

}
