<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

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
        $menu_item = Menu::findOrFail($id);
        return view('menu.edit')->with('menu_item', $menu_item);
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
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'item_photo' => 'filled|mimes:jpeg,png|max:2048',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->price = $request->price;

        if($request->hasFile('item_photo')) {
            $image = $request->file('item_photo');
            $img_name = time() . '-' . $this->get_random_menu_item_code() . '.' . $image->getClientOriginalExtension();
            $destination = public_path('images');
            $image->move($destination, $img_name);
            $menu->item_photo = $img_name;
            File::delete('images/' . $request->item_photo);
        }

        if($menu->save()) {
            Session::flash('success', 'Menu item updated successfully.');
            return redirect()->route('menu.index');
        } else {
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Menu::findOrFail($id);
        if($item->delete()) {
            File::delete('images/' . $item->item_photo);
            Session::flash('success', 'Menu Item deleted successfully.');
        } else {
            Session::flash('error', 'Error: Failed to delete menu item.');
        }
        return redirect()->back();
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


    /**
     * @param Request $request
     * @return
     */
    public function getSuggestion(Request $request) {
        $input = $request->menu_item;
        $related_item = Menu::where('name', 'like', '%' . $input . '%')->get();
        return response()->json($related_item);
    }
}
