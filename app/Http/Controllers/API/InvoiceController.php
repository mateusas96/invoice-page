<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\User;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Invoice::select('invoice_number','senders_company','discount','grand_total','invoice_created_date','invoice_payment_date','invoice_paid_at','is_paid','invoice_currency')
                ->where('invoices.client_id',auth()->user()->id)->get();
    }

    public function invoiceView($iNr){
        return Invoice::where('invoice_number',$iNr)->get();
    }

    public function invoiceItemsView($iNr){
        return InvoiceItems::where('invoice_number',$iNr)->get();
    }

    public function checkForUnpaidInvoices(){
        return Invoice::select('is_paid')->where('is_paid',0)->where('client_id',auth()->user()->id)->get();
    }

    public function invoiceNumber(){
        return Invoice::orderBy('invoice_number','DESC')->first()->invoice_number;
    }

    public function billSendersInfo(){
        return User::select('users.id','name','companyName','email','bank_accounts.bank_account')
                ->leftJoin('bank_accounts','users.id','bank_accounts.user_id')
                ->where('users.id',auth()->user()->id)->get();
    }

    public function billToClientList(){
        return User::select('users.id','name','companyName','email','bank_accounts.bank_account')
                ->leftJoin('bank_accounts','users.id','bank_accounts.user_id')
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
            'invoicePaymentDueDate'   => 'required|date|after_or_equal:'.Carbon::now()->toDateString(),
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

        Invoice::create([
            'invoice_number'            => "INr".$request['invoiceNumber'],
            'invoice_payment_date'      => $request['invoicePaymentDueDate'],
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
            InvoiceItems::create([
                'invoice_number'        =>  'INr'.$request['invoiceNumber'],
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
        return Invoice::where('invoice_number',$iNr)->update([
                                                            'is_paid' => 1,
                                                            'invoice_paid_at' => Carbon::now()->toDateString()
                                                        ]);
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
