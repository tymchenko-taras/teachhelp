<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Pattern;


class PatternController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $ability, $arguments = [])
    {
        $items = Pattern::latest()->paginate(5);
        return view('pattern.index',compact('items'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pattern.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'value' => 'required',
        ]);
        Pattern::create($request->all());
        return redirect()->route('pattern.index')
            ->with('success','Pattern created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Pattern::find($id);
        return view('pattern.show',compact('item'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Pattern::find($id);
        return view('pattern.edit',compact('item'));
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
        request()->validate([
            'name' => 'required',
            'value' => 'required',
        ]);
        Pattern::find($id)->update($request->all());
        return redirect()->route('pattern.index')
            ->with('success','Pattern updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pattern::find($id)->delete();
        return redirect()->route('pattern.index')
            ->with('success','Pattern deleted successfully');
    }
}