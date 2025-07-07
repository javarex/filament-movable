<x-filament-panels::page>

  @once
  @push('styles')
  <style>
    body {
      font-family: sans-serif;
      margin: 0;
      padding: 0;
    }

    .toolbar {
      padding: 10px;
      background: #f0f0f0;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 50;
    }

    canvas {
      border: 1px solid #ccc;
      display: block;
      margin: 20px auto;
    }

    .canvas-container-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
      /* Remove height or max-height so the canvas can grow */
    }

    input[type="file"] {
      display: inline-block;
    }
  </style>
  @endpush
  @endonce
  <div class="flex">
  <input type="text" value="{{isset($name_of_document) ? $name_of_document : 'Untitled Document'}}" id="document-title" class="text-2xl font-bold border-t-0 border-x-0 border-b-2 focus:outline-0 focus:ring-0 ">
  </div>
  <div class="toolbar">
    <div class="flex flex-col">
      <label>Size</label>
      <x-filament::input.wrapper>
        <x-filament::input.select onchange="applyPresetSize(this.value)">
          <option value="">Custom</option>
          <option value="A4">A4</option>
          <option value="A5">A5</option>
          <option value="Letter">Letter</option>
          <option value="Legal">Legal</option>
        </x-filament::input.select>
      </x-filament::input.wrapper>
    </div>
    <div class="flex flex-col">
      <label>Width</label>
      <x-filament::input.wrapper>
        <x-filament::input
          id="canvasWidth"
          type="number"
          min="100"
          :value="800"
          x-on:change="resizeCanvas()" />
      </x-filament::input.wrapper>
    </div>
    <div class="flex flex-col">
      <label>Height</label>
      <x-filament::input.wrapper>
        <x-filament::input
          id="canvasHeight"
          type="number"
          min="100"
          :value="500"
          x-on:input="resizeCanvas()" />
      </x-filament::input.wrapper>
    </div>
    <div class="flex flex-col">
      <div>&nbsp;</div>
      <div>
        <x-filament::button outlined @click="addText()" tooltip="Add Rectangle">
          <span class="font-bold text-md">T</span>
        </x-filament::button>
      </div>
    </div>
    <div class="flex flex-col">
      <div>&nbsp;</div>
      <div>
        <x-filament::button outlined @click="addRect()" tooltip="Add Rectangle">
          <svg width="30" height="20" style="background-color:white;border:solid black 1px">
          </svg>
        </x-filament::button>
      </div>
    </div>
    <div class="flex flex-col">
      <label>Add Image</label>
      <x-filament::input.wrapper>
        <x-filament::input
          type="file"
          id="imgUploader"
          accept="image/*">

        </x-filament::input>
      </x-filament::input.wrapper>
    </div>
    <!-- <input type="file" id="imgUploader" accept="image/*"> -->


    <div class="flex flex-col">
      <label>Fill Color</label>
      <x-filament::input.wrapper>
        <x-filament::input
          id="fillColor"
          type="color"
          x-on:change="changeFillColor()">

        </x-filament::input>
      </x-filament::input.wrapper>
    </div>
    <div class="flex flex-col">
      <label for="">Font Size:</label>
      <x-filament::input.wrapper>
        <x-filament::input
          id="fontSize"
          type="number"
          min="5"
          value="20"
          x-on:input="changeFontSize($event.target.value)" />
      </x-filament::input.wrapper>
    </div>

    <div class="flex flex-col">
      <label for="textAlign">Text Align</label>
      <x-filament::input.wrapper>
        <x-filament::input.select
          x-on:change="changeTextAlign()"
          id="textAlign">
          <option value="left">Left</option>
          <option value="center">Center</option>
          <option value="right">Right</option>
          <option value="justify">Justify</option>
        </x-filament::input.select>
      </x-filament::input.wrapper>
    </div>
    <div class="flex flex-col">
      <label>Canvas BG</label>
      <x-filament::input.wrapper>
        <x-filament::input
          id="canvasBG"
          type="color"
          x-on:change="changeCanvasBG()">

        </x-filament::input>
      </x-filament::input.wrapper>
    </div>


    <div>
      <x-filament::button
        @click="exportHTML()"
        icon="heroicon-m-code-bracket">
        Export HTML
      </x-filament::button>
      <x-filament::button
        @click="exportImage()"
        icon="heroicon-m-photo">
        Export Image
      </x-filament::button>
      <x-filament::button
        @click="loadCanvas(3)"
        icon="heroicon-m-photo">
        Load Canvas
      </x-filament::button>
      <!-- <button onclick="exportImage()">Export Image</button>
    <button onclick="exportHTML()">Export HTML</button> -->
    </div>
  </div>
  <div class="canvas-container-wrapper flex justify-center bg-gray-300">
    <canvas id="canvas" width="800" height="500"></canvas>
  </div>
  @once
  @push('scripts')
  @livewireScripts
  <script src="{{asset('js/filament/assets/fabric.min.js')}}"></script>
  <script>
    window.currentCanvasId = @json($canvas_id);
    const canvas = new fabric.Canvas('canvas', {
      backgroundColor: '#ffffff'
    });

    function addText() {
      const text = new fabric.IText('Edit me', {
        left: 100,
        top: 100,
        fill: '#000',
        fontSize: 20,
        textAlign: 'left'
      });
      canvas.add(text);
      canvas.setActiveObject(text);
    }

    function addRect() {
      const rect = new fabric.Rect({
        left: 150,
        top: 150,
        fill: '#00aaff',
        width: 100,
        height: 100
      });
      canvas.add(rect);
      canvas.setActiveObject(rect);
    }

    function resizeCanvas() {
      const width = parseInt(document.getElementById('canvasWidth').value);
      const height = parseInt(document.getElementById('canvasHeight').value);
      if (!isNaN(width) && !isNaN(height)) {
        canvas.setWidth(width);
        canvas.setHeight(height);
        canvas.renderAll();
      }
    }

    function applyPresetSize(sizeName) {
      const sizes = {
        A4: {
          width: 794,
          height: 1123
        },
        A5: {
          width: 559,
          height: 794
        },
        Letter: {
          width: 816,
          height: 1056
        },
        Legal: {
          width: 816,
          height: 1344
        }
      };

      if (sizes[sizeName]) {
        document.getElementById('canvasWidth').value = sizes[sizeName].width;
        document.getElementById('canvasHeight').value = sizes[sizeName].height;
        resizeCanvas();
      }
    }

    function changeFillColor() {
      const color = document.getElementById('fillColor').value
      const obj = canvas.getActiveObject();
      if (obj) {
        obj.set('fill', color);
        canvas.renderAll();
      }
    }

    function changeFontSize(input) {
      const obj = canvas.getActiveObject();
      let size = parseInt(input);

      if (!obj || obj.type !== 'i-text') return;

      // fallback size if invalid
      if (isNaN(size) || size <= 0) {
        size = 20;
      }

      obj.set('fontSize', size);
      canvas.renderAll();
    }

    function changeTextAlign() {
      const align = document.getElementById('textAlign').value;
      const obj = canvas.getActiveObject();
      if (obj && obj.type === 'i-text') {
        obj.set('textAlign', align);
        canvas.renderAll();
      }
    }

    function changeCanvasBG() {
      const color = document.getElementById('canvasBG').value;
      canvas.setBackgroundColor(color, canvas.renderAll.bind(canvas));
    }

    function exportImage() {
      const dataURL = canvas.toDataURL({
        format: 'png',
        quality: 1
      });
      const win = window.open();
      win.document.write(`<img src="${dataURL}" />`);
    }

    function exportHTML1() {
      const svg = canvas.toSVG();
      const fullHtml = `<!DOCTYPE html> <html><head><meta charset="UTF-8"><title>Exported Fabric Canvas</title></head><body>${svg}</body></html>`;
      const win = window.open();
      win.document.open();
      win.document.write('<pre>' + fullHtml.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre>');
      win.document.close();
    }
    
    async function exportHTML() {
    try {
        const json = canvas.toJSON(); // ✅ this is the key change
        const canvasId = window.currentCanvasId ?? null;
        const backgroundColor = canvas.backgroundColor;
        const title = document.getElementById('document-title').value;

        const response = await fetch('/save-html', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                json: json, // send JSON, not HTML or image
                id: canvasId,
                background: backgroundColor,
                width: canvas.getWidth(),
                height: canvas.getHeight(),
                name: title
            }),
        });

        const result = await response.json();

        if (result.success) {
            alert('Canvas saved!');
            window.currentCanvasId = result.id;
        } else {
            alert('Failed to save canvas');
        }
    } catch (error) {
        console.error('Error saving canvas:', error);
    }
}

    async function exportHTML2() {
      try {
        const svg = canvas.toSVG();
        const canvasId = window.currentCanvasId ?? null;
        const backgroundColor = canvas.backgroundColor; // get background color
        const title = document.getElementById('document-title').value;
        console.log(title)
        const response = await fetch('/save-html', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            html: svg,
            id: canvasId,
            background: backgroundColor,
            width: canvas.getWidth(),
            height: canvas.getHeight(),
            name:title
          }),
        });

        const result = await response.json();

        if (result.success) {
          alert('Canvas saved!');
          window.currentCanvasId = result.id;
        } else {
          alert('Failed to save canvas');
        }
      } catch (error) {
        console.error('Error saving canvas:', error);
      }
    }


    async function loadCanvas1(id) {
      try {
        const response = await fetch(`/canvas/${id}`);
        const result = await response.json();

        if (result.html) {
          const width = result.width ?? 800;
          const height = result.height ?? 500;

          // Set size
          canvas.setWidth(width);
          canvas.setHeight(height);

          // Restore background color
          canvas.setBackgroundColor(result.background || '#ffffff', canvas.renderAll.bind(canvas));

          // Update inputs
          document.getElementById('canvasWidth').value = width;
          document.getElementById('canvasHeight').value = height;

          fabric.loadSVGFromString(result.html, (objects, options) => {
            canvas.clear(); // this also removes background, so re-set it below
            canvas.setBackgroundColor(result.background || '#ffffff', canvas.renderAll.bind(canvas));

            const obj = fabric.util.groupSVGElements(objects, options);
            canvas.add(obj);
            canvas.renderAll();
          });

          window.currentCanvasId = result.id;
        } else {
          alert('Canvas data is empty');
        }
      } catch (error) {
        console.error('Error loading canvas:', error);
      }
    }

async function loadCanvas(id) {
    try {
        const path = window.location.pathname;
        const segments = path.split('/').filter(Boolean);

        let response, result;

        if (segments.includes('edit')) {
            response = await fetch(`/canvas/${id}/edit`);
        } else {
            response = await fetch(`/canvas/${id}`);
        }

        result = await response.json(); // ✅ Now result is available

        if (result.json) {
            const width = result.width ?? 800;
            const height = result.height ?? 500;

            canvas.setWidth(width);
            canvas.setHeight(height);
            canvas.setBackgroundColor(result.background || '#ffffff', canvas.renderAll.bind(canvas));

            canvas.loadFromJSON(result.json, function () {
                canvas.renderAll();
            });

            window.currentCanvasId = result.id;
        } else {
            alert('Canvas data is empty');
        }
    } catch (error) {
        console.error('Error loading canvas:', error);
    }
}


    document.getElementById('imgUploader').addEventListener('change', function(e) {
      const reader = new FileReader();
      reader.onload = function(f) {
        fabric.Image.fromURL(f.target.result, function(img) {
          img.scaleToWidth(200);
          img.set({
            left: 200,
            top: 200
          });
          canvas.add(img);
          canvas.setActiveObject(img);
        });
      };
      reader.readAsDataURL(e.target.files[0]);
    });

    canvas.on('selection:created', updateControls);
    canvas.on('selection:updated', updateControls);

    function updateControls() {
      const obj = canvas.getActiveObject();
      if (obj && obj.type === 'i-text') {
        document.getElementById('textAlign').value = obj.textAlign || 'left';
        document.getElementById('fontSize').value = obj.fontSize || 20;
        document.getElementById('fillColor').value = rgbToHex(obj.fill);
      } else if (obj) {
        document.getElementById('fillColor').value = rgbToHex(obj.fill);
      }
    }

    function rgbToHex(rgb) {
      if (!rgb) return '#000000';
      const result = rgb.match(/\d+/g);
      if (!result) return rgb;
      return "#" + result.map(x => (+x).toString(16).padStart(2, '0')).join('');
    }

    function deleteSelected() {
      const obj = canvas.getActiveObject();
      if (obj) {
        canvas.remove(obj);
        canvas.discardActiveObject();
        canvas.renderAll();
      }
    }

    // event listener

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Delete') {
        deleteSelected();
      }
    });
    document.addEventListener('DOMContentLoaded', function () {
        if (window.currentCanvasId) {
            loadCanvas(window.currentCanvasId);
        }
    });

    let clipboardObject = null;

document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
        e.preventDefault();
        copyObject();
    }

    if ((e.ctrlKey || e.metaKey) && e.key === 'v') {
        e.preventDefault();
        pasteObject();
    }
});

function copyObject() {
    const activeObject = canvas.getActiveObject();
    if (!activeObject) return;

    activeObject.clone(function (cloned) {
        clipboardObject = cloned;
    });
}

function pasteObject() {
    if (!clipboardObject) return;

    clipboardObject.clone(function (clonedObj) {
        canvas.discardActiveObject();

        clonedObj.set({
            left: clonedObj.left + 20,
            top: clonedObj.top + 20,
            evented: true,
        });

        if (clonedObj.type === 'activeSelection') {
            // If multiple objects are selected
            clonedObj.canvas = canvas;
            clonedObj.forEachObject(function (obj) {
                canvas.add(obj);
            });
            clonedObj.setCoords();
        } else {
            canvas.add(clonedObj);
        }

        canvas.setActiveObject(clonedObj);
        canvas.requestRenderAll();
    });
}

document.addEventListener('keydown', function (e) {
    const obj = canvas.getActiveObject();
    if (!obj) return;

    let moved = false;
    const step = e.shiftKey ? 10 : 1; // move faster if shift is held

    switch (e.key) {
        case 'ArrowLeft':
            obj.left -= step;
            moved = true;
            break;
        case 'ArrowRight':
            obj.left += step;
            moved = true;
            break;
        case 'ArrowUp':
            obj.top -= step;
            moved = true;
            break;
        case 'ArrowDown':
            obj.top += step;
            moved = true;
            break;
    }

    if (moved) {
        obj.setCoords(); // update boundaries for selection/movement
        canvas.renderAll();
        e.preventDefault(); // prevent browser scrolling
    }
});


  </script>
  @endpush
  @endonce
</x-filament-panels::page>