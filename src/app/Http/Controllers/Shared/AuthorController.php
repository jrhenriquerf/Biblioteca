<?php

namespace App\Http\Controllers\Shared;

use App\Models\Author;
use Illuminate\Http\Request;
use Validator;
use DB;
use Exception;

class AuthorController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Author::paginate(15);
        return $authors;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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

            return;
        }

        throw new Exception("Falha na validação dos dados");
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

        return $authors;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $authorId
     * @return \Illuminate\Http\Response
     */
    public function edit(int $authorId)
    {
        return Author::find($authorId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $authorId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
        ]);

        $name = $request->input('name');
        $surname = $request->input('surname');

        $authorUpdt = Author::find($authorId);

        if (!$validator->fails()) {
            if (!empty($authorUpdt)) {
                $authorUpdt->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                ]);

                return;
            }

            throw new Exception('Author not found');
        }

        throw new Exception('Validation data failed!');
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

            return;
        }

        throw new Exception("Author not found", 1);
    }
}
