<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostCrud;
use Validator;

class PostCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            return datatables()->of(PostCrud::latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('ajax_index');
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
        if ($request->user()->can('create-posts')) {
            $rules = array(
                'title'           =>  'required',
                'slug'            =>  'required|unique:post_cruds,slug',
                'description'     =>  'required',
                'image'  =>  'required|image|max:2048'
            );
    
            $error = Validator::make($request->all(), $rules);
    
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
    
            $image = $request->file('image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
    
            $form_data = array(
                'user_id'      =>  auth()->user()->id,
                'title'        =>  $request->title,
                'slug'         =>  $request->slug,
                'description'  =>  $request->description,
                'image'        =>  $new_name
            );
    
            PostCrud::create($form_data);
    
            return response()->json(['success' => 'Post Added successfully.']);    
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
        if ($request->user()->can('edit-posts')) {
            if(request()->ajax())
            {
                $data = PostCrud::findOrFail($id);
                return response()->json(['data' => $data]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    public function update(Request $request)
    {
        if ($request->user()->can('edit-posts')) 
        {
            $image_name = $request->hidden_image;
            $image = $request->file('image');
            if($image != '')
            {
                $rules = array(
                    'title'           =>  'required',
                    'slug'            =>  'required|unique:post_cruds,slug',
                    'description'     =>  'required',
                    'image'  =>  'required|image|max:2048'
                );
                $error = Validator::make($request->all(), $rules);
                if($error->fails())
                {
                    return response()->json(['errors' => $error->errors()->all()]);
                }

                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $image_name);
            }
            else
            {
                $rules = array(
                    'title'           =>  'required',
                    'slug'            =>  'required|unique:post_cruds,slug',
                    'description'     =>  'required'
                );

                $error = Validator::make($request->all(), $rules);

                if($error->fails())
                {
                    return response()->json(['errors' => $error->errors()->all()]);
                }
            }

            $form_data = array(
                'user_id'      =>  auth()->user()->id,
                'title'        =>  $request->title,
                'slug'         =>  $request->slug,
                'description'  =>  $request->description,
                'image'        =>  $image_name
            );
            PostCrud::whereId($request->hidden_id)->update($form_data);

            return response()->json(['success' => 'Post is successfully updated']);
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
        $data = PostCrud::findOrFail($id);
        $data->delete();
    }
}
