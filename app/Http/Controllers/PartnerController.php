<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Partner;


class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name=null, $per_page=null, $page=null)
    {
        /*$partners = Partner::cursor()->filter(function($partner) {
            return $partner->name.star
        });*/
        /*$name = $Input::get('name');
        $per_page = $Input::get('per_page');
        $page = $Input::get('page');*/
        echo $name;
         
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
        if (preg_match("/n\-*e\-*x\-*u\-*s/i", $request->name)){
            return "You cannot use 'Nexus' word and its derivative names for your partner name!";
        } else {
            if($request->has('photo')) {
                // store partner logo image in public/images folder
                $path = $request->photo->store('public/images');

                // get partner logo image from public/images folder
                $partner_logo = Storage::get($path);

                // crop image and save it again with partners name
                $file= Image::make(
                    $partner_logo)
                    ->crop('100','100')
                    ->save($request->name.'.png'); 
                // delete old saved image file.
                Storage::delete($path);
            }
            $partner = new Partner;
            $partner->name = $request->name;
            $partner->photo = 'public/'.$request->name.'.png';
            $partner->save();
        }
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
        if (preg_match("/n\-*e\-*x\-*u\-*s/i", $request->name)){
            return "You cannot use 'Nexus' word and its derivative names for your partner name!";
        } else {
            // get partner object
            $partner = Partner::find($id);

            if($request->has('photo')) {
                // delete old partner logo from filesystem
                \File::delete(basename($partner->photo));

                // store new partner logo image to public/images folder
                $new_logo_path = $request->photo->store('public/images');

                // get partner logo image from public/images folder
                $new_partner_logo = Storage::get($new_logo_path);

                // crop image and save it again with partners name
                $file= Image::make(
                    $new_partner_logo)
                    ->crop('100','100')
                    ->save($request->name.'.png'); 

                Storage::delete($new_logo_path);
            }

            $partner->name = $request->name;
            $partner->photo = 'public/'.$request->name.'.png';
            $partner->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partner = Partner::find($id);
        $partner->delete();
    }
}
