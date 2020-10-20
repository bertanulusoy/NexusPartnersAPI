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
    public function index(Request $request)
    {
        if(isset($request->per_page) && isset($request->page)) {
            // if partner name set
            if(isset($request->name)) {
                $result = Partner::orderBy('id', 'asc')
                    ->where('name', 'like', $request->name.'%')
                    ->take($request->page)
                    ->paginate($request->per_page);
            } else { // otherwise just do pagination
                $result = Partner::orderBy('id', 'asc')
                    ->take($request->page)
                    ->paginate($request->per_page);
            }
        } else {
            // if per_page and page are not set, take default
            $result = Partner::orderBy('id', 'asc')->paginate(10);
            if(isset($request->name)) {
                $result = Partner::orderBy('id', 'asc')
                    ->where('name', 'like', $request->name.'%')
                    ->paginate(10);
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
        //"name" field can't contain the substring "Nexus" or its dashed derivatives
        if (preg_match("/n\-*e\-*x\-*u\-*s/i", $request->name)){
            return "You cannot use 'Nexus' word and its derivative names for your partner name!";
        } else {
            // if photo uploaded
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

            // insert new partner into the database
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
        // find partner by id
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
        //"name" field can't contain the substring "Nexus" or its dashed derivatives
        if (preg_match("/n\-*e\-*x\-*u\-*s/i", $request->name)){
            return "You cannot use 'Nexus' word and its derivative names for your partner name!";
        } else {

            // get partner by id
            $partner = Partner::find($id);

            // if photo uploaded
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

            // update partner info in the database
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
        // delete partner by id
        $partner = Partner::find($id);
        $partner->delete();
    }
}
