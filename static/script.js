// Minimal Music Login Form JavaScript
class MinimalMusicLoginForm {
    constructor() {
        this.form = document.getElementById('loginForm');
        this.emailInput = document.getElementById('email');
        this.passwordInput = document.getElementById('password');
        this.passwordToggle = document.getElementById('passwordToggle');
        this.submitButton = this.form.querySelector('.login-btn');
        this.successMessage = document.getElementById('successMessage');
        this.socialButtons = document.querySelectorAll('.social-btn');
                this.nameInput = document.getElementById('name');

        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.setupPasswordToggle();
        this.setupSocialButtons();
    }
    
    bindEvents() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.emailInput.addEventListener('blur', () => this.validateEmail());
        this.passwordInput.addEventListener('blur', () => this.validatePassword());
        this.emailInput.addEventListener('input', () => this.clearError('email'));
        this.passwordInput.addEventListener('input', () => this.clearError('password'));
    }
    
    setupPasswordToggle() {
        this.passwordToggle.addEventListener('click', () => {
            const type = this.passwordInput.type === 'password' ? 'text' : 'password';
            this.passwordInput.type = type;
            
            // Update icon (simple rotation effect)
            this.passwordToggle.style.transform = type === 'text' ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }
    
    setupSocialButtons() {
        this.socialButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const provider = button.classList.contains('google-btn') ? 'Google' : 'Apple';
                this.handleSocialLogin(provider, button);
            });
        });
    }

    validateName() {
        const name = this.nameInput.value.trim();
        
        
        if (!name) {
            this.showError('email', 'Name is required');
            return false;
        }
        
        if (!emailRegex.test(name)) {
            this.showError('name', 'Please enter your name');
            return false;
        }
        
        this.clearError('name');
        return true;
    }
    
    validateEmail() {
        const email = this.emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            this.showError('email', 'Email address is required');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            this.showError('email', 'Please enter a valid email address');
            return false;
        }
        
        this.clearError('email');
        return true;
    }
    
    validatePassword() {
        const password = this.passwordInput.value;
        
        if (!password) {
            this.showError('password', 'Password is required');
            return false;
        }
        
        if (password.length < 6) {
            this.showError('password', 'Password must be at least 6 characters long');
            return false;
        }
        
        this.clearError('password');
        return true;
    }
    
    showError(field, message) {
        const formGroup = document.getElementById(field).closest('.form-group');
        const errorElement = document.getElementById(`${field}Error`);
        
        formGroup.classList.add('error');
        errorElement.textContent = message;
        errorElement.classList.add('show');
    }
    
    clearError(field) {
        const formGroup = document.getElementById(field).closest('.form-group');
        const errorElement = document.getElementById(`${field}Error`);
        
        formGroup.classList.remove('error');
        errorElement.classList.remove('show');
        setTimeout(() => {
            errorElement.textContent = '';
        }, 200);
    }
    
  
   
  
    
}

// Initialize the form when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new MinimalMusicLoginForm();
});






const pickerContainer = document.getElementById('emojiPicker');
const emojiBtn = document.getElementById('emojiBtn');

const picker = new EmojiMart.Picker({
  set: 'twitter',
  theme: 'light',
  emojiSize: 24,
  perLine: 8,
  previewPosition: 'none',
  searchPosition: 'top',
  onEmojiSelect: (emoji) => {
    msgInput.value += emoji.native;
    msgInput.focus();
  },
});

pickerContainer.appendChild(picker);
twemoji.parse(pickerContainer);

pickerContainer.style.display = 'none';
emojiBtn.addEventListener('click', () => {
  pickerContainer.style.display = pickerContainer.style.display === 'none' ? 'block' : 'none';
});
document.addEventListener('click', (e) => {
  if (!pickerContainer.contains(e.target) && e.target !== emojiBtn) {
    pickerContainer.style.display = 'none';
  }
});


document.addEventListener("click", function (e) {
  if (e.target.classList.contains("menu-btn")) {
    const menu = e.target.nextElementSibling;

    // Close other open menus
    document.querySelectorAll(".dropdown-menu").forEach(m => {
      if (m !== menu) m.classList.remove("show");
    });

    // Toggle clicked menu
    menu.classList.toggle("show");
  } 
  else if (!e.target.closest(".menu-container")) {
    document.querySelectorAll(".dropdown-menu").forEach(m => m.classList.remove("show"));
  }
});




$(document).on("click", ".delmsg", function() {
    var btn = $(this);
    var mid = btn.data("mid");

    $.ajax({
        url: "deleteMessage.php",
        type: "POST",
        data: { mid: mid },
        success: function(response) {
            if (response.trim() === "delete") {
                

            } else if (response.trim() === "notexists") {
                btn.text("requested");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});



$(document).on("click", ".demsg", function() {
    var btn = $(this);
    var mid = btn.data("mid");

    $.ajax({
        url: "demsg.php",
        type: "POST",
        data: { mid: mid },
        success: function(response) {
            if (response.trim() === "deleteme") {
                

            } else if (response.trim() === "notexists") {
                btn.text("requested");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});


$(document).on("click", ".delrec", function() {
    var btn = $(this);
    var mid = btn.data("mid");

    $.ajax({
        url: "delrec.php",
        type: "POST",
        data: { mid: mid },
        success: function(response) {
            if (response.trim() === "deleterec") {
                

            } else if (response.trim() === "notexists") {
                btn.text("requested");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});

// 🔹 Copy message text to clipboard
$(document).on("click", ".copymsg", function(e) {
  e.preventDefault(); // stop link behavior
  const text = $(this).data("message");
navigator.clipboard.writeText(text).then(() => {
  const toast = $("<span class='toast-msg'>Copied!</span>");
  $("body").append(toast);
  setTimeout(() => toast.fadeOut(300, () => toast.remove()), 800);
});
});




function updateSeenStatus() {
    const seen = document.querySelector("#seenIndicator");
    if (!seen) return;

    if (seen.dataset.readtime) {
        // seen case
        const rtime = new Date(seen.dataset.readtime).getTime();
        const diff = (Date.now() - rtime) / 1000;

        if (diff < 60) seen.textContent = "Seen just now";
        else if (diff < 3600) seen.textContent = "Seen " + Math.floor(diff/60) + " min ago";
        else if (diff < 86400) seen.textContent = "Seen " + Math.floor(diff/3600) + " hrs ago";
        else if (diff < 172800) seen.textContent = "Seen yesterday";
        else seen.textContent = "Seen " + Math.floor(diff/86400) + " days ago";
    }
    else if (seen.dataset.senttime) {
      
 seen.textContent = "Messsage Sent ";
       
}
}
setInterval(updateSeenStatus, 10);


document.getElementById("blockbtn").onclick = function () {
    fetch("toggleBlock.php")
        .then(res => res.text())
        .then(result => {

            if (result.trim() === "blocked") {
                document.getElementById("blockbtn").textContent = "Unblock";
                document.getElementById("msgInput").disabled = true;
                document.getElementById("msgInput").placeholder = "You blocked this user";
            }
            else {
                document.getElementById("blockbtn").textContent = "Block";
                document.getElementById("msgInput").disabled = false;
                document.getElementById("msgInput").placeholder = "Type a message...";
            }
        });
};


// ---------- GIF PICKER ----------
const gifBtn = document.getElementById("gifBtn");
const gifBox = document.getElementById("gifBox");
const gifSearch = document.getElementById("gifSearch");
const gifResults = document.getElementById("gifResults");

const GIPHY_KEY = "KSW18RYWclwdogq0YHWg4Pcoopw4clkk";  // <<--- Replace

// Toggle GIF Box
gifBtn.addEventListener("click", () => {
    gifBox.style.display = gifBox.style.display === "none" ? "block" : "none";
    gifSearch.focus();
});

// Hide when clicking outside
document.addEventListener("click", (e) => {
    if (!gifBox.contains(e.target) && e.target !== gifBtn) {
        gifBox.style.display = "none";
    }
});

// Search GIFs
gifSearch.addEventListener("keyup", async () => {
    let q = gifSearch.value.trim();
    if (q.length < 2) return;

    let url = `https://api.giphy.com/v1/gifs/search?api_key=${GIPHY_KEY}&limit=20&q=${encodeURIComponent(q)}`;

    let res = await fetch(url);
    let data = await res.json();

    gifResults.innerHTML = "";

    data.data.forEach(gif => {
        let img = gif.images.fixed_width.url;
        gifResults.innerHTML += `
            <div class="gif-item">
                <img src="${img}" data-gif="${img}">
            </div>
        `;
    });
});

// When user clicks a GIF → send as message
gifResults.addEventListener("click", (e) => {
    if (e.target.tagName === "IMG") {
        let gifUrl = e.target.dataset.gif;
        sendGifMessage(gifUrl);
        gifBox.style.display = "none";
    }
});

// This will send GIF as message (modify for your app)
function sendGifMessage(gifUrl) {
    fetch("insertMessage.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "file=" + encodeURIComponent(gifUrl)
    }).then(() => {
        loadMessages();
    });
}




// ---------- STICKER PICKER ----------
const stickerBtn = document.getElementById("stickerBtn");
const stickerBox = document.getElementById("stickerBox");
const stickerSearch = document.getElementById("stickerSearch");
const stickerResults = document.getElementById("stickerResults");


// Toggle sticker box
stickerBtn.addEventListener("click", () => {
  stickerBox.style.display = stickerBox.style.display === "none" ? "block" : "none";
  stickerSearch.focus();
});

// Close sticker box on body click
document.addEventListener("click", (e) => {
  if (!stickerBox.contains(e.target) && e.target !== stickerBtn) {
    stickerBox.style.display = "none";
  }
});

// Perform search when typing
stickerSearch.addEventListener("keyup", async () => {
  let q = stickerSearch.value.trim();
  if (q.length < 2) return;

  let url = `https://api.giphy.com/v1/stickers/search?api_key=${GIPHY_KEY}&limit=20&q=${encodeURIComponent(q)}`;

  let res = await fetch(url);
  let data = await res.json();

  stickerResults.innerHTML = "";

  data.data.forEach(st => {
    let img = st.images.fixed_width.url;

    stickerResults.innerHTML += `
      <div class="sticker-item">
        <img src="${img}" data-sticker="${img}">
      </div>
    `;
  });
});

// When user clicks a sticker → send as file message
stickerResults.addEventListener("click", (e) => {
  if (e.target.tagName === "IMG") {
    let stickerUrl = e.target.dataset.sticker;
    sendStickerMessage(stickerUrl);
    stickerBox.style.display = "none";
  }
});

// Send sticker as message
function sendStickerMessage(stickerUrl) {
  fetch("insertMessage.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "file=" + encodeURIComponent(stickerUrl)
  }).then(() => {
    loadMessages();
  });
}
