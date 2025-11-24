<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Binder</title>

  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
  /* ===== AuthX SINGLE-FILE STYLES (prefix: authx-) ===== */
/* ===== AUTHX SINGLE-COLUMN STYLES ===== */
:root{
    --bg: #ff2975;
    --card: #ff3f8e;
    --muted: #f7b4d7;
    --accent1: #ff4b9a;
    --accent2: #ff75d8;
    --glass: rgba(255, 80, 150, 0.08);
    --radius: 16px;
    --container-w: 960px;
    --shadow: 0 20px 50px rgba(255,0,80,0.35);
}

/* FULL PAGE CENTERING */
html,body{
   background: linear-gradient(
    180deg,
    #ff0f7b 0%,
  
    #12040b 60%
  );
    height:100%;
    margin:0;
  padding: 0px;
}
body{
    display:flex;
    align-items:center;
    justify-content:center;
    font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    color:#fff0fa;
    padding:20px;
}

/* AUTHX MAIN SHELL - SINGLE COLUMN */
.authx-shell{
    width:100%;
    max-width:var(--container-w);
    background:linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.04));
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    padding:40px 50px;
    margin:auto;
    position: relative;
    top:-20px;
}

/* LEFT MAIN CONTENT */
.authx-main{
    max-width:600px;
    margin:auto;
    text-align:center;
    display:flex;
    flex-direction:column;
    justify-content:center;
    gap:18px;
}

.authx-hero { margin-bottom:8px; }

.authx-title{
    font-size:28px;
    font-weight:800;
    margin:0 0 6px 0;
    color:white;
}
.authx-sub{
    color:var(--muted);
    margin:0 0 18px 0;
    font-size:14px;
}

/* FORM BOX */
.authx-formpane{
    display:flex;
    justify-content:center;
}

.authx-form{
    width:100%;
    max-width:520px;
    background:linear-gradient(180deg,rgba(255,255,255,0.02),rgba(255,255,255,0.01));
    border-radius:12px;
    padding:22px;
    border:1px solid rgba(255,255,255,0.06);
    box-shadow:0 7px 25px rgba(0,0,0,0.5);
}

.authx-form h3{
    margin:0 0 8px 0;
    font-size:16px;
    font-weight:700;
}
.authx-form p.help{
    margin:0 0 14px 0;
    color:var(--muted);
    font-size:13px;
}

/* INPUT FIELDS */
.authx-field{
    margin-bottom:12px;
    display:flex;
    flex-direction:column;
}
.authx-field label{
    font-size:13px;
    color:var(--muted);
    margin-bottom:6px;
}
.authx-field input,
.authx-field textarea{
    background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border:1px solid rgba(255,255,255,0.06);
    padding:12px 14px;
    border-radius:10px;
    color:#eef6ff;
    font-size:14px;
    outline:none;
}
.authx-field input::placeholder { color: rgba(255,255,255,0.3); }
.authx-field textarea{
    resize:vertical;
    min-height:90px;
    max-height:180px;
}

/* BUTTONS */
.authx-actions{
    margin-top:8px;
    display:flex;
    gap:12px;
    justify-content:center;
}

.authx-btn{
    background:linear-gradient(90deg,var(--accent1),var(--accent2));
    border:none;
    color:white;
    padding:12px 18px;
    border-radius:12px;
    cursor:pointer;
    font-weight:700;
    box-shadow:0 8px 24px rgba(255,60,150,0.35);
    transition:transform .15s, box-shadow .15s;
}
.authx-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 14px 34px rgba(255,60,150,0.45);
}
.authx-btn.ghost{
    background:transparent;
    border:1px solid rgba(255,255,255,0.07);
    color:var(--muted);
}

/* FOOT */
.authx-foot{
    margin-top:10px;
    color:var(--muted);
    font-size:13px;
    text-align:center;
}

/* REMOVED RIGHT PANEL FOR CLEAN VERSION */
.authx-side, .authx-steps, .authx-step { display:none !important; }

/* RESPONSIVE */
@media (max-width:600px){
    .authx-shell{
        padding:26px 18px;
    }
    .authx-form{
        padding:18px;
    }
    .authx-btn{
        width:100%;
        padding:12px 0;
    }
    .authx-main{
        width:100%;
    }
    .authx-title{ font-size:24px; }
}


  </style>
</head>
<body>

  <div class="authx-shell" id="authx-container">

    <!-- MAIN: forms -->
    <div class="authx-main">
      <div class="authx-hero">
        <h1 class="authx-title">Welcome to Binder</h1>
      </div>

      <div class="authx-formpane">

        <!-- SIGN IN -->
        <form class="authx-form" action="checkLogin.php" method="post" id="authx-signin-form">
          <h3>Sign in</h3>
          <p class="help">Enter your account details to continue.</p>

          <div class="authx-field">
            <label for="authx-username">Username </label>
            <input id="authx-username" name="userEmail" placeholder="tyrion21" type="text" required />
          </div>

          <div class="authx-field">
            <label for="authx-password">Password</label>
            <input id="authx-password" name="passWord" type="password" placeholder="Your password" required />
          </div>

          <div class="authx-actions">
            <button type="submit" class="authx-btn">Sign In</button>
            <button type="button" class="authx-btn ghost" id="authx-show-signup">Create account</button>
          </div>

        </form>

        <!-- SIGN UP -->
        <form class="authx-form" action="insertUser.php" method="post" id="authx-signup-form" style="display:none;">
          <h3>Create account</h3>
          <!-- <p class="help">Start by telling us your full name.</p> -->

          <div class="authx-field">
            <label for="authx-fullname">Username</label>
            <input id="authx-fullname" name="uname" placeholder="Create new Username" required />
              <small id="authx-username-error" class="authx-error"></small>

          </div>
        
         <div  class="authx-field">
              <label for="authx-email">Email</label>
              <input id="authx-email" name="uemail" type="email" placeholder="you@example.com" required />
                <small id="authx-email-error" class="authx-error"></small>

            </div>
            
         <div  class="authx-field">
              <label for="authx-email">Age</label>
              <input id="authx-email" name="uage" type="number" placeholder="Enter your age" required />
                <small id="authx-email-error" class="authx-error"></small>

            </div>
<div  class="authx-field">
  <label for="authx-create-pass">Create password</label>
  <input id="authx-create-pass" 
         name="upassword" 
         type="password" 
         placeholder="Strong password" 
         required />
  <small id="authx-pass-error" class="authx-error"></small>
</div>

<div  class="authx-field">
  <label for="authx-confirm-pass">Confirm password</label>
  <input id="authx-confirm-pass" 
         name="confirm_password" 
         type="password" 
         placeholder="Re-enter password" 
         required />
  <small id="authx-confirm-error" class="authx-error"></small>
</div>

        
            <!-- <div class="authx-row"></div> -->

          <div class="authx-actions">
            <button type="submit" class="authx-btn">Sign Up</button>
            <button type="button" class="authx-btn ghost" id="authx-show-signin">Already have an account</button>
          </div>

          <!-- <div class="authx-foot">
            <span class="authx-small">By creating an account you agree to our <a href="#" style="color: #fff; text-decoration:underline;">Terms</a>.</span>
          </div> -->
        </form>

      </div>
    </div>

 

  </div>

  <script>
 // AuthX JS — unique ids & classes
(function () {

  const showSignup = document.getElementById('authx-show-signup');
  const showSignin = document.getElementById('authx-show-signin');
  const signinForm = document.getElementById('authx-signin-form');
  const signupForm = document.getElementById('authx-signup-form');
  const tryBtn = document.getElementById('authx-try');

  // Password Inputs
  const createPass = document.getElementById('authx-create-pass');
  const confirmPass = document.getElementById('authx-confirm-pass');
  const passError = document.getElementById('authx-pass-error');
  const confirmError = document.getElementById('authx-confirm-error');

  // Username & Email Inputs
  const usernameInput = document.getElementById('authx-fullname');
  const emailInput = document.getElementById('authx-email');
  const usernameErr = document.getElementById('authx-username-error');
  const emailErr = document.getElementById('authx-email-error');

  function show(type) {
    if (type === 'signup') {
      signupForm.style.display = 'block';
      signinForm.style.display = 'none';
      setTimeout(() => usernameInput.focus(), 80);
    } else {
      signupForm.style.display = 'none';
      signinForm.style.display = 'block';
    }
  }

  // Buttons
  showSignup?.addEventListener('click', () => show('signup'));
  showSignin?.addEventListener('click', () => show('signin'));
  tryBtn?.addEventListener('click', () => show('signup'));

  // ESC → close signup  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') show('signin');
  });

  // Prevent spam submit  
  [signinForm, signupForm].forEach(form => {
    form?.addEventListener('submit', (ev) => {
      const btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.style.opacity = 0.88;
        setTimeout(() => btn.disabled = false, 3000);
      }
    });
  });

  // ---------------------------
  // PASSWORD VALIDATION
  // ---------------------------

  function validatePasswordRules(pass) {
    const pattern =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;
    return pattern.test(pass);
  }

  createPass.addEventListener('input', () => {
    const val = createPass.value;

    if (!validatePasswordRules(val)) {
      passError.textContent =
        "Password must contain uppercase, lowercase, number, special char & min 6 chars.";
    } else {
      passError.textContent = "";
    }
  });

  confirmPass.addEventListener('input', () => {
    if (confirmPass.value !== createPass.value) {
      confirmError.textContent = "Passwords do not match";
    } else {
      confirmError.textContent = "";
    }
  });

  // ---------------------------
  // USERNAME + EMAIL CHECK (AJAX)
  // ---------------------------

  let usernameOK = false;
  let emailOK = false;

  function checkAvailability(type, value, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "authx-check.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      callback(xhr.responseText.trim());
    };
    xhr.send("type=" + type + "&value=" + encodeURIComponent(value));
  }

  // USERNAME CHECK
  usernameInput.addEventListener("input", () => {
    const value = usernameInput.value.trim();

    if (value.length < 3) {
      usernameErr.textContent = "Must be minimum 3 characters.";
      usernameOK = false;
      return;
    }

    checkAvailability("username", value, (res) => {
      if (res === "used") {
        usernameErr.textContent = "Username already taken.";
        usernameErr.classList.remove("authx-success");
        usernameOK = false;
      } else {
        usernameErr.textContent = "Username available ✓";
        usernameErr.classList.add("authx-success");
        usernameOK = true;
      }
    });
  });

  // EMAIL CHECK
  emailInput.addEventListener("input", () => {
    const value = emailInput.value.trim();

    checkAvailability("email", value, (res) => {
      if (res === "used") {
        emailErr.textContent = "Email is already registered.";
        emailErr.classList.remove("authx-success");
        emailOK = false;
      } else {
        emailErr.textContent = "Email available ✓";
        emailErr.classList.add("authx-success");
        emailOK = true;
      }
    });
  });

  // BLOCK FORM SUBMIT IF INVALID
  signupForm.addEventListener("submit", (e) => {

    if (!usernameOK || !emailOK) {
      e.preventDefault();
      alert("Fix errors before submitting!");
    }

    if (!validatePasswordRules(createPass.value)) {
      passError.textContent =
        "Password must contain uppercase, lowercase, number, special char & min 6 chars.";
      e.preventDefault();
    }

    if (createPass.value !== confirmPass.value) {
      confirmError.textContent = "Passwords do not match";
      e.preventDefault();
    }

  });

})();


  </script>
</body>
</html>
