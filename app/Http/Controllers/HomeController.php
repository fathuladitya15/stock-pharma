<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\PurchaseOrder;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user           =   auth()->user();
        $role           =   auth()->user()->role;

        if ($role == 'supplier') {
            $email         = $user->email;
            $suppllier     = Suppliers::where('email',$email)->first();
            $POwait        = PurchaseOrder::where('status','sent')->where('supplier_id',$suppllier->id)->count();
            $POconfirmed   = PurchaseOrder::where('status','confirmed')->where('supplier_id',$suppllier->id)->count();
            $POshipped     = PurchaseOrder::where('status','shipped')->where('supplier_id',$suppllier->id)->count();
            $POcomplete    = PurchaseOrder::where('status','completed')->where('supplier_id',$suppllier->id)->count();
            $POcanceled    = PurchaseOrder::where('status','cancelled')->where('supplier_id',$suppllier->id)->count();
            $totalPurchase = PurchaseOrder::where('supplier_id',$suppllier->id)->count();
            return view('page.dashboard.vSupplier',compact('totalPurchase','POwait','POshipped','POcomplete','POcanceled','POconfirmed'));
        }else {
            $countProduct   =   Products::count();
            $totalStock     =   Products::sum('stock');
            $totalPurchaseN =   DB::table('purchase_order_details')
            ->select(DB::raw('SUM(quantity * price) as total'))
            ->value('total');

            $totalPurchase  = "Rp " .number_format($totalPurchaseN,2,'.',',');
            $totalSuppliers = Suppliers::count();
            return view('page.dashboard.vAdmin',compact('countProduct','totalStock','totalPurchase','totalSuppliers'));
        }
    }
}
