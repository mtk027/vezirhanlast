<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchCreateRequest;
use App\Models\Branch;
use App\Models\BranchDetail;
use App\Models\File;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Branch::with('language');
            return DataTables::of($data)
                ->editColumn('description.title', function ($data) {
                    return strip_tags($data->language->title);
                })
                ->addColumn('action', function ($data) {
                    return Helper::get_action_buttons($data->id);
                })
                ->addColumn('created_at', function ($data) {
                    return Helper::date_format($data->created_at);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.branches.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Helper::get_languages();
        return view('admin.branches.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchCreateRequest $request)
    {
        $data      = [];
        $validated = $request->validated();
        // dd($validated);

        $created   = Branch::create([
            'lat'          => $validated['lat'],
            'lng'          => $validated['lng'],
            'phone'        => $validated['phone'],
            'address'      => $validated['address'],
            'order_status' => 1,
            'status'       => $validated['status'],
        ]);

        if ($request->image) {
            $file = $request->image;
        } elseif ($request->video) {
            $file = $request->video;
        }
        $file = File::where('slug', $file)->first();

        foreach (Helper::get_languages() as $lang) {
            $data[$lang->code] = [
                'language_id'     => $lang->id,
                'branch_id'       => $created->id,
                'title'           => $validated['title'][$lang->id],
                'description'     => $validated['description'][$lang->id],
                'seo_title'       => $validated['seo_title'][$lang->id],
                'seo_description' => $validated['seo_description'][$lang->id],
                'seo_url'         => $validated['seo_url'][$lang->id]

            ];
            BranchDetail::create($data[$lang->code]);
        }
            $created->files()->attach($file->id);
        return redirect()->route('dashboard.branches.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['data'] = Branch::with('languages')->find($id);
        $data['languages'] = Language::all();
        return view('admin.branches.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(BranchCreateRequest $request, $id)
    {
        $data      = [];
        $validated = $request->validated();
        // dd($validated);

        $branch_main   = [
            'lat'          => $validated['lat'],
            'lng'          => $validated['lng'],
            'phone'        => $validated['phone'],
            'address'      => $validated['address'],
            'order_status' => 1,
            'status'       => $validated['status'],
        ];
        $branch = Branch::find($id);

        Branch::find($id)->update($branch_main);

        if ($request->image) {
            $file = $request->image;
        } elseif ($request->video) {
            $file = $request->video;
        }

        $file = File::where('slug', $file)->first();
        foreach (Helper::get_languages() as $lang) {
            $data[$lang->code] = [
                'language_id'     => $lang->id,
                'branch_id'       => $id,
                'title'           => $validated['title'][$lang->id],
                'description'     => $validated['description'][$lang->id],
                'seo_title'       => $validated['seo_title'][$lang->id],
                'seo_description' => $validated['seo_description'][$lang->id],
                'seo_url'         => $validated['seo_url'][$lang->id]

            ];
            BranchDetail::where('branch_id', $id)->where('language_id', $lang->id)->update($data[$lang->code]);
        }
            $branch->files()->sync($file->id);
        return redirect()->route('dashboard.branches.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $slider = Branch::findOrFail($id);
            $slider->languages()->delete();
            $slider->delete();
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
