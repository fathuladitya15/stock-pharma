<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;
use App\Models\SalesDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index() {

        return view('page.sales.vIndex');
    }

    public function store(Request $request) {

        $productIds = $request->input('product_id');
        $quantities = $request->input('qty');
        $prices     = $request->input('price');

        foreach ($productIds as $index => $productId) {
            $qtyRequested   = (int)$quantities[$index];
            $checkStock     = $this->checkStockProduct($productId,$qtyRequested);
        }


        if ($checkStock['status'] === false) {
            return response()->json([
                'message' => $checkStock['message'],
            ], 422);
        }

        DB::beginTransaction();
        $today = Carbon::now()->format('Ymd');
        $prefix = "INV-{$today}-";

        // Hitung jumlah invoice hari ini
        $countToday = Sales::whereDate('created_at', Carbon::today())->count() + 1;

        // Format jadi 4 digit
        $number     = str_pad($countToday, 4, '0', STR_PAD_LEFT);


        try {
            $sales  =   new Sales();
            $sales->invoice_number      = $prefix.$number;
            $sales->transaction_date    = Carbon::now();
            $sales->subtotal            = $this->removeRp($request->total_amount);
            $sales->total_amount        = $this->removeRp($request->total_amount);
            $sales->amount_paid         = $this->removeRp($request->amount_paid);
            $sales->change              = $this->removeRp($request->change);
            $sales->save();

            foreach ($productIds as $index => $productId) {
                $product        = Products::find($productId);
                $qtyRequested   = (int)$quantities[$index];
                $price          = $prices[$index];
                $salesDetail                = new SalesDetail();
                $salesDetail->sale_id       = $sales->id;
                $salesDetail->product_id    = $productId;
                $salesDetail->product_name  = $product->name;
                $salesDetail->price         = $price;
                $salesDetail->quantity      = $qtyRequested;
                $salesDetail->subtotal      = $qtyRequested * $price;
                $salesDetail->save();

                $product->stock -= $qtyRequested;
                $product->save();
            }

            DB::commit();
            return response()->json(['message' => "Purchase made successfully",'title' => "Success",'data' => $request->all()]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['title' => "error",'message'=> $th->getMessage(),'data' => $request->all()],422);
        }

    }

    function removeRp($price) {
        // Hilangkan "Rp", titik, dan spasi
        $cleanedHarga = preg_replace('/[^\d]/', '', $price);
        return $cleanedHarga;
    }

    function checkStockProduct($productId,$qtyRequested) {
        $product = Products::find($productId);
        if ($product->stock < $qtyRequested) {
            $r = [
                 'message' => 'Insufficient stock for '.$product->name,
                 'status'   => false
            ];

        }else {
            $r = [
                 'status'   => true,
                 'stoct'    => $product->stock,
                 'qty'  => $qtyRequested
            ];
        }
        return  $r;
    }

    function data(Request $request) {
        $sales = Sales::query();

        // Filter berdasarkan tanggal
        if ($request->has('date_filter')) {
            $filter = $request->input('date_filter');

            if ($filter === 'last_7_days') {
                $sales->whereBetween('transaction_date', [now()->subDays(7), now()]);
            } elseif ($filter === 'last_month') {
                $sales->whereMonth('transaction_date', now()->subMonth()->month)
                    ->whereYear('transaction_date', now()->year);
            } elseif ($filter === 'recent') {
                $sales->orderBy('created_at', 'desc');
            } elseif ($filter === 'asc') {
                $sales->orderBy('invoice_number', 'asc');
            } elseif ($filter === 'desc') {
                $sales->orderBy('invoice_number', 'desc');
            }
        }

        $dataTable = DataTables::of($sales)
        ->addIndexColumn()
        ->addColumn('total_amount',function($row) {
            return "Rp ".number_format($row->total_amount,2,',','.');
        })

        ->addColumn('transaction_date',function($row) {
            $date = Carbon::parse($row->transaction_date)->format('d F Y, H:i');
            return $date;
        })
        ->rawColumns(['total_amount'])
        ->make(true);

        return $dataTable;
    }

    public function show($id)
    {
        $sales = Sales::with('details.product')->find($id);
        $sales->transaction_date = Carbon::parse($sales->transaction_date)->format('l, d F Y, H:i');
        // Format setiap detail: price dan subtotal
        foreach ($sales->details as $detail) {
            $detail->price      = "Rp " . number_format($detail->price, 2, ',', '.');
            $detail->subtotal   = "Rp " . number_format($detail->subtotal, 2, ',', '.');
        }

        $sales->total_amount    = "Rp ".number_format($sales->total_amount,2,',','.');
        $sales->amount_paid     = "Rp ".number_format($sales->amount_paid,2,',','.');
        $sales->change          = "Rp ".number_format($sales->change,2,',','.');
        if (!$sales) {
            return response()->json([
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $sales
        ]);
    }

    function export(Request $request) {
        try {
            $start      = $request->start_date;
            $end        = $request->end_date;
            $filename   = "Sales-".now()->format('Ymd').'.xlsx';
            return Excel::download(new SalesExport($start,$end), $filename);
            // Tambahkan header agar Content-Disposition bisa dibaca di JavaScript
            $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');
        } catch (\Exception $th) {
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th],422);
        }
    }

    function print($id) {
        try {
            $sales = Sales::with('details.product')->find($id);
            foreach ($sales->details as $detail) {
                $detail->price      = "Rp " . number_format($detail->price, 2, ',', '.');
                $detail->subtotal   = "Rp " . number_format($detail->subtotal, 2, ',', '.');
            }
            $sales->total_amount    = "Rp ".number_format($sales->total_amount,2,',','.');
            $sales->amount_paid     = "Rp ".number_format($sales->amount_paid,2,',','.');
            $sales->change          = "Rp ".number_format($sales->change,2,',','.');
            return view('page.sales.invoice',compact('sales'));
        } catch (\Throwable $th) {
            return abort(500);
        }
    }

}
