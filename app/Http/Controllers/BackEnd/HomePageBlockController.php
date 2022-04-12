<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\HomePageBlock;
use App\Models\Language;
use Illuminate\Http\Request;

class HomePageBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['languages'] = Language::all();
        $data['blocks'] = HomepageBlock::get();
        return view('admin.homepage-blocks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach (Helper::get_languages() as $lang) {
            $hpBlock = HomePageBlock::where('language_id', $lang->id)->get();
            foreach ($hpBlock as  $hpBlockValue) {
                $block_item  = HomePageBlock::where([['key', $hpBlockValue->key], ['language_id', $lang->id]])->first();
                $jsonItem    = json_decode($block_item->arrJsonItem);
                $arrJsonItem = [];
                if ($jsonItem) {
                    foreach ($jsonItem as $key => $jsonValue) {
                        if ($key != "data") {
                            $arrJsonItem[$key] = $request[$key][$hpBlockValue->key][$lang->id];
                        }else{
                            foreach ($jsonValue as $valueKey => $jsonValueData) {
                                foreach ($jsonValueData as $itemKey => $item) {
                                    $arrJsonItem['data'][$valueKey][$itemKey] = $request['data_'.$itemKey][$valueKey][$hpBlockValue->key][$lang->id];
                                }
                            }
                        }
                    }
                }else{
                    $arrJsonItem = null;
                }
                $block_item->update([
                    'row_number' => $request->row_number[$hpBlockValue->key][$lang->id],
                    'json'       => $arrJsonItem
                ]);

            }
        }

        return redirect()->route('dashboard.homepage-blocks.index');
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HomePageBlock  $homePageBlock
     * @return \Illuminate\Http\Response
     */
    public function show(HomePageBlock $homePageBlock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HomePageBlock  $homePageBlock
     * @return \Illuminate\Http\Response
     */
    public function edit(HomePageBlock $homePageBlock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HomePageBlock  $homePageBlock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomePageBlock $homePageBlock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomePageBlock  $homePageBlock
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomePageBlock $homePageBlock)
    {
        //
    }
}
