<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\AuthorController as SharedAuthor;
use App\Models\Author;
use Illuminate\Http\Request;
use Validator;
use DB;

class AuthorController extends Controller
{
    private $sharedAuthor;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->sharedAuthor = new SharedAuthor();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = $this->sharedAuthor->index();
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
        $this->sharedAuthor->store($request);
        
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
        $authors = $this->sharedAuthor->search($request);
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
        $author = $this->sharedAuthor->edit($authorId);

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
        try {
            $this->sharedAuthor->update($request, $author->id);

            return redirect()->route('author.index');
        } catch (Exception $err) {
            return view('author.edit', compact('author'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $authorId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $authorId)
    {
        try {
            $this->sharedAuthor->destroy($authorId);
        } catch (Exception $err) { }

        return redirect()->route('author.index');
    }
}
