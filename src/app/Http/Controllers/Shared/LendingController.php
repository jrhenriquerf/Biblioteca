<?php

namespace App\Http\Controllers\Shared;

use App\Models\Lending;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class LendingController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->isAdmin())
            return Lending::whereNull('date_finish')->paginate(10);

        return Lending::whereNull('date_finish')->where('user_id', Auth::id())->paginate(10);
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

            return $lending;
        }

        throw new Exception("Validation failed", 1);
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

        return $query->get();
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

            return;
        }

        throw new Exception("Lending not found", 1);
    }
}
