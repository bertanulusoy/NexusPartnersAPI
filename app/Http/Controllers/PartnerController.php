<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Partner;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$partners = Partner::cursor()->filter(function($partner) {
            return $partner->name.star
        });*/
        $name = $Input::get('name');
        $per_page = $Input::get('per_page');
        $page = $Input::get('page');

        // pagination will be at that range
        if( isset($per_page) && isset($page) ){
            // if partner name set
            if(isset($name)) {
                $result = Partner::orderBy('id', 'asc')
                    ->where('name', 'like', name.'%')
                    ->take($page)
                    ->paginate($per_page)
                    ->get();
            } else { // otherwise just do pagination
                $result = Partner::orderBy('id', 'asc')
                    ->take($page)
                    ->paginate($per_page)
                    ->get();
            }
        } else {
            // if per_page and page are not set, take default
            $result = Partner::orderBy('id', 'asc')->paginate(10);
            // if partner name set, do it with deafult paginate number
            if(isset($name)) {
                $result = Partner::orderBy('id', 'asc')
                ->where('name', 'like', name.'%')
                ->paginate(10)
                ->get();
            }
        }
        return $result;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Partner::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
