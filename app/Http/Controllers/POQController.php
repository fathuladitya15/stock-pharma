<?php

namespace App\Http\Controllers;

use App\Exports\PoQReportExport;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PoQ;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class POQController extends Controller
{
    function __construct(){
        $this->middleware('auth');
    }

    function index(Request $request) {
        $product = Products::orderBy('name','ASC')->get();
        return view('page.poq.vIndex',compact('product'));
    }


    public function calculatePOQ($productId)
    {
        DB::beginTransaction();
        try {
            $product = Products::find($productId);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            // Validasi average_demand
            if ($product->average_demand <= 0) {
                return response()->json(['message' => 'Average demand must be more than 0'], 422);
            }

            // Validasi ordering_cost
            $S = $product->ordering_cost ?? 100000;
            if ($S <= 0) {
                return response()->json(['message' => 'Ordering cost is invalid'], 422);
            }

            // Ambil harga terakhir
            $unitPrice = $product->selling_price;
            if ($unitPrice <= 0) {
                return response()->json(['message' => 'Product price is invalid'], 422);
            }

            // Hitung Holding Cost
            $H = $unitPrice * ($product->holding_cost_percent ?? 0.1);
            if ($H <= 0) {
                return response()->json(['message' => 'Holding costs are invalid'], 422);
            }

            // Hitung EOQ dan POQ
            $D = $product->average_demand * 12; // per tahun
            $EOQ = sqrt((2 * $D * $S) / $H);
            $POQ = $EOQ / ($D / 12); // periode per bulan

            // Simpan POQ ke database
            $product->poq_quantity = ceil($EOQ); // bisa diganti dengan POQ juga jika ingin
            $product->save();

            // Simpan ke tabel poqs (riwayat)
            PoQ::create([
                'product_id' => $productId,
                'average_demand' => $product->average_demand,
                'demand_per_year' => $D,
                'ordering_cost' => $S,
                'unit_price' => $unitPrice,
                'holding_cost' => $H,
                'eoq' => $EOQ,
                'poq' => $POQ,
                'calculated_by' => auth()->id(),
                'notes' => 'Perhitungan otomatis sistem'
            ]);

            DB::commit();

            return response()->json([
                'product' => $product->name,
                'EOQ' => round($EOQ),
                'POQ' => round($POQ, 2),
                'Unit Price' => $unitPrice,
                'Ordering Cost (S)' => $S,
                'Holding Cost (H)' => round($H, 2),
                'message' => $product->name. " calculate successfully",
                'title'   => "Success"
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
                'log' => $th->getMessage()
            ], 422);
        }
    }

    public function getLatestUnitPrice($productId)
    {
        return DB::table('purchase_order_details')
            ->join('purchase_orders', 'purchase_order_details.purchase_order_id', '=', 'purchase_orders.id')
            ->where('purchase_order_details.product_id', $productId)
            ->orderBy('purchase_orders.order_date', 'desc')
            ->limit(1)
            ->value('price') ?? 10000; // fallback harga default
    }

    public function data(Request $request) {
        $data = PoQ::all();

        $table = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('product_id',function($row) {
            $product = Products::find($row->product_id);
            return $product->name;
        })
        ->addColumn('created_at', function($row) {
            $format = Carbon::parse($row->created_at)->format('d F Y, H:i');
            return $format;
        })
        ->addColumn('unit',function($row) {
            $product = Products::find($row->product_id);
            return $product->unit;
        })
        ->make(true);

        return $table;
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $poq = PoQ::findOrFail($id);
            $poq->delete();
            DB::commit();
            return response()->json(['message' => "POQ Delete Successfully",'title' => "Success"]);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th],422);
        }
    }

    public function export(Request $request) {
        try {
            $start      = $request->start_date;
            $end        = $request->end_date;
            $filename   = "POQ-".now()->format('Ymd').'.xlsx';
            return Excel::download(new PoQReportExport($start,$end), $filename);
            // Tambahkan header agar Content-Disposition bisa dibaca di JavaScript
            $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');
        } catch (\Exception $th) {
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th],422);
        }
    }
}
