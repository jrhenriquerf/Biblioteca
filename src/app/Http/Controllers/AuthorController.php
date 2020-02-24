<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Validator;
use DB;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Author::paginate(10);
        return view('author.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('author.add');
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
            'name' => 'required|min:3|max:255',
        ]);

        if (!$validator->fails()) {
            $author = Author::create([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
            ]);
        }

        return redirect()->route('author.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
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

        $authors = DB::table('authors')
            ->whereNull('deleted_at')
            ->where(function ($authors) use ($text) {
                return $authors->where('name', 'like', "%{$text}%")
                    ->orWhere('surname', 'like', "%{$text}%");
            })
            ->get();

        return view('author.index', compact('authors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $authorId
     * @return \Illuminate\Http\Response
     */
    public function edit(int $authorId)
    {
        $author = Author::find($authorId);

        if (!$author) {
            return redirect()->route('author.index');
        }

        return view('author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
        ]);

        $name = $request->input('name');
        $surname = $request->input('surname');

        $authorUpdt = Author::find($author->id);

        if (!$validator->fails()) {
            if (!empty($authorUpdt)) {
                $authorUpdt->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                ]);
    
                return redirect()->route('author.index');
            }
        }

        return view('author.edit', compact('author'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $authorId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $authorId)
    {
        $authorDlt = Author::find($authorId);

        if(!empty($authorId)) {
            $authorDlt->delete();
        }

        return redirect()->route('author.index');
    }
}
