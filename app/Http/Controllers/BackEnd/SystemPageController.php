<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\SystemPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SystemPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        SystemPage::truncate();
        $routeName = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            dd($action['as']);
            if (array_key_exists('as', $action)) {
                if (Str::contains($action['as'], 'system')) {
                    $routeName[] = $action['as'];
                }
            }
        }
        foreach ($routeName as $value) {
            SystemPage::create([
                'language_id' => Str::between($value, 'system.', '.'),
                'title' => Str::afterLast($value, '.'),
                'value' => $value
            ]);
        }
        return $routeName;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemPage  $systemPage
     * @return \Illuminate\Http\Response
     */
    public function show(SystemPage $systemPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemPage  $systemPage
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemPage $systemPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemPage  $systemPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemPage $systemPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemPage  $systemPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemPage $systemPage)
    {
        //
    }
}
