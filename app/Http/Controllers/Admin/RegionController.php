<?php

namespace App\Http\Controllers\Admin;

use App\CsiRegion;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRegionRequest;
use App\Http\Requests\UploadRegionRequest;
use Excel;
use Input;
use Laracasts\Flash\Flash;
use Request;
use Validator;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = (Input::exists('row')? Input::get('row'): 7);
        $page = (Input::exists('page')? Input::get('page'): 1);
        $regions = CsiRegion::paginate($rows);
        return view('backend.divisions.regions.listing', compact('regions', 'rows', 'page'));
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
    public function update(UpdateRegionRequest $request, $id)
    {
        $name = Input::get('EditRegionName');
        $region = CsiRegion::find($id);
        if(!$region){
            Flash::error('Error While Updating');
            return redirect()->back();
        }
        $region->name = $name;
        if($region->save()){
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

    public function upload(UploadRegionRequest $request){
        
        $file = Input::file('uploadRegionsField');
        if($file){
            Excel::load($file, function($reader){
                if($reader){
                    $rows = $reader->all();
                    $count = $rows->count();
                    foreach ($rows as $row) {
                        $validator = validator::make($row->all(), [
                            'country_code' => 'required|string',
                            'name' => 'required|string',
                        ]);
                        if($validator->fails()){
                            Flash::error('data in uploaded file is not valid, please try again');
                            return redirect()->back();
                        }
                        CsiRegion::create([
                            'country_code' => $row->country_code,
                            'name' => $row->name
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
