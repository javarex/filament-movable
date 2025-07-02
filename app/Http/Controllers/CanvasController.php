<?php

namespace App\Http\Controllers;

use App\Models\Canvas;
use Illuminate\Http\Request;

class CanvasController extends Controller
{
    public function saveHtml1(Request $request)
    {
        $request->validate([
            'html' => 'required|string',
            'id' => 'nullable|integer',
        ]);

        if ($request->id) {
            $canvas = Canvas::findOrFail($request->id);
            $canvas->html = $request->html;
            $canvas->save();
        } else {
            $canvas = Canvas::create([
                'html' => $request->html,
            ]);
        }

        return response()->json([
            'success' => true,
            'id' => $canvas->id,
        ]);
    }

    public function saveHtml(Request $request)
    {
        $request->validate([
        'json' => 'required|array',
        'id' => 'nullable|integer',
        'background' => 'nullable|string',
        'width' => 'nullable|integer',
        'height' => 'nullable|integer',
    ]);

    $data = [
        'json' => json_encode($request->json),
        'background' => $request->background,
        'width' => $request->width,
        'height' => $request->height,
        'name' => $request->name
    ];

    if ($request->id) {
        $canvas = Canvas::findOrFail($request->id);
        $canvas->update($data);
    } else {
        $canvas = Canvas::create($data);
    }

    return response()->json([
        'success' => true,
        'id' => $canvas->id,
    ]);
    }

    public function show(Canvas $canvasExport)
    {
        return response()->json($canvasExport);
    }
}
