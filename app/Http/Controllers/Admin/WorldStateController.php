<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\State;
use Illuminate\Http\Request;
use Validator;
use Flash;
use Input;

class WorldStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($country_code)
    {
        $rows = (Input::exists('row')? Input::get('row'): 15);
        $page = (Input::exists('page')? Input::get('page'): 1);
        $states = State::where('country_code', $country_code)->paginate($rows);

        return view('backend.divisions.world-state.listing', compact('states', 'page', 'rows', 'country_code'));
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
    public function update(Request $request, $country_code, $state_code)
    {
        $validator = validator::make($request->all(), [
            'EditWorldStateName' => 'required|string'
        ]);

        if($validator->fails()){
            Flash::error('Input is not valid, please try again');
            return redirect()->back();
        }
        $state = State::where('state_code', $state_code)->first();
        $state->name = Input::get('EditWorldStateName');
        if($state->save()){
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

    public function upload(uploadWorldStateRequest $request, $country_code){
        
        $file = Input::file('uploadWorldStatesField');
        if($file){
            Excel::load($file, function($reader) use($country_code){
                if($reader){
                    $rows = $reader->all();
                    $count = $rows->count();

                    foreach ($rows as $row) {
                        $validator = validator::make($row->all(), [
                            'state_code' => 'required|string',
                            'name' => 'required|string',
                            'capital' => 'required|string',
                        ]);
                        if($validator->fails()){
                            Flash::error('data in uploaded file is not valid, please try again');
                            return redirect()->back();
                        }
                        State::create([
                            'country_code' => $country_code,
                            'state_code' => $row->state_code,
                            'name' => $row->name,
                            'capital' => $row->capital,
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
