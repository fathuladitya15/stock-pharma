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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseExport;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;

class PurchaseOrderController extends Controller
{
    function __construct(){
        $this->middleware(['auth']);
        $this->middleware(['role:admin|staff_gudang|supplier|manager']);
    }

    function index() {
        $suppliers = Suppliers::orderBy('name','ASC')->get();
        $products  = Products::all();
        $user      = Auth::user();

        if ($user->role == 'supplier') {
            return view('page.purchase.vSupplier',compact('suppliers','products'));
        }else {
            return view('page.purchase.index',compact('suppliers','products'));
        }
    }

    function data(Request $request) {
        $user   =   auth()->user();
        $role   =   $user->role;

        if ($role == 'supplier') {
            $supplier   =   Suppliers::where('user_id',$user->id)->first();
            $po         =   PurchaseOrder::where('supplier_id',$supplier->id)->get();
        }else {
            $po     =   PurchaseOrder::where('status','completed')->orderBy('created_at','desc')->get();
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
        ->addColumn('status',function($row) use ($role) {
            // Mapping status badge
            $statusMap = [
                'draft'      => ['label' => 'Draft', 'class' => 'badge badge-secondary'],
                'pending'    => ['label' => 'Pending', 'class' => 'badge badge-warning'],
                'sent'       => ['label' => 'Sent', 'class' => 'badge badge-info'],
                'confirmed'  => ['label' => 'Confirmed', 'class' => 'badge badge-primary'],
                'processing' => ['label' => 'Processing', 'class' => 'badge badge-warning'],
                'shipped'    => ['label' => 'Shipped', 'class' => 'badge badge-info'],
                'received'   => ['label' => 'Received', 'class' => 'badge badge-primary'],
                'completed'  => ['label' => 'Completed', 'class' => 'badge badge-success'],
                'cancelled'  => ['label' => 'Cancelled', 'class' => 'badge badge-danger'],
            ];

            $statusKey = $row->status;

            // Masking untuk supplier melihat "sent" sebagai "Waiting"
            if ($role === 'supplier' && $row->status === 'sent') {
                $label = 'Waiting';
                $class = 'badge badge-warning';
            } else {
                $label = $statusMap[$statusKey]['label'] ?? Str::title($statusKey);
                $class = $statusMap[$statusKey]['class'] ?? 'badge badge-secondary';
            }

            return '<span class="'. $class .'">'. $label .'</span>';
        })
        ->addColumn('action',function($row) use ($role) {
            $status    =   $row->status;
            $edit      =   '<div class="edit-delete-action">
                            <a class="me-2 edit-icon p-2 show_data" href="javascript:void(0)" data-id="'.$row->id.'">
                                <i data-feather="eye" class="feather-eye"></i>
                            </a>';

            $delete     =   '<a class="p-2 delete" data-id="'.$row->id.'" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>';
            $complete   =   '<a class="p-2 complete" data-id="'.$row->id.'" href="javascript:void(0);">
                                <i data-feather="check-square" class="feather-check-square"></i>
                                </a>';
            $show       =   '<a class="p-2" href="'.route('purchase.order.detail',$row->id).'" target="_BLANK" data-id="'.$row->id.'" href="javascript:void(0);">
                                <i data-feather="eye" class="feather-eye"></i>
                                </a>';
            if ($role == 'supplier') {
                $button = $edit;
            }else {
                if (in_array($status,['draft','sent'])) {
                    $button = $edit.'&nbsp;'.$delete;
                }else if (in_array($status,['confirmed','pending','processing','received','shipped'])) {
                    $button = $edit.'&nbsp;'.$complete;
                }
                else if ($status == 'completed') {
                    $button = $show;
                }
                else {
                    $button = "";
                }
            }
            return $button;
        })
        ->rawColumns(['checkbox','status','action'])
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
                'user_id'   => Auth::id(),
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
            $request->validate([
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
            $purchaseOrder->status      = $request->status;
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

    public function export(Request $request) {
        try {
            $start      = $request->start_date;
            $end        = $request->end_date;
            $filename   = "PO_REPORT-".now()->format('Ymd').'.xlsx';
            return Excel::download(new PurchaseExport($start,$end), $filename);
            // Tambahkan header agar Content-Disposition bisa dibaca di JavaScript
            $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');
        } catch (\Exception $th) {
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th],422);
        }
    }

    public function update_status(Request $request, $id) {
        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $purchaseOrder->status      = $request->status;
            $purchaseOrder->save();
            DB::commit();
            return response()->json(['message' => 'Purchase order status has been updated.'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong while updating.',
                'title'   => 'Error',
                'log'     => $th->getMessage()
            ], 422);
        }
    }

    function detail($id) {
        $data   =   PurchaseOrder::find($id);
        $data->order_date = Carbon::parse($data->order_date)->format('l, d F Y');
        $item   =   PurchaseOrderItem::with('product')->where('purchase_order_id',$id)->get();
        $supplier = Suppliers::find($data->supplier_id);
        return view('page.purchase.vDetail',compact('data','item','supplier'));
    }

    function report() {
        return view('page.purchase.vReport');
    }

    function report_search(Request $request) {
        $user  =   Auth::user();
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end   = Carbon::parse($request->end_date)->endOfDay();

        if ($user->role == 'manager') {
            $purchaseOrder = PurchaseOrder::whereBetween('order_date', [$start, $end])
            ->where('status','completed')
            ->orderBy('created_at', 'asc')->pluck('id')->toArray();
        }else {
            $purchaseOrder = PurchaseOrder::whereBetween('order_date', [$start, $end])
            ->orderBy('created_at', 'asc')->pluck('id')->toArray();

        }

        $purchaseOrderItem = PurchaseOrderItem::with('product')->whereIn('purchase_order_id',$purchaseOrder)->get();

        $result = [];
        $grandTotal = 0;
        foreach ($purchaseOrderItem as $item) {
            $purchaseOrderD = PurchaseOrder::find($item->purchase_order_id);
            $category       = Categories::find($item->product->category_id);
            $total          = $item->quantity * $item->price;
            $result[] = [
                'purchase_number'   => $purchaseOrderD->po_number,
                'product_name'      => $item->product->name,
                'product_code'      => $item->product->product_code,
                'created_at'        => Carbon::parse($purchaseOrderD->order_date)->format('l, d F Y'),
                'category'          => $category->name,
                'qty'               => $item->quantity,
                'price'             => "Rp ".number_format($item->price,0,',','.'),
                'total'             => "Rp ".number_format($total,0,',','.'),
            ];
            $grandTotal += $total;
        }

        return response()->json(['data' => $result, 'grand_total' => "Rp " . number_format($grandTotal, 0, ',', '.')]);
    }

    function excel(Request $request) {


        try {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();


            $purchaseOrder = PurchaseOrder::whereBetween('order_date', [$start, $end])
            ->orderBy('created_at', 'asc')->pluck('id')->toArray();

            $purchaseOrderItem = PurchaseOrderItem::with('product')->whereIn('purchase_order_id',$purchaseOrder)->get();

            // $sales  =   SalesDetail::with(['product'])
            // ->whereBetween('created_at', [$start, $end])
            // ->orderBy('created_at', 'asc')
            // ->get();


            $result = [];
            $grandTotal = 0;

            foreach ($purchaseOrderItem as $item) {
                 $purchaseOrderD = PurchaseOrder::find($item->purchase_order_id);
                 $category       = Categories::find($item->product->category_id);
                 $total          = $item->quantity * $item->price;
                 $result[] = [
                     'purchase_number'   => $purchaseOrderD->po_number,
                     'product_name'      => $item->product->name,
                     'product_code'      => $item->product->product_code,
                     'created_at'        => Carbon::parse($purchaseOrderD->order_date)->format('l, d F Y'),
                     'category'          => $category->name,
                     'qty'               => $item->quantity,
                     'price'             => "Rp ".number_format($item->price,0,',','.'),
                     'total'             => "Rp ".number_format($total,0,',','.'),
                 ];
                 $grandTotal += $total;
             }

            $grandTotalToRP = "Rp " . number_format($grandTotal, 0, ',', '.');
            $filename       = "Purchase_order_Report_" . Carbon::now()->format('ymd_His');

            return response()->view('page.sales.excel', compact('result','grandTotalToRP'))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');
        } catch (\Throwable $th) {
            return response()->json(['message' => "Something wrong",'log' => $th->getMessage],422);
        }
    }
}
