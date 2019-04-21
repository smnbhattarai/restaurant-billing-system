<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Bill;
use Illuminate\Support\Facades\Session;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bill::latest()->paginate(20);
        return view('bill.index')->with('bills', $bills);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bill.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer_name = $request->customer_name;
        $discount_per = $request->discount_per;
        $vat_per = $request->vat_per;
        $menu_price = $request->menu_price;
        $quantity = $request->quantity;
        $menu_id = $request->menu_id;

        if(count($menu_id) > 0) {
            foreach($menu_id as $key => $value) {
                $bill = new Bill();
                $bill->menu_id = $value;
                $bill->customer_name = $customer_name;
                $bill->quantity = $quantity[$key];
                $bill->menu_price = $menu_price[$key];

                $discount_amount = $menu_price[$key] * $discount_per / 100;
                $bill->discount = $discount_amount;

                $taxable_amount = $menu_price[$key] - $discount_amount;
                $bill->tax = $taxable_amount * $vat_per / 100;

                $final_price = $taxable_amount + ($taxable_amount * $vat_per / 100);
                $bill->final_price = $final_price;

                $bill->save();
            }

            Session::flash('success', 'Bill added successfully.');
            return redirect()->route('bill.index');

        }
        Session::flash('info', 'Well looks like your were not suppose to be there.');
        return redirect()->back();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getSuggestion(Request $request) {
        $input = $request->menu_item;
        $related_item = Menu::where('name', 'like', '%' . $input . '%')->get();
        return response()->json($related_item);
    }
}
