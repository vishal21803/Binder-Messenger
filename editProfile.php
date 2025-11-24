


<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>


<div class="">

<?php @session_start();
$uname=$_SESSION["uname"];

include("connectdb.php");
$info=mysqli_query($con,"select * from user_info where uname='$uname'");
while($row=mysqli_fetch_array($info)){
    $fullname=$row["ufull"];
    $email=$row["uemail"];
    $dp=$row["ufile"];
    $website=$row["uwebsite"];
    $bio=$row["ubio"];
    $age=$row["uage"];
}

?>



<div class="header">


<img src="<?php echo("uploads/$dp");?>" alt=""  />

<h3><?php echo("$uname");?></h3>
<!-- Button to Open the Modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
  Change Profile Picture
</button>
</div>
<!-- DP Editor Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Body -->
      <div class="modal-body" style="padding:0;">

        <div style="max-height:85vh; overflow-y:auto; padding:20px; display:flex; justify-content:center;">
          <div class="editor" id="editorRoot">
            <!-- LEFT -->
            <div class="left">
              <div class="drop" id="dropZone" title="Click to upload or drag & drop">
                <span class="hint">Click or drop image here</span>
                <input type="file" id="fileInput" accept="image/*">
                <canvas id="editorCanvas" width="720" height="720"></canvas>
                <div class="crop-overlay"></div>
              </div>

              <div class="controls">
                <div class="row space">
                  <div><strong>Edit & crop</strong>
                    <div class="note">Position with mouse drag. Use zoom & rotate</div></div>
                  <div class="row">
                    <button class="btn small" id="resetBtn">Reset</button>
                    <button class="btn ghost small" id="fitBtn">Fit</button>
                  </div>
                </div>

                <div style="margin-top:10px">
                  <div class="row">
                    <div class="label">Zoom</div>
                    <input type="range" id="zoom" min="0.3" max="3" step="0.01" value="1">
                  </div>

                  <div class="row" style="margin-top:6px">
                    <div class="label">Rotate</div>
                    <button class="btn ghost small" id="rotLeft">⟲</button>
                    <button class="btn ghost small" id="rotRight">⟳</button>
                    <div style="flex:1"></div>
                    <div class="label" style="min-width:auto">° <span id="rotDeg">0</span></div>
                  </div>
                </div>

                <hr style="margin:12px 0; border:none; border-top:1px solid #f0f0f0">

                <div>
                  <div style="display:flex; justify-content:space-between; align-items:center">
                    <strong>Filters</strong>
                    <div class="note">Live preview</div>
                  </div>

                  <div style="margin-top:8px" class="row">
                    <div class="label">Brightness</div>
                    <input type="range" id="brightness" min="40" max="200" value="100">
                  </div>
                  <div class="row">
                    <div class="label">Contrast</div>
                    <input type="range" id="contrast" min="40" max="200" value="100">
                  </div>
                  <div class="row">
                    <div class="label">Saturate</div>
                    <input type="range" id="saturate" min="0" max="200" value="100">
                  </div>
                  <div class="row">
                    <div class="label">Grayscale</div>
                    <input type="range" id="grayscale" min="0" max="100" value="0">
                  </div>

                  <div style="margin-top:8px" class="preset-list" id="presets">
                    <div class="preset" data-preset="none">Original</div>
                    <div class="preset" data-preset="clarendon">Clarendon</div>
                    <div class="preset" data-preset="juno">Juno</div>
                    <div class="preset" data-preset="lark">Lark</div>
                    <div class="preset" data-preset="bw">B&W</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- RIGHT -->
            <div class="right">
              <div>
                <h1>Edit Profile Photo</h1>
                <p class="muted">Crop to circle, apply filters, rotate and save.</p>
              </div>

              <div class="preview">
                <div class="dp-preview" id="dpPreview"></div>
                <div class="meta">
                  <div style="font-weight:600">Your Profile Preview</div>
                  <div class="note">Shows how your final DP looks.</div>
                </div>
              </div>

              <strong>Actions</strong>
              <div class="actions" style="margin-top:10px;">
                <button class="btn" id="downloadBtn">Download Edited Image</button>

                <form id="dpForm" action="changeDP.php" method="post" enctype="multipart/form-data" style="margin-top:10px;">
                  <input type="hidden" name="editedDP" id="editedDP">
                  <button type="button" class="btn ghost" id="uploadBtn">Save DP</button>
                </form>
              </div>
            </div>

          </div> <!-- editor end -->
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<form method="post" action="updateProfile.php" class="edit-box">

  <h2 class="edit-title">Edit Profile</h2>

  <label>Name</label>
  <input name="fname" type="text" class="input-field" placeholder="Enter your name" value="<?php echo($fullname);?>">

  <label>Bio</label>
  <textarea name="bio" class="input-field textarea-field" placeholder="Write something..."><?php echo($bio);?>"</textarea>

  <label>Website</label>
  <input name="website" type="text" class="input-field" placeholder="https://yourwebsite.com" value="<?php echo($website);?>">

  <label>Age</label>
  <input name="age" type="number" class="input-field" placeholder="Your age" value="<?php echo($age); ?>">

  <button class="save-btn">Save Changes</button>

</form>



</div>


</main>

<script>
    
const fileInput = document.getElementById('fileInput');
const dropZone = document.getElementById('dropZone');
const canvas = document.getElementById('editorCanvas');
const ctx = canvas.getContext('2d', { willReadFrequently: true });

let img = new Image();
let state = {
  loaded:false,
  imgW:0, imgH:0,
  x:0, y:0, // position of image center relative to canvas center
  scale:1,
  rotation:0, // degrees
  filters:{
    brightness:100, contrast:100, saturate:100, grayscale:0
  }
};

function setCanvasSize(){
  // Set an internal high-res canvas for better exports but keep CSS size for layout
  const size = Math.min(1000, Math.max(480, Math.min(window.innerWidth-80, 900)));
  canvas.width = size;
  canvas.height = size;
}
setCanvasSize();
window.addEventListener('resize', ()=>{
  setCanvasSize();
  if(state.loaded) draw();
});

// helpers for filters
function cssFilterString(filters){
  return `brightness(${filters.brightness}%) contrast(${filters.contrast}%) saturate(${filters.saturate}%) grayscale(${filters.grayscale}%)`;
}

/* DRAW pipeline
 1. clear
 2. draw image transformed (translate to center + x/y, rotate, scale)
 3. draw overlay cropping mask on top for preview (we don't apply mask to final export)
*/
function draw(){
  if(!state.loaded) {
    // empty placeholder
    ctx.clearRect(0,0,canvas.width,canvas.height);
    ctx.fillStyle = '#efefef';
    ctx.fillRect(0,0,canvas.width,canvas.height);
    // small icon
    ctx.fillStyle = '#ddd';
    ctx.fillRect(canvas.width*0.2, canvas.height*0.2, canvas.width*0.6, canvas.height*0.6);
    updatePreview();
    return;
  }

  ctx.clearRect(0,0,canvas.width,canvas.height);

  // apply filters using ctx.filter (works in modern browsers)
  ctx.save();
  ctx.filter = cssFilterString(state.filters);

  // move to center
  ctx.translate(canvas.width/2 + state.x, canvas.height/2 + state.y);

  // rotation in radians
  ctx.rotate(state.rotation * Math.PI/180);

  // scale
  ctx.scale(state.scale, state.scale);

  // draw image centered at (0,0)
  const iw = state.imgW, ih = state.imgH;
  ctx.drawImage(img, -iw/2, -ih/2, iw, ih);

  ctx.restore();

  // draw circular crop overlay (visual only)
  // a dim mask outside a circle:
  ctx.save();
  ctx.beginPath();
  ctx.rect(0,0,canvas.width,canvas.height);
  const cw = canvas.width, ch = canvas.height;
  const circleSize = Math.min(cw,ch) * 0.66;
  const cx = cw/2, cy = ch/2;
  ctx.arc(cx, cy, circleSize/2, 0, Math.PI*2, true);
  ctx.fillStyle = 'rgba(0,0,0,0.45)';
  ctx.fill('evenodd');
  // inner circle border
  ctx.beginPath();
  ctx.arc(cx, cy, circleSize/2, 0, Math.PI*2);
  ctx.lineWidth = 4;
  ctx.strokeStyle = 'rgba(255,255,255,0.95)';
  ctx.stroke();
  ctx.restore();

  updatePreview();
}

// update the round preview on the right
function updatePreview(){
  const preview = document.getElementById('dpPreview');
  // We'll create a small canvas, export the circular region
  const exportCanvas = document.createElement('canvas');
  const size = 240;
  exportCanvas.width = size; exportCanvas.height = size;
  const ectx = exportCanvas.getContext('2d');

  // Fill transparent
  ectx.clearRect(0,0,size,size);

  // Draw the same scene scaled to this export canvas size
  // Map from main canvas to export canvas: compute scale factor
  const scaleFactor = size / canvas.width;

  // set filters
  ectx.filter = cssFilterString(state.filters);

  ectx.save();
  ectx.translate(size/2 + state.x * scaleFactor, size/2 + state.y * scaleFactor);
  ectx.rotate(state.rotation * Math.PI/180);
  ectx.scale(state.scale * scaleFactor, state.scale * scaleFactor);
  ectx.drawImage(img, -state.imgW/2, -state.imgH/2, state.imgW, state.imgH);
  ectx.restore();

  // Create circular mask for preview
  const mask = document.createElement('canvas');
  mask.width = size; mask.height = size;
  const mctx = mask.getContext('2d');
  mctx.clearRect(0,0,size,size);
  mctx.beginPath();
  mctx.arc(size/2, size/2, size/2, 0, Math.PI*2);
  mctx.closePath();
  mctx.fillStyle = '#fff';
  mctx.fill();

  // apply mask: draw exportCanvas onto masked canvas using destination-in
  ectx.globalCompositeOperation = 'destination-in';
  ectx.drawImage(mask,0,0);

  // set the preview image
  preview.style.backgroundImage = `url(${exportCanvas.toDataURL('image/png')})`;
  preview.style.backgroundSize = 'cover';
  preview.style.backgroundPosition = 'center';
}

// handle file selection / drop
function handleFile(file){
  if(!file) return;
  const reader = new FileReader();
  reader.onload = (ev) => {
    img = new Image();
    img.onload = () => {
      state.loaded = true;
      // set natural size for drawing — fit image so that the smaller side fits circle diameter
      const maxDisplay = Math.min(canvas.width, canvas.height) * 0.9; // base display size before zoom
      // set initial image display size proportionally
      const aspect = img.width / img.height;
      if(img.width >= img.height){
        state.imgW = maxDisplay * aspect;
        state.imgH = maxDisplay;
      } else {
        state.imgW = maxDisplay;
        state.imgH = maxDisplay / aspect;
      }
      // reset transforms
      state.x = 0; state.y = 0; state.scale = 1; state.rotation = 0;
      document.getElementById('zoom').value = 1;
      document.getElementById('rotDeg').textContent = '0';
      draw();
    }
    img.src = ev.target.result;
  }
  reader.readAsDataURL(file);
}

// drag & drop
dropZone.addEventListener('click', ()=> fileInput.click());
dropZone.addEventListener('dragover', (e)=>{ e.preventDefault(); dropZone.style.borderColor='#bbb'; });
dropZone.addEventListener('dragleave', ()=>{ dropZone.style.borderColor=''; });
dropZone.addEventListener('drop', (e)=>{
  e.preventDefault();
  dropZone.style.borderColor='';
  const f = e.dataTransfer.files && e.dataTransfer.files[0];
  if(f) handleFile(f);
});

fileInput.addEventListener('change', (e)=> handleFile(e.target.files[0]));

// pan (drag) logic
let isPanning = false;
let last = {x:0,y:0};
canvas.addEventListener('pointerdown', (e)=>{
  if(!state.loaded) return;
  isPanning = true;
  last.x = e.clientX; last.y = e.clientY;
  canvas.setPointerCapture(e.pointerId);
});
canvas.addEventListener('pointermove', (e)=>{
  if(!isPanning) return;
  const dx = e.clientX - last.x;
  const dy = e.clientY - last.y;
  // adjust by device pixel ratio? The canvas coordinate system is in pixels; fine as-is
  state.x += dx;
  state.y += dy;
  last.x = e.clientX; last.y = e.clientY;
  draw();
});
canvas.addEventListener('pointerup', (e)=>{ isPanning=false; canvas.releasePointerCapture(e.pointerId); });
canvas.addEventListener('pointercancel', ()=> isPanning=false);

// zoom via slider
const zoomEl = document.getElementById('zoom');
zoomEl.addEventListener('input', ()=>{
  state.scale = parseFloat(zoomEl.value);
  draw();
});

// rotate buttons
document.getElementById('rotLeft').addEventListener('click', ()=>{
  state.rotation = (state.rotation - 90) % 360;
  document.getElementById('rotDeg').textContent = state.rotation;
  draw();
});
document.getElementById('rotRight').addEventListener('click', ()=>{
  state.rotation = (state.rotation + 90) % 360;
  document.getElementById('rotDeg').textContent = state.rotation;
  draw();
});

// fit / reset
document.getElementById('resetBtn').addEventListener('click', ()=>{
  state.scale = 1; state.x=0; state.y=0; state.rotation=0;
  document.getElementById('brightness').value=100;
  document.getElementById('contrast').value=100;
  document.getElementById('saturate').value=100;
  document.getElementById('grayscale').value=0;
  Object.assign(state.filters, {brightness:100, contrast:100, saturate:100, grayscale:0});
  zoomEl.value = 1;
  document.getElementById('rotDeg').textContent = '0';
  draw();
});
document.getElementById('fitBtn').addEventListener('click', ()=>{
  // Fit so the image fully covers the circle area (cover)
  if(!state.loaded) return;
  // target circle diameter
  const circle = Math.min(canvas.width, canvas.height) * 0.66;
  const scaleX = circle / state.imgW;
  const scaleY = circle / state.imgH;
  const target = Math.max(scaleX, scaleY); // cover
  state.scale = target;
  zoomEl.value = state.scale;
  draw();
});

// filter controls
const filtersIds = ['brightness','contrast','saturate','grayscale'];
filtersIds.forEach(id=>{
  document.getElementById(id).addEventListener('input', (e)=>{
    state.filters[id] = parseFloat(e.target.value);
    draw();
  });
});

// presets
const presetDefs = {
  none: {brightness:100,contrast:100,saturate:100,grayscale:0},
  clarendon: {brightness:110,contrast:110,saturate:130,grayscale:0},
  juno: {brightness:105,contrast:108,saturate:150,grayscale:0},
  lark: {brightness:110,contrast:105,saturate:120,grayscale:0},
  bw: {brightness:100,contrast:115,saturate:0,grayscale:0}
};
document.getElementById('presets').addEventListener('click', (e)=>{
  const p = e.target.closest('.preset');
  if(!p) return;
  const name = p.dataset.preset;
  const preset = presetDefs[name] || presetDefs.none;
  Object.assign(state.filters, preset);
  // update sliders
  filtersIds.forEach(id => document.getElementById(id).value = state.filters[id]);
  draw();
});

// export: create square PNG where outside circle is transparent
// export: create square PNG where outside circle is transparent
function exportImage(size = 800){
  if(!state.loaded) return null;

  const out = document.createElement('canvas');
  out.width = size;
  out.height = size;
  const octx = out.getContext('2d');

  // Clear background → transparent
  octx.clearRect(0, 0, size, size);

  // Apply filters
  octx.filter = cssFilterString(state.filters);

  // Transform from editor canvas → output canvas
  const sf = size / canvas.width;
  octx.save();
  octx.translate(size/2 + state.x * sf, size/2 + state.y * sf);
  octx.rotate(state.rotation * Math.PI/180);
  octx.scale(state.scale * sf, state.scale * sf);
  octx.drawImage(img, -state.imgW/2, -state.imgH/2, state.imgW, state.imgH);
  octx.restore();

  /* -----------------------------
     ✔ FIXED MASK (NO BLACK CIRCLE)
     ----------------------------- */

  octx.save();
  octx.globalCompositeOperation = "destination-in";
  octx.beginPath();
  octx.arc(size/2, size/2, size/2, 0, Math.PI * 2);
  octx.closePath();
  octx.fill();
  octx.restore();

  return out;
}


// download
document.getElementById('downloadBtn').addEventListener('click', ()=>{
  const out = exportImage(1024);
  if(!out) return alert('No image loaded');
  const dataUrl = out.toDataURL('image/png');
  const a = document.createElement('a');
  a.href = dataUrl;
  a.download = 'profile_dp.png';
  a.click();
});

// upload to server
document.getElementById('uploadBtn').addEventListener('click', () => {
    const out = exportImage(1024);
    if (!out) return alert("No image loaded");

    const dataUrl = out.toDataURL("image/png");

    // Set base64 string into hidden field
    document.getElementById("editedDP").value = dataUrl;

    // Submit form
    document.getElementById("dpForm").submit();
});



// initialize empty
draw();
</script>


<?php
include("footer.php");
}else{
    include("index.php");
}
?>
