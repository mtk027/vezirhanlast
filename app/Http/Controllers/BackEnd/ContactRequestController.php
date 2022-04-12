<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = ContactRequest::query();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $actionBtn = '<a href="javascript:;" data-subject="' . $data->subject . '" data-user="' . $data->name . '"  data-message="' . $data->message . '" data-bs-custom-class="tooltip-dark" rel="tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Mesajı Gör" class="btn btn-sm btn-light-primary detail_modal"><i class="fas fa-search fs-4"></i></a>';

                    return $actionBtn;
                })

                ->addColumn('created_at', function ($data) {
                    return Helper::date_format($data->created_at);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.contact-request.index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactRequest  $contactRequest
     * @return \Illuminate\Http\Response
     */
    public function show(ContactRequest $contactRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactRequest  $contactRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactRequest $contactRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactRequest  $contactRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactRequest $contactRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactRequest  $contactRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactRequest $contactRequest)
    {
        //
    }
}
