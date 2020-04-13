<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('invoice.invoices')->select('invoice_number','senders_company','discount','grand_total','invoice_date','is_paid','invoice_currency')
                ->where('invoices.client_id',auth()->user()->id)->paginate(5);
    }

    public function invoiceView($iNr){
        return DB::table('invoice.invoices')->select('*')->where('invoice_number',$iNr)->get();
    }

    public function invoiceItemsView($iNr){
        return DB::table('invoice.invoices_items')->select('*')->where('invoice_number',$iNr)->get();
    }

    public function checkForUnpaidInvoices(){
        return DB::table('invoice.invoices')->select('is_paid')->where('is_paid',0)->where('client_id',auth()->user()->id)->get();
    }

    public function invoiceNumber(){
        return DB::table('invoice.invoices')->orderBy('invoice_number','DESC')->first()->invoice_number;
    }

    public function billSendersInfo(){
        return DB::table('invoice.users')->select('users.id','users.name','users.companyName','users.email','invoice.bank_accounts.bank_account')
                ->leftJoin('invoice.bank_accounts','users.id','invoice.bank_accounts.user_id')
                ->where('users.id',auth()->user()->id)->get();
    }

    public function billToClientList(){
        return DB::table('invoice.users')->select('users.id','users.name','users.companyName','users.email','invoice.bank_accounts.bank_account')
                ->leftJoin('invoice.bank_accounts','users.id','invoice.bank_accounts.user_id')
                ->where('users.id','!=',auth()->user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'invoiceDate'             => 'required|date',
            'company.name'            => 'required|string',
            'company.contact'         => 'required|string',
            'client'                  => 'required|string',
            'subTotalCost'            => 'required|numeric',
            'totalDiscount'           => 'required|numeric',
            'discountRate'            => 'required|numeric',
            'totalTax'                => 'required|numeric',
            'taxRate'                 => 'required|numeric',
            'totalCost'               => 'required|numeric',
            'items.*.item_name'       => 'required|string|max:255',
            'items.*.unit_price'      => 'required|numeric|gt:0',
            'items.*.quantity'        => 'required|int|gt:0',
            'notes'                   => 'max:65535',
            'terms'                   => 'max:65535'
        ]);
        
        $itemsCount = count($request['items']);

        DB::table('invoice.invoices')->insert([
            'invoice_number'            => "INr".$request['invoiceNumber'],
            'invoice_date'              => $request['invoiceDate'],
            'senders_company'           => $request['company.name'],
            'senders_info'              => $request['company.contact'],
            'client_id'                 => $request['clientsId'],
            'client_info'               => $request['client'],
            'invoice_currency'          => $request->invoiceCurrency['name'],
            'subtotal'                  => $request['subTotalCost'],
            'discount'                  => $request['totalDiscount'],
            'discount_in_percentage'    => $request['discountRate'],
            'tax'                       => $request['totalTax'],
            'tax_in_percentage'         => $request['taxRate'],
            'grand_total'               => $request['totalCost'],
            'notes'                     => $request['notes'],
            'terms'                     => $request['terms']
        ]);

        for($i=0;$i<$itemsCount;$i++){
            DB::table('invoice.invoices_items')->insert([
                'invoice_number'        =>  "INr".$request['invoiceNumber'],
                'item_name'             =>  $request->items[$i]['item_name'],
                'price_per_unit'        =>  $request->items[$i]['unit_price'],
                'quantity'              =>  $request->items[$i]['quantity'],
                'total'                 =>  $request->items[$i]['singleItemAllCost']
            ]);
        }
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $iNr)
    {
        return DB::table('invoice.invoices')->where('invoice_number',$iNr)->update(['is_paid' => 1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

}
