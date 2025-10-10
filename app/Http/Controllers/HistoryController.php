<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        // Aquí podrías cargar registros, p.ej. $items = History::latest()->paginate(15);
        return view('history', [
            'items' => [], // placeholder
        ]);
    }
}
