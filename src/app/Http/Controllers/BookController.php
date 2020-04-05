<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\BookController as SharedBook;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $sharedBook;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->sharedBook = new SharedBook();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = $this->sharedBook->index();
        $authors = Author::withTrashed()->get();
        $selectedAuthor = [];

        return view('book.index', compact('books', 'authors', 'selectedAuthor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::get();
        $selectedAuthor = [];

        return view('book.add', compact('authors', 'selectedAuthor'));
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
            $books = $this->sharedBook->store($request);
        } catch (Exception $err) { }

        return redirect()->route('book.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
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
        $books = $this->sharedBook->search($request);
        $authors = Author::withTrashed()->get();

        if (empty($selectedAuthor)) {
            $selectedAuthor = [];
        }

        return view('book.index', compact('books', 'authors', 'selectedAuthor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $bookId
     * @return \Illuminate\Http\Response
     */
    public function edit(int $bookId)
    {
        try {
            $book = $this->sharedBook->edit($bookId);
            $authors = Author::get();
            $selectedAuthor = [];

            foreach ($book->authors as $key => $author) {
                $selectedAuthor[] = $author->pivot->author_id;
            }

            return view('book.edit', compact('book', 'authors', 'selectedAuthor'));
        } catch (Exception $err) {
            return redirect()->route('book.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        try {
            $this->sharedBook->update($request, $book->id);
            return redirect()->route('book.index');
        } catch (Exception $err) {
            return redirect()->route('book.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $bookId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $bookId)
    {
        try {
            $this->sharedBook->destroy($bookId);
        } catch (Exception $err) { }

        return redirect()->route('book.index');
    }
}
