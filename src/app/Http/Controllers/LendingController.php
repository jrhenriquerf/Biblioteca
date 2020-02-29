<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

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
        if(Auth::user()->isAdmin()) {
            $lendings = Lending::whereNull('date_finish')->paginate(10);
        } else {
            $lendings = Lending::whereNull('date_finish')->where('user_id', Auth::id())->paginate(10);
        }

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
        $validator = Validator::make($request->all(), [
            'book_id' => 'required',
        ]);

        if (!$validator->fails()) {
            $lending = Lending::create([
                'user_id' => Auth::id(),
                'date_start' => date('Y-m-d H-i-s'),
                'date_end' => date('Y-m-d', strtotime("+7 day", strtotime(date('Y-m-d')))) . " 23:59:59",
            ]);

            $lending->books()->sync($request->input('book_id'));
        }

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
        $text = $request->input('inputSearch');

        $query = Lending::whereNull('date_finish');

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if (!empty($text)) {
            $query->where(function ($authors) use ($text) {
                return $authors->whereHas('books', function($query) use ($text) {
                    return $query->where('books.title', 'like', "%{$text}%");
                })
                ->orWhere('lendings.date_start', 'like', "%{$text}%")
                ->orWhere('lendings.date_end', 'like', "%{$text}%");
            });
        };

        $lendings = $query->get();
        
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
        $lendingUpdt = Lending::find($lendingId);

        if (!empty($lendingUpdt)) {
            $lendingUpdt->update([
                'date_finish' => date('Y-m-d H-i-s'),
            ]);
        }

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
