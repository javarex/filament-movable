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
          min="100"
          :value="20"
          x-on:input="changeFontSize(this.value)" />
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
        icon="heroicon-m-code-bracket"
      >
          Export HTML
      </x-filament::button>
      <x-filament::button
        @click="exportImage()"
          icon="heroicon-m-photo"
      >
          Export Image
      </x-filament::button>
      <x-filament::button
        @click="loadCanvas(4)"
          icon="heroicon-m-photo"
      >
          Load Canvas
      </x-filament::button>
      <!-- <button onclick="exportImage()">Export Image</button>
    <button onclick="exportHTML()">Export HTML</button> -->
    </div>
  </div>
  <div class="canvas-container-wrapper flex justify-center">
    <canvas id="canvas" width="800" height="500"></canvas>
  </div>
  @once
  @push('scripts')
  @livewireScripts
  <script src="{{asset('js/filament/assets/fabric.min.js')}}"></script>
  <script>
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

    function changeFontSize(size) {
      const obj = canvas.getActiveObject();
      if (obj && obj.type === 'i-text') {
        obj.set('fontSize', parseInt(size));
        canvas.renderAll();
      }
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
    // 1. Get canvas SVG string (for HTML export)
    const svg = canvas.toSVG();

    // 2. Get canvas object JSON (for reloading later)
    const canvasJson = canvas.toJSON();

    // 3. Get selected paper size (A4, A5, etc.)
    const presetSize = document.querySelector('select[onchange="applyPresetSize(this.value)"]').value;

    // 4. Package all data into an object
    const fullData = {
      svg: svg,
      json: canvasJson,
      presetSize: presetSize,
    };

    // 5. Send to your Laravel route
    const response = await fetch('/save-html', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(fullData),
    });

    // 6. Handle response
    if (response.ok) {
      const data = await response.json();
      alert('Canvas saved successfully!');
    } else {
      console.error('Failed to save:', await response.text());
    }
  } catch (error) {
    console.error('Export failed:', error);
  }
}


    async function loadCanvas(id) {
  try {
    const res = await fetch(`/canvas/${id}`);
    const { html } = await res.json();
    const json = JSON.parse(html);

    canvas.loadFromJSON(json, () => {
      canvas.renderAll();
      window.canvasRecordId = id;

      // Sync toolbar controls
      document.getElementById('canvasWidth').value = canvas.getWidth();
      document.getElementById('canvasHeight').value = canvas.getHeight();

      const bgColor = canvas.backgroundColor;
      document.getElementById('canvasBG').value = rgbToHex(bgColor);

      updateControls(); // Sync fillColor, fontSize, textAlign
    });

  } catch (e) {
    console.error('Failed to load:', e);
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
      if (e.key === 'Delete' || e.key === 'Backspace') {
        deleteSelected();
      }
    });
  </script>
  @endpush
  @endonce
</x-filament-panels::page>