<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Flash;

class WorldCountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = (Input::exists('row')? Input::get('row'): 15);
        $page = (Input::exists('page')? Input::get('page'): 1);
        $countries = Country::paginate($rows);
        return view('backend.divisions.world-country.listing', compact('countries', 'page', 'rows'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $alpha3_code)
    {
        $validator = validator::make($request->all(), [
            'EditWorldCountryName' => 'required|string'
        ]);

        if($validator->fails()){
            Flash::error('Input is not valid, please try again');
            return redirect()->back();
        }
        $country = Country::where('alpha3_code', $alpha3_code)->first();
        $country->name = Input::get('EditWorldCountryName');
        if($country->save()){
            Flash::success('Updated successfully!');
        }
        return redirect()->back();
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

    public function upload(uploadWorldCountryRequest $request){
        
        $file = Input::file('uploadWorldCountryField');
        if($file){
            Excel::load($file, function($reader){
                if($reader){
                    $rows = $reader->all();
                    $count = $rows->count();
                    foreach ($rows as $row) {
                        $validator = validator::make($row->all(), [
                            'alpha2_code' => 'required|string',
                            'alpha3_code' => 'required|string',
                            'name' => 'required|string',
                            'dial_code' => 'required|numeric',
                        ]);
                        if($validator->fails()){
                            Flash::error('data in uploaded file is not valid, please try again');
                            return redirect()->back();
                        }
                        Country::create([
                            'alpha2_code' => $row->alpha2_code,
                            'alpha3_code' => $row->alpha3_code,
                            'name' => $row->name,
                            'dial_code' => $row->name,
                        ]);
                    } //for
                } // if
            });
            Flash::error('uploaded successfully!');
            return redirect()->back();
        } else{
            Flash::error('File not uploaded! Please try again');
            return redirect()->back();
        }
    }
}
