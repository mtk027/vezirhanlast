<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['data']      = Translation::all();
        $data['languages'] = Language::all();
        return view('admin.translations.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.translations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    
          
            $new_language = Language::create([
                'title' => $request->title,
                'code' => Str::lower($request->code)
            ]);
            $file = base_path('resources/lang/en.json');
            if (!file_exists($file)) {
                $file = base_path('resources/lang/tr.json');
            }
            $coppied = copy($file, base_path("resources/lang/{$new_language->code}.json"));
            if ($coppied) {
                Helper::save_data_database();
                return redirect()->route('dashboard.translations.index');
            } else {
                return redirect()->route('dashboard.settings.index');
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function show(Translation $translation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function edit(Translation $translation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $myObj = [];

        foreach ($request->key as $language => $key) {
            $dosya = base_path("resources/lang/{$language}.json");
            foreach ($request->key[$language] as $item => $text) {
                $myObj[$text] = $request->text[$language][$item];
            }
            file_put_contents($dosya, json_encode($myObj));
        }
        Helper::save_data_database();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Translation  $translation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Translation $translation)
    {
        //
    }
}
