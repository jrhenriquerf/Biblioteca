<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Validator;
use DB;
use Storage;

class BookController extends Controller
{
    private $path = 'books';
    
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
        $books = Book::paginate(15);
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'authors' => 'required'
        ]);

        if (!$validator->fails()) {
            $imageFileName = '';
            $image = $request->file('image');

            if (!empty($image)) {
                $imageFileName = time() . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs($this->path, $image, $imageFileName);
            }

            $book = Book::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $imageFileName
            ]);

            $book->authors()->sync($request->input('authors'));
        }

        return redirect()->route('book.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
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
        $text = $request->input('inputSearch');
        $selectedAuthor = $request->input('author');

        $query = new Book();

        if(!empty($text)) {
            $query = $query->where(function ($query) use ($text) {
                return $query->where('books.title', 'like', "%{$text}%")
                    ->orWhere('books.description', 'like', "%{$text}%");
            });
        }

        if(!empty($selectedAuthor)) {
            $query = $query->whereHas('authors', function ($query) use ($selectedAuthor) {
                return $query->whereIn('id', $selectedAuthor);
            });
        }

        $authors = Author::withTrashed()->get();
        $books = $query->get();

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
        $book = Book::find($bookId);

        if(!empty($book)) {
            $authors = Author::get();
            $selectedAuthor = [];

            foreach ($book->authors as $key => $author) {
                $selectedAuthor[] = $author->pivot->author_id;
            }

            return view('book.edit', compact('book', 'authors', 'selectedAuthor'));
        }

        return redirect()->route('book.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'authors' => 'required'
        ]);

        if(!$validator->fails()) {
            $book = Book::find($book->id);

            if(!empty($book)) {
                $imageFileName = '';
                $image = $request->file('image');
                $deleteImage = $request->input('deleteimage');
                $authors = $request->input('authors');
                $title = $request->input('title');
                $description = $request->input('description');
                $storage = Storage::disk('public');
        
                if(!empty($deleteImage)) {
                    $imagePath = $this->path . '/' . $deleteImage;
                    if($storage->exists($imagePath))
                        $storage->delete($imagePath);
                }

                if(!empty($image) && $image->isValid()) {
                    $imageFileName = time() . '.' . $image->getClientOriginalExtension();
                    $storage->putFileAs($this->path, $image, $imageFileName);
                }
                
                $book->update([
                    'title' => $title,
                    'description' => $description,
                    'image' => $imageFileName
                ]);
                
                $book->authors()->sync($authors);

                return redirect()->route('book.index');
            }
            
            return redirect()->route('book.edit');
        }

        return redirect()->route('book.edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $bookId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $bookId)
    {
        $book = Book::find($bookId);

        if(!empty($book)) {
            if(!empty($book->image)) {
                $imagePath = "{$this->path}/{$book->image}";
                if(file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $book->authors()->detach();
            $book->lendings()->detach();
            $book->delete();
        }

        return redirect()->route('book.index');
    }
}
