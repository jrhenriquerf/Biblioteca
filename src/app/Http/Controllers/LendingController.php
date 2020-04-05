<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\LendingController as SharedLending;
use Illuminate\Http\Request;

class LendingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lendings = $this->sharedLending->index();

        return view('lending.index', compact('lendings'));
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
        try {
            $lendings = $this->sharedLending->store($request);
        } catch (Exception $err) { }

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lending  $lending
     * @return \Illuminate\Http\Response
     */
    public function show(Lending $lending)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $lendings = $this->sharedLending->search($request);

        return view('lending.index', compact('lendings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lending  $lending
     * @return \Illuminate\Http\Response
     */
    public function edit(Lending $lending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lendingId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $lendingId)
    {
        try {
            $this->sharedLending->update($request, $lendingId);
        } catch (Exception $err) { }

        if ($request->input('home'))
            return redirect()->route('home');
        return redirect()->route('lending.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lending  $lending
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lending $lending)
    {
        //
    }
}
