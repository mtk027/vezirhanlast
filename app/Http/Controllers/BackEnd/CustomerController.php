<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\File;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Customer::with('languages');

            return DataTables::of($data)
                ->editColumn('description.title', function ($data) {
                    return $data->language->title;
                })
                ->addColumn('created_at', function ($data) {
                    return Helper::date_format($data->created_at);
                })
                ->addColumn('action', function ($data) {
                    return Helper::get_action_buttons($data->id);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Helper::get_languages();
        return view('admin.customers.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $data = [];
        $validated = $request->validated();
        $created = Customer::create([
            'status'     => $validated['status'],
            'location'   => $validated['location']
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
                'customer_id' => $created->id,
                'title'       => $validated['title'][$lang->id],
                'short_description' => $validated['short_description'][$lang->id]
            ];
            CustomerDetail::create($data[$lang->code]);
        }
        if ($request->has_images == 1) {
        $created->files()->attach($file->id);}
        return redirect()->route('dashboard.customers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['data'] = Customer::with('languages')->find($id);
        $data['languages'] = Language::all();
        return view('admin.customers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request,$id)
    {
        $data = [];
        $validated = $request->validated();


        $customer_main = [
            'status' => $validated['status'],
            'location' => $validated['location'],
        ];

        $customer = Customer::find($id);

        $update = Customer::find($id)->update($customer_main);


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
                'short_description' => $validated['short_description'][$lang->id],
                'customer_id' => $id
            ];

            CustomerDetail::where('customer_id', $id)->where('language_id', $lang->id)->update($data[$lang->code]);
        }

        if ($request->has_images == 1) {
        $customer->files()->sync($file->id);}

        return redirect()->route('dashboard.customers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $slider = Customer::findOrFail($id);
            $slider->languages()->delete();
            $slider->delete();
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
