<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyRequest;
use App\Models\File;
use App\Models\Language;
use App\Models\Property;
use App\Models\PropertyDetail;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Property::with('language');

            return DataTables::of($data)
                ->editColumn('description.title', function ($data) {
                    return $data->language->title;
                })
                ->editColumn('status', function ($data) {
                    return Helper::get_status_button($data->status, $data->id, 'status');
                })
                ->addColumn('action', function ($data) {
                    return Helper::get_action_buttons($data->id);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('admin.properties.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Helper::get_languages();
        return view('admin.properties.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyRequest $request)
    {
        $data = [];

        $validated = $request->validated();
        $created = Property::create([
            'status'     => $validated['status'],
            'row_number' => $validated['row_number'],
            'color'      => $validated['color']
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
                'property_id' => $created->id,
                'title'       => $validated['title'][$lang->id],
                'description' => $validated['description'][$lang->id]
            ];
                PropertyDetail::create($data[$lang->code]); 
        }

        $created->files()->attach($file->id);
        return redirect()->route('dashboard.properties.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['data']      = Property::with('languages')->find($id);
        $data['languages'] = Language::all();
        return view('admin.properties.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyRequest $request,$id)
    {
        $data = [];

        $validated = $request->validated();
        $property_main = [
            'status'     => $validated['status'],
            'row_number' => $validated['row_number'],
            'color'      => $validated['color']
        ];
        $property = Property::find($id);
        Property::find($id)->update($property_main);

        if ($request->image) {
            $file = $request->image;
        } elseif ($request->video) {
            $file = $request->video;
        }

        $file = File::where('slug', $file)->first();
        foreach (Helper::get_languages() as $lang) {
            $data[$lang->code] = [
                'language_id' => $lang->id,
                'property_id' => $id,
                'title'       => $validated['title'][$lang->id],
                'description' => $validated['description'][$lang->id]
            ];
            PropertyDetail::where('property_id',$id)->where('language_id',$lang->id)->update($data[$lang->code]);
        }
        $property->files()->sync($file->id);
        return redirect()->route('dashboard.properties.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $slider = Property::findOrFail($id);
            $slider->languages()->delete();
            $slider->delete();
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
