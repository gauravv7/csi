<?php

namespace App\Http\Controllers\Admin;

use App\CsiChapter;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UploadChapterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Validator;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $state_code)
    {
        $rows = (Input::exists('row')? Input::get('row'): 15);
        $page = (Input::exists('page')? Input::get('page'): 1);
        $chapters = CsiChapter::filterByStateCode($state_code)->paginate($rows);
        return view('backend.divisions.chapters.listing', compact('chapters', 'rows', 'page', 'id', 'state_code'));
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
    public function update(Request $request, $id, $state_code, $chapter_id)
    {
        $validator = validator::make($request->all(), [
            'EditChapterName' => 'required|string'
        ]);

        if($validator->fails()){
            Flash::error('Input is not valid, please try again');
            return redirect()->back();
        }
        $chapter = CsiChapter::find($chapter_id);
        $chapter->name = Input::get('EditChapterName');
        if($chapter->save()){
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

    public function upload(UploadChapterRequest $request, $id, $state_code){
        
        $file = Input::file('uploadChaptersField');
        if($file){
            Excel::load($file, function($reader) use($id){
                if($reader){
                    $rows = $reader->all();
                    $count = $rows->count();
                    foreach ($rows as $row) {
                        $validator = validator::make($row->all(), [
                            'name' => 'required|string'
                        ]);
                        if($validator->fails()){
                            Flash::error('data in uploaded file is not valid, please try again');
                            return redirect()->back();
                        }
                        CsiChapter::create([
                            'name' => $row->name,
                            'state_code' => $state_code
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
