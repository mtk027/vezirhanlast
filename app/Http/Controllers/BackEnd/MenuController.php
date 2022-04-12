<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\SystemPage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Menu::query();

            return DataTables::of($data)
                ->editColumn('title', function ($data) {
                    return $data->title;
                })
                ->editColumn('status', function ($data) {
                    return Helper::get_status_button($data->status, $data->id, 'status');
                })
                ->addColumn('created_at', function ($data) {
                    return Helper::date_format($data->created_at);
                })
                ->addColumn('action', function ($data) {
                    return Helper::get_action_buttons($data->id, false);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.menus.index');
    }
    public function dynamic_nestable(Request $request)
    {
        return $this->recursive($request->data);
    }
    public function recursive($array, $temp_item = 0)
    {
        foreach ($array as $key => $menu) {
            $item = MenuItem::find($menu["id"]);
            if ($temp_item == 0) {
                $item->parent_id = null;
                $item->row_number = $key + 1;
                $item->save();
            } else {
                $item->parent_id = $temp_item;
                $item->row_number = $key + 1;
                $item->save();
            }
            if (isset($menu['children'])) {
                $this->recursive($menu['children'], $item->id);
            }
        }
        return true;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['languages'] = Language::all();
        $data['data_menu'] = Menu::find($id);
        $data['data'] = MenuItem::where(['menu_id' => $id, 'parent_id' => null])->get();
        $data['branches'] = Branch::with('languages')->get();
        return view('admin.menus.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $value = $request->value[$request->type];
        $max_row_number = MenuItem::max('row_number');
        if ($request->type == 2) {
            $value = SystemPage::where(['title' => $value])->first()->id;
        }
        $menu_item = MenuItem::updateOrCreate(
            ['id' => $request->id],
            [
                'language_id' => $request->language_id,
                'menu_id' => $id,
                'title' => $request->title,
                'type' =>  $request->type,
                'value' =>  $value,
                'row_number' =>  $max_row_number + 1,
            ]
        );
        return redirect()->route('dashboard.menus.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
