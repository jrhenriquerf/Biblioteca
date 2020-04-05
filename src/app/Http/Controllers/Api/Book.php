<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Shared\BookController as SharedBook;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Book extends Controller 
{
    private $sharedBook;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sharedBook = new SharedBook();
    }

    /**
     * Get home books
     *
     * @return mixed
     */
    public function index() {
        return response()->json($this->sharedBook->index(), 200);
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
            $book = $this->sharedBook->store($request);

            return response()->json($book, 201);
        } catch (Exception $err) {
            return response()->json((object) [
                "message" => $err->getMessage()
            ], 500);
        }
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

        return response()->json($books, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $bookId
     * @return \Illuminate\Http\Response
     */
    public function edit(int $bookId)
    {
        try {
            $book = $this->sharedBook->edit($bookId);

            return response()->json($book, 200);
        } catch (\Throwable $th) {
            return response()->json((object) [ 
                'message' => 'Book not found' 
            ], 406);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $bookId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $bookId)
    {
        try {
            $this->sharedBook->update($request, $bookId);

            return response()->json(null, 204);
        } catch (Exception $err) {
            return response()->json((object) [ 
                'message' => $err->getMessage() 
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $bookId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $bookId)
    {
        try {
            $this->sharedBook->destroy($bookId);

            return response()->json(null, 204);
        } catch (Exception $err) {
            return response()->json((object) [ 
                'message' => $err->getMessage() 
            ], 500);
        }
    }
}