<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Shared\AuthorController as SharedAuthor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Author extends Controller 
{
    private $sharedAuthor;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sharedAuthor = new SharedAuthor();
    }

    /**
     * Get home books
     *
     * @return mixed
     */
    public function index() {
        return response()->json($this->sharedAuthor->index(), 200);
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
            $author = $this->sharedAuthor->store($request);

            return response()->json(null, 201);
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
        $authors = $this->sharedAuthor->search($request);

        return response()->json($authors, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $authorId
     * @return \Illuminate\Http\Response
     */
    public function edit(int $authorId)
    {
        try {
            $author = $this->sharedAuthor->edit($authorId);

            return response()->json($author, 200);
        } catch (Exception $err) {
            return response()->json((object) [ 
                'message' => $err->getMessage()
            ], 406);
        }
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
        try {
            $this->sharedAuthor->update($request, $authorId);

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
     * @param  int $authorId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $authorId)
    {
        try {
            $this->sharedAuthor->destroy($authorId);

            return response()->json(null, 204);
        } catch (Exception $err) {
            return response()->json((object) [ 
                'message' => $err->getMessage() 
            ], 500);
        }
    }
}