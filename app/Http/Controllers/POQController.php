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

            $unitPrice  =   $product->selling_price ?? 0;

            // Biaya Pemesanan (S)
            $S  = $product->ordering_cost ?? 0;
            $H  = $product->holding_cost_percent ?? 0;
            $D  = $product->average_demand ?? 0;

            // Calculate POQ
            $TwoxS    =   2 * $S;
            $DxH      =   $D * $H;
            // $POQ      =   ceil(sqrt($TwoxS/$DxH));
            $POQ      = sqrt($TwoxS/$DxH);

            // Pastikan tidak ada pembagian nol // Calculate EOQ
            if ($D > 0 && $S > 0 && $H > 0) {
                $EOQ = sqrt((2 * $D * $S) / $H);
            } else {
                $EOQ = 0;
            }

            // Count Quantity
            $POQbulanan = "";
            $Q          = $D / $POQ;

            // Simpan ke tabel poqs (riwayat)
            PoQ::create([
                'product_id' => $productId,
                'average_demand' => $product->average_demand,
                'demand_per_year' => $D * 12,
                'ordering_cost' => $S,
                'unit_price' => $unitPrice,
                'holding_cost' =>  (int)$H,
                'eoq' => $EOQ,
                'poq' => $POQ,
                'calculated_by' => auth()->id(),
                'notes' => 'Perhitungan otomatis sistem',
                'S' => 2 * $S
            ]);

            DB::commit();

            return response()->json([
                'product' => $product->name,
                'EOQ' => $EOQ,
                'POQ' => $POQ,
                'Unit Price' => $unitPrice,
                'Ordering Cost (1)' =>  $TwoxS,
                'total_request (2)' => $DxH,
                'Holding Cost (H)' => $H,
                'Quantity'  => $Q,
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
        ->addColumn('holding_cost',function($row) {
            return "Rp ".number_format($row->holding_cost,0,',','.');
        })
        ->addColumn('unit_price',function($row) {
            return "Rp ".number_format($row->unit_price,0,',','.');
        })
        ->addColumn('ordering_cost',function($row) {
            return "Rp ".number_format($row->ordering_cost,0,',','.');
        })
        ->addColumn('product_id',function($row) {
            $product = Products::find($row->product_id);
            return $product->name;
        })
        ->addColumn('average_demand',function($row) {
            return number_format($row->average_demand,0,',','.');
        })
        ->addColumn('created_at', function($row) {
            $format = Carbon::parse($row->created_at)->format('d F Y, H:i');
            return $format;
        })
        ->addColumn('unit',function($row) {
            $product = Products::find($row->product_id);
            return $product->unit;
        })
        ->rawColumns(['holding_cost'])
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
