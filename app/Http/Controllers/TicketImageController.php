<?php

namespace App\Http\Controllers;

use App\Models\TicketImage;
use Illuminate\Support\Facades\Storage;

class TicketImageController extends Controller
{
    public function show($id)
    {
        $image = TicketImage::findOrFail($id);
        $path = $image->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $absolutePath = Storage::disk('local')->path($path);

        return response()->file($absolutePath);
    }
}
