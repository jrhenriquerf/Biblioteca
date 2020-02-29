<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use Validator;

class ApiController extends Controller {
    private $path = 'images/book';

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
    public function saveAuthor(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
        ]);

        if (!$validator->fails()) {
            $author = Author::create([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
            ]);

            if (!empty($author)) {
                return response()->json($author, 201);
            }

            return response()->json([
                "message" => "Failed to save author"
            ], 500);
        }

        return response()->json([
            "message" => "Validation fail"
        ], 500);
    }
}