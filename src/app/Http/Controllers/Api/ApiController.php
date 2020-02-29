<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use Validator;
use Storage;

class ApiController extends Controller {
    /**
     * get all books
     *
     * @return mixed
     */
    public function books() {
        $books = Book::with(['authors', 'lendings'])->get();
        return response()->json($books);
    }

    /**
     * get all categories
     *
     * @return mixed
     */
    public function authors() {
        $authors = Author::get();
        return response()->json($authors);
    }

    /**
     * save a new category
     *
     * @param \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function saveBook(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'authors' => 'required'
        ]);

        if (!$validator->fails()) {
            $url = $request->input('image');

            $contents = file_get_contents($url);
            $namefile = time() . '.' . substr($url, strrpos($url, '.') + 1);
            Storage::disk("public")->put('books/' . $namefile, $contents);

            $book = Book::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image' => $namefile
            ]);

            $book->authors()->sync($request->input('authors'));

            if (!empty($book)) {
                return response()->json($book, 201);
            }

            return response()->json([
                "message" => "Failed to save book"
            ], 500);
        }

        return response()->json([
            "message" => "Validation fail"
        ], 500);
    }
}