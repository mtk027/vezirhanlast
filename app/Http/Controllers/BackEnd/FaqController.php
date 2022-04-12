<?php

namespace App\Http\Controllers\BackEnd;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Faq::query();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $btn= '<a href="javascript:;" data-created_at="' . Helper::date_format($data->created_at) . '" data-message="' . $data->question . '" data-bs-custom-class="tooltip-dark" rel="tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Soruyu Gör" class="btn btn-sm btn-light-primary detail_modal"><i class="fas fa-search fs-4"></i></a>';

                    return $btn;
                })

                ->addColumn('created_at', function ($data) {
                    return Helper::date_format($data->created_at);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.faqs.index');
    }
}
