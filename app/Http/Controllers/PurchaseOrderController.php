<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PurchaseOrderItem;
use App\Models\Suppliers;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }

    function index() {
        $suppliers = Suppliers::orderBy('name','ASC')->get();
        $products  = Products::all();
        return view('page.purchase.index',compact('suppliers','products'));
    }

    function data(Request $request) {
        $user   =   auth()->user();
        $role   =   $user->role;

        if ($role == 'supplier') {
            $supplier   =   Suppliers::where('email',$user->email)->first();
            $po         =   PurchaseOrder::where('supplier_id',$supplier->id)->get();
        }else {
            $po     =   PurchaseOrder::orderBy('created_at','desc')->get();
        }

        $table  = DataTables::of($po)
        ->addIndexColumn()
        ->addColumn('checkbox', function ($row) {
            $html = '<label class="checkboxs">
                        <input type="checkbox">
                        <span class="checkmarks"></span>
                    </label>';

            return $html;
        })
        ->addColumn('supplier_id',function($row) {
            $supplier = Suppliers::find($row->supplier_id);
            return $supplier->name;
        })
        ->addColumn('order_date',function($row) {
            return Carbon::parse($row->order_date)->format('d F Y');
        })
        ->rawColumns(['checkbox'])
        ->make(true);

        return $table;
    }

    function store(Request $request) {
        $validator      =   Validator::make($request->all(),[
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'note'          => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(),'title' => "Error"],422);
        }

        DB::beginTransaction();
        try {
            $order = PurchaseOrder::create([
                'po_number' => 'PO-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'status' => 'draft',
                'note'  => $request->note,
            ]);

            foreach ($request->products as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],  // Menggunakan harga inputan pengguna
                ]);
            }


            DB::commit();

            return response()->json(['message' => "Purchase order created successfully",'title' => "Success"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Something wrong...",'title' => "Error",'log' => $th->getMessage(),'data' => $request->all()],422);
        }
    }

    public function show($id)
    {
        try {
            $purchaseOrder = PurchaseOrder::with('items')->find($id);
            $suppliers = Suppliers::all();
            $products = Products::select('id', 'name')->get();

            return response()->json([
                'data' => $purchaseOrder,
                'supplier' => $suppliers,
                'products' => $products,
                'items' => $purchaseOrder->items
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong...",
                'title' => "Error",
                'log' => $th->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Validasi input request
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'order_date'  => 'required|date',
                'products'    => 'required|array',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity'   => 'required|numeric|min:1',
                'products.*.price'      => 'required|numeric|min:1',
            ]);

            // Cari purchase order
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $purchaseOrder->supplier_id = $request->supplier_id;
            $purchaseOrder->order_date  = $request->order_date;
            $purchaseOrder->save(); // Simpan perubahan pada order utama

            // Hapus item lama
            $purchaseOrder->items()->delete();

            // Insert produk baru
            foreach ($request->products as $productData) {
                // Menambahkan item produk yang baru
                $purchaseOrder->items()->create([
                    'product_id' => $productData['product_id'],
                    'quantity'   => $productData['quantity'],
                    'price'      => $productData['price'],
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Purchase order updated successfully.'], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong while updating.',
                'title'   => 'Error',
                'log'     => $th->getMessage()
            ], 422);
        }
    }


    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $purchaseOrder->delete();

            DB::commit();
            return response()->json(['message' => "Purchase order deleted!",'title' => "Success"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Something wrong...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }
}
