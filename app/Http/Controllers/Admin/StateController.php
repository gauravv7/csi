<?php

namespace App\Http\Controllers\Admin;

use App\CsiState;
use App\CsiRegion;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UploadStateRequest;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $rows = (Input::exists('row')? Input::get('row'): 15);
        $page = (Input::exists('page')? Input::get('page'): 1);
        $states = CsiState::filterByRegion($id)->paginate($rows);
        return view('backend.divisions.states.listing', compact('states', 'rows', 'page', 'id'));
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
    public function update(Request $request, $id, $state_id)
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

    public function upload(UploadStateRequest $request, $id){
        
        $file = Input::file('uploadStatesField');
        if($file){
            Excel::load($file, function($reader) use($id){
                if($reader){
                    $rows = $reader->all();
                    $count = $rows->count();
                    foreach ($rows as $row) {
                        $validator = validator::make($row->all(), [
                            'state_code' => 'required|string'
                        ]);
                        if($validator->fails()){
                            Flash::error('data in uploaded file is not valid, please try again');
                            return redirect()->back();
                        }
                        CsiState::create([
                            'csi_region_id' => $id,
                            'state_code' => $row->state_code
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
