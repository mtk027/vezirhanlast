<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SliderCreateRequest;
use App\Models\File;
use App\Models\Language;
use App\Models\Slider;
use App\Models\SliderDetail;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Slider::with('language');

            return DataTables::of($data)
                ->editColumn('description.title', function ($data) {
                    return $data->language->title;
                })
                ->editColumn('status', function ($data) {
                    return Helper::get_status_button($data->status, $data->id, 'status');
                })
                ->addColumn('release_date', function ($data) {
                    return Helper::date_format($data->release_date);
                })
                ->addColumn('created_at', function ($data) {
                    return Helper::date_format($data->created_at);
                })
                ->addColumn('action', function ($data) {
                    return Helper::get_action_buttons($data->id);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.sliders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Helper::get_languages();
        return view('admin.sliders.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SliderCreateRequest $request)
    {
        $data = [];
        $validated = $request->validated();

        $created = Slider::create([
            'status' => $validated['status'],
            'release_date' => $validated['release_date'],
            'row_number' => $validated['row_number'],
        ]);


        if ($request->image) {
            $file = $request->image;
        } elseif ($request->video) {
            $file = $request->video;
        }
        $file = File::where('slug', $file)->first();
        foreach (Helper::get_languages() as $lang) {
            $data[$lang->code] = [
                'language_id' => $lang->id,
                'title' => $validated['title'][$lang->id],
                'sub_title' => $validated['sub_title'][$lang->id],
                'short_description' => $validated['short_description'][$lang->id],
                'button_title' => $validated['button_title'][$lang->id],
                'slider_id' => $created->id
            ];

            SliderDetail::create($data[$lang->code]);
        }
            $created->files()->attach($file->id);

        return redirect()->route('dashboard.sliders.index');
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
        $data['data'] = Slider::with('languages')->find($id);
        $data['languages'] = Language::all();
        return view('admin.sliders.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SliderCreateRequest $request, $id)
    {
        $data = [];
        $validated = $request->validated();


        $slide_main = [
            'status' => $validated['status'],
            'release_date' => $validated['release_date'],
            'row_number' => $validated['row_number'],
        ];

        $slider = Slider::find($id);

        $update = Slider::find($id)->update($slide_main);


        if ($request->image) {
            $file = $request->image;
        } elseif ($request->video) {
            $file = $request->video;
        }

        $file = File::where('slug', $file)->first();

        foreach (Helper::get_languages() as $lang) {
            $data[$lang->code] = [
                'language_id' => $lang->id,
                'title' => $validated['title'][$lang->id],
                'sub_title' => $validated['sub_title'][$lang->id],
                'short_description' => $validated['short_description'][$lang->id],
                'button_title' => $validated['button_title'][$lang->id],
                'slider_id' => $id
            ];

            SliderDetail::where('slider_id', $id)->where('language_id', $lang->id)->update($data[$lang->code]);
        }

            $slider->files()->sync($file->id);

        return redirect()->route('dashboard.sliders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $slider = Slider::findOrFail($id);
            $slider->languages()->delete();
            $slider->delete();
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
