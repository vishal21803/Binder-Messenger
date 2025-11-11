
<?php
include("header.php")
?>

<main>
<div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    
                </div>
               
            </div>
            
            <form class="login-form" id="loginForm"  action="insertUser.php" method="get">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="name" id="name" name="uname" required >
                </div>
                <div class="form-group">
                    <label for="name">Age</label>
                    <input type="number" id="name" name="uage" required >
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="uemail" required autocomplete="email">
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="upassword" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="passwordError"></span>
                </div>

                  <div class="form-group">
                    <label for="password">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="passwordError"></span>
                </div>

                <div class="form-options">
                  
                </div>

                <button type="submit" class="login-btn">
                    Create Account
                </button>
            </form>

            <!-- <div class="divider">
                <span>or</span>
            </div>
\ -->
            <div class="signup-link">
                <p>Alreday have an account? <a href="loginForm.php">Login Now</a></p>
            </div>

          
        </div>
    </div>
</main>




<?php
include("footer.php")
?>
