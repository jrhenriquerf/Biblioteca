<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Validator;
use DB;

class BookController extends Controller
{
    private $path = 'images/book';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::paginate(10);
        $authors = Author::get();
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
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move($this->path, $fileName);
                $imageFileName = $fileName;
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

        $query = DB::table('books')
            ->select(
                'books.id',
                'books.title',
                'books.image',
                'books.description',
                'authors.id as authors'
            )
            ->join('books_authors', 'books.id', '=', 'books_authors.book_id')
            ->join('authors', 'authors.id', '=', 'books_authors.author_id')
            ->groupBy('books.id', 'books.title', 'books.description', 'books.image', 'authors.id');
        
        if(!empty($text)) {
            $query->where(function ($query) use ($text) {
                return $query->where('books.title', 'like', "%{$text}%")
                    ->orWhere('books.description', 'like', "%{$text}%");
            });
        }

        if(!empty($selectedAuthor)) {
            $query->whereIn('authors.id', $selectedAuthor);
        }

        $authors = Author::get();
        $oldBooks = $query->get();
        $books = [];

        foreach ($oldBooks as $keyFather => $valueFather) {
            foreach ($oldBooks as $key => $value) {
                if ($keyFather === $key)
                    continue;

                if ($valueFather->id === $value->id && 
                    !in_array($valueFather->id, array_column($books, 'id'))) {
                    $oldBooks[$keyFather]->authors = array_merge([$valueFather->authors], [$value->authors]);
                    continue;
                }
            }

            if (!in_array($valueFather->id, array_column($books, 'id'))) {
                $books[] = $valueFather;
            }
        }

        foreach ($books as $key => $value) {
            if (is_array($value->authors)) {
                foreach ($value->authors as $k => &$author) {
                    $author = Author::find($author);
                }
                continue;
            }
                
            $value->authors = [Author::find($value->authors)];
        }

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
        
                if(!empty($deleteImage)) {
                    $imagePath = $this->path . '/' . $deleteImage;
                    if(file_exists($imagePath))
                        unlink($imagePath);
                }

                if(!empty($image) && $image->isValid()) {
                    $imageFileName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($this->path, $imageFileName);
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
