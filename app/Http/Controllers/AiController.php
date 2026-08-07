<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;

class AiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function tanya(Request $request, AiService $ai)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:500',
        ]);

        $jawaban = $ai->tanya($request->pertanyaan);

        return response()->json([
            'jawaban'    => $jawaban,
            'pertanyaan' => $request->pertanyaan,
        ]);
    }
}