<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = File::where('type', 'gallery')->get();
        $data['slugs'] = [];
        foreach ($gallery as $key => $galleryValue) {
            $data['slugs'][$key] = $galleryValue->slug;
        }
        $data['slugs'] = json_encode($data['slugs']);
        return view('admin.galleries.index', $data);
    }

    public function store(Request $request)
    {
        $files = File::where('type', 'gallery')->whereNotIn('slug', $request->gallery)->get();

        foreach ($files as $file) {
            $file->delete();
        }
        return $this->index();
    }
}
