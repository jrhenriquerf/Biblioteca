<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Shared\LendingController as SharedLending;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Lending 
{
    private $sharedLending;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sharedLending = new SharedLending();
    }

    /**
     * Get home lendings
     *
     * @return mixed
     */
    public function index() {
        return response()->json($this->sharedLending->index(), 200);
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
            $lending = $this->sharedLending->store($request);

            return response()->json($lending, 201);
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
        $lendings = $this->sharedLending->search($request);

        return response()->json($lendings, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lendingId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $lendingId)
    {
        try {
            $this->sharedLending->update($request, $lendingId);

            return response()->json(null, 204);
        } catch (Exception $err) {
            return response()->json((object) [ 
                'message' => $err->getMessage() 
            ], 500);
        }
    }
}