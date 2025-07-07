<?php

namespace App\Http\Controllers;

use App\Models\Canvas;
use App\Models\ChildTable;
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
        $json = json_decode($canvasExport->json, true);

        // Default placement for $Content
        $placeholderY = 100;
        $placeholderX = 50;

        // Detect placeholder coordinates from existing JSON
        if (isset($json['objects']) && is_array($json['objects'])) {
            foreach ($json['objects'] as $obj) {
                if (isset($obj['text']) && $obj['text'] === '$Content') {
                    $placeholderY = $obj['top'] ?? $placeholderY;
                    $placeholderX = $obj['left'] ?? $placeholderX;
                    break;
                }
            }
        }

        $canvasWidth = $canvasExport->width ?? 800;
        $rightX = $canvasWidth - 50;

        $rowGap = 30; // vertical gap between each group
        $text1_2_Yoffset = 0; // base Y
        $text3_Yoffset = 12;  // slightly below text1/text2

        // Generate content lines
        $content = ChildTable::where('canvas_id', $canvasExport->id)
            ->get()
            ->flatMap(function ($item, $index) use ($placeholderX, $rightX, $placeholderY, $rowGap, $text1_2_Yoffset, $text3_Yoffset) {
                $baseY = $placeholderY + ($index * $rowGap);

                return [
                    // text1 (left)
                    [
                        'type' => 'i-text',
                        'left' => $placeholderX,
                        'top' => $baseY + $text1_2_Yoffset,
                        'text' => $item->text1,
                        'fontSize' => 12,
                        'fill' => '#000000',
                        'fontWeight' => 'normal',
                    ],
                    // text2 (right, bold)
                    [
                        'type' => 'i-text',
                        'left' => $rightX,
                        'top' => $baseY + $text1_2_Yoffset,
                        'text' => $item->text2,
                        'fontSize' => 12,
                        'fill' => '#000000',
                        'fontWeight' => 'bold',
                        'textAlign' => 'right',
                        'originX' => 'right',
                    ],
                    // text3 (below)
                    [
                        'type' => 'i-text',
                        'left' => $placeholderX,
                        'top' => $baseY + $text3_Yoffset,
                        'text' => $item->text3,
                        'fontSize' => 12,
                        'fill' => '#000000',
                        'fontWeight' => 'normal',
                        'lineHeight' => 0.8,
                    ],
                ];
            })
            ->values();

        $replacements = [
            '$name' => 'Raymart Itanong',
            '$address' => 'Poblacion, Compostela, Davao de Oro',
            '$date' => now()->format('F d, Y'),
            '$Content' => $content,
        ];

        // Replace placeholders in text only
        if (isset($json['objects']) && is_array($json['objects'])) {
            foreach ($json['objects'] as &$object) {
                if (in_array($object['type'], ['textbox', 'text', 'i-text'])) {
                    $object['text'] = $object['text'] ?? '';
                    foreach ($replacements as $key => $replacement) {
                        if (is_string($replacement)) {
                            $object['text'] = str_replace($key, $replacement, $object['text']);
                        }
                    }
                }
            }

            // Replace $Content object with generated rows
            $json['objects'] = collect($json['objects'])->flatMap(function ($object) use ($replacements) {
                if (isset($object['text']) && $object['text'] === '$Content') {
                    return $replacements['$Content'];
                }
                return [$object];
            })->toArray();
        }

        return response()->json([
            'id' => $canvasExport->id,
            'json' => $json,
            'width' => $canvasExport->width,
            'height' => $canvasExport->height,
            'background' => $canvasExport->background,
        ]);
    }

    public function edit(Canvas $canvas)
    {
      
       $json = json_decode($canvas->json, true);

        return response()->json([
            'id' => $canvas->id,
            'json' => $json,
            'width' => $canvas->width,
            'height' => $canvas->height,
            'background' => $canvas->background,
        ]);
    }

}
