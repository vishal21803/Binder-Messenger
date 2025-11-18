
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>
   <form id="postform" class="addpost-container" action="uploadPost.php" method="POST" enctype="multipart/form-data">
  
  <div class="addpost-card">

    <h2 class="heading">Create New Post</h2>

    <!-- Image Upload Section -->
    <div class="upload-box" id="uploadBox">
      <input type="file" id="postFile" name="postFile" accept="image/*" required>
      <div class="upload-hint">Click to upload image</div>
      <img id="previewImg" class="preview-img" />
    </div>

    <!-- Caption -->
    <div class="field">
      <label>Caption</label>
      <textarea id="caption" name="caption" placeholder="Write a caption..." required></textarea>
    </div>

    <!-- Tags -->
    <div class="field">
      <label>Tags</label>
      <input type="text" id="tags" name="tags" placeholder="#travel #friends #sunset" />
    </div>

    <!-- Location -->
    <div class="field">
      <label>Location</label>
      <input type="text" id="location" name="location" placeholder="Add location" />
    </div>

    <!-- Filters -->
    <input type="hidden" name="filter" id="selectedFilter" value="none">

    <div class="filter-section">
      <h3>Filters</h3>
      <div class="filter-options" id="filters">
        <!-- BASIC FILTERS -->
    <div class="filter-item " data-filter="none">Original</div>
    <div class="filter-item" data-filter="grayscale(1)">B&W</div>
    <div class="filter-item" data-filter="sepia(0.8)">Sepia</div>
    <div class="filter-item" data-filter="invert(1)">Invert</div>

    <!-- BRIGHTNESS / CONTRAST -->
    <div class="filter-item" data-filter="brightness(1.2)">Bright</div>
    <div class="filter-item" data-filter="brightness(0.8)">Dim</div>
    <div class="filter-item" data-filter="contrast(1.3)">Contrast+</div>
    <div class="filter-item" data-filter="contrast(0.8)">Contrast-</div>

    <!-- COLOR POP / VIBRANT -->
    <div class="filter-item" data-filter="saturate(1.5)">Vibrant</div>
    <div class="filter-item" data-filter="saturate(2)">Color Pop</div>
    <div class="filter-item" data-filter="saturate(0)">Desaturate</div>

    <!-- WARM / COOL -->
    <div class="filter-item" data-filter="sepia(0.3) brightness(1.1)">Warm</div>
    <div class="filter-item" data-filter="brightness(1.1) saturate(1.3)">Sunny</div>
    <div class="filter-item" data-filter="hue-rotate(20deg)">Warm Tone</div>
    <div class="filter-item" data-filter="hue-rotate(200deg)">Cool Tone</div>

    <!-- INSTAGRAM-INSPIRED EFFECTS -->
    <div class="filter-item" data-filter="brightness(1.1) contrast(1.2) saturate(1.2)">Clarendon</div>
    <div class="filter-item" data-filter="brightness(1.1) saturate(1.3)">Juno</div>
    <div class="filter-item" data-filter="brightness(1.2) saturate(1.1)">Lark</div>
    <div class="filter-item" data-filter="contrast(1.1) sepia(0.2)">Gingham</div>
    <div class="filter-item" data-filter="brightness(0.9) sepia(0.4)">Retro</div>

    <!-- DARK / MOODY -->
    <div class="filter-item" data-filter="brightness(0.7) contrast(1.3)">Moody</div>
    <div class="filter-item" data-filter="brightness(0.6) contrast(1.4) saturate(0.8)">Dark Fade</div>

    <!-- CARTOON / FUN -->
    <div class="filter-item" data-filter="contrast(1.5) saturate(1.8)">Cartoonify</div>
    <div class="filter-item" data-filter="grayscale(0.5) sepia(0.2)">Vintage</div>
      </div>
    </div>
<canvas id="finalCanvas" style="display:none;"></canvas>
<input type="hidden" name="editedImage" id="editedImage">

    <!-- Upload Button -->
    <button class="post-btn" type="submit" id="submitPostBtn">Post</button>

  </div>

</form>


</main>

<script>
// === ELEMENTS ===
const fileInput = document.getElementById("postFile");
const uploadBox = document.getElementById("uploadBox");
const previewImg = document.getElementById("previewImg");
const filterItems = document.querySelectorAll(".filter-item");
const selectedFilter = document.getElementById("selectedFilter");
const postForm = document.getElementById("postform");

// CANVAS ELEMENTS
const finalCanvas = document.getElementById("finalCanvas");
const editedImageInput = document.getElementById("editedImage");

// === CLICK TO OPEN FILE PICKER ===
uploadBox.addEventListener("click", () => {
    fileInput.click();
});

// === PREVIEW IMAGE ===
fileInput.addEventListener("change", () => {
    if (fileInput.files && fileInput.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = "block";
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
});

// === FILTER SELECTION ===
filterItems.forEach(item => {
    item.addEventListener("click", function () {

        // remove active class
        filterItems.forEach(f => f.classList.remove("active"));
        this.classList.add("active");

        // apply filter to preview image
        const filterValue = this.getAttribute("data-filter");
        previewImg.style.filter = filterValue;

        // save for PHP
        selectedFilter.value = filterValue;
    });
});

// === BEFORE FORM SUBMIT -> MAKE FILTER PERMANENT ===
postForm.addEventListener("submit", function (e) {
    e.preventDefault(); // stop for processing

    if (!fileInput.files[0]) {
        alert("Please select an image");
        return;
    }

    const filter = selectedFilter.value;
    const img = previewImg;
    const canvas = finalCanvas;
    const ctx = canvas.getContext("2d");

    // set canvas size
    canvas.width = img.naturalWidth;
    canvas.height = img.naturalHeight;

    // apply filter permanently
    ctx.filter = filter;
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    // convert canvas → base64
    const finalImgURL = canvas.toDataURL("image/png");
    editedImageInput.value = finalImgURL;

    this.submit(); // submit to PHP now
});


</script>


<?php
include("footer.php");
}else{
    include("index.php");
}
?>
