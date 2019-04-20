<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu_items = Menu::latest()->get();
        return view('menu.index')->with('menu_items', $menu_items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'item_photo' => 'filled|mimes:jpeg,png|max:2048',
        ]);

        $menu_item = new Menu();

        if($request->hasFile('item_photo')) {
            $image = $request->file('item_photo');
            $img_name = time() . '-' . $this->get_random_menu_item_code() . '.' . $image->getClientOriginalExtension();
            $destination = public_path('images');
            $image->move($destination, $img_name);
            $menu_item->item_photo = $img_name;
        }

        $menu_item->name = $request->name;
        $menu_item->price = $request->price;
        $menu_item->menu_code = $this->get_random_menu_item_code();

        if($menu_item->save()) {
            Session::flash('success', 'Menu item added successfully.');
            return redirect()->route('menu.index');
        } else {
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
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


    /**
     * Get random code for menu item
     */
    private function get_random_menu_item_code() {
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = strlen($string);
        $out = '';
        for($i = 0; $i < 6; $i++) {
            $out .= $string[rand(0, $length - 1)];
        }
        return $out;
    }
}
