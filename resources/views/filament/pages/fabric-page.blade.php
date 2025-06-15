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
        <input type="number" id="canvasWidth" value="800" min="100" onchange="resizeCanvas()">
    </div>
    <div class="flex flex-col">
        <label>Height</label>
        <x-filament::input.wrapper>
            <x-filament::input
                :id="'canvasHeight'" 
                value="500" 
                min="100" 
                onchange="resizeCanvas()"
                type="number"
            />
        </x-filament::input.wrapper>
    </div>
<label>
  Height:
  <input type="number" id="canvasHeight" value="500" min="100" onchange="resizeCanvas()">
</label>
  <button onclick="addText()">Add Text</button>
  <button onclick="addRect()">Add Rectangle</button>
  <input type="file" id="imgUploader" accept="image/*">

  <label>
    Fill Color:
    <input type="color" id="fillColor" onchange="changeFillColor(this.value)">
  </label>
  
  <label>
    Font Size:
    <input type="number" id="fontSize" value="20" onchange="changeFontSize(this.value)">
  </label>

  <label>
    Text Align:
    <select id="textAlign" onchange="changeTextAlign(this.value)">
      <option value="left">Left</option>
      <option value="center">Center</option>
      <option value="right">Right</option>
      <option value="justify">Justify</option>
    </select>
  </label>

  <label>
    Canvas BG:
    <input type="color" onchange="changeCanvasBG(this.value)">
  </label>

  <button onclick="exportImage()">Export Image</button>
  <button onclick="exportHTML()">Export HTML</button>
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
        A4: { width: 794, height: 1123 },
        A5: { width: 559, height: 794 },
        Letter: { width: 816, height: 1056 },
        Legal: { width: 816, height: 1344 }
    };

    if (sizes[sizeName]) {
        document.getElementById('canvasWidth').value = sizes[sizeName].width;
        document.getElementById('canvasHeight').value = sizes[sizeName].height;
        resizeCanvas();
    }
    }

    function changeFillColor(color) {
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

    function changeTextAlign(align) {
        const obj = canvas.getActiveObject();
        if (obj && obj.type === 'i-text') {
        obj.set('textAlign', align);
        canvas.renderAll();
        }
    }

    function changeCanvasBG(color) {
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

    function exportHTML() {
        const svg = canvas.toSVG();
        const fullHtml = `<!DOCTYPE html> <html><head><meta charset="UTF-8"><title>Exported Fabric Canvas</title></head><body>${svg}</body></html>`;
        const win = window.open();
        win.document.open();
        win.document.write('<pre>' + fullHtml.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre>');
        win.document.close();
    }

    document.getElementById('imgUploader').addEventListener('change', function (e) {
        const reader = new FileReader();
        reader.onload = function (f) {
        fabric.Image.fromURL(f.target.result, function (img) {
            img.scaleToWidth(200);
            img.set({ left: 200, top: 200 });
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
        }
    }

    function rgbToHex(rgb) {
        if (!rgb) return '#000000';
        const result = rgb.match(/\d+/g);
        if (!result) return rgb;
        return "#" + result.map(x => (+x).toString(16).padStart(2, '0')).join('');
    }
</script>
@endpush
@endonce
</x-filament-panels::page>
