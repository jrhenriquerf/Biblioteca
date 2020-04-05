<?php

namespace App\Http\Controllers\Shared;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Validator;
use DB;
use Storage;

class BookController
{
    private $path = 'books';

    /**
     * Display a listing of the resource.
     *
     * @return Book
     */
    public function index()
    {
        return Book::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Book
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

            return $book;
        }

        throw new Exception("Validation failed", 1);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return object
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

        return $query->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $bookId
     * @return Book
     */
    public function edit(int $bookId)
    {
        $book = Book::find($bookId);

        if(!empty($book)) {
            return $book;
        }

        throw new Exception("Book not found", 1);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return null
     */
    public function update(Request $request, int $bookId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'authors' => 'required'
        ]);

        if(!$validator->fails()) {
            $book = Book::find($bookId);

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

                return;
            }

            throw new Exception("Book not found", 1);
        }

        throw new Exception("Validation failed", 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $bookId
     * @return null
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

            return;
        }

        throw new Exception("Book not found", 1);
    }
}
