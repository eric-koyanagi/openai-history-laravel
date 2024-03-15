<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\DataRun;
use App\Models\History;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function buildPage(Request $request, string $runId) 
    {
        $run = DataRun::find($runId);
        if (!$run) {
            abort(404);
        }

        return view('history', ['run' => $run]);        
    }
}
