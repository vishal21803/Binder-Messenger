
<?php
include("header.php")
?>

<main>
 <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    
                </div>
                <h1>Sign in to Binder</h1>
                <p>Continue to the World</p>
            </div>
            
            <form class="login-form" id="loginForm" novalidate action="checkLogin.php" method="post">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="userEmail" required autocomplete="email">
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="passWord" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="passwordError"></span>
                </div>

               
                <button type="submit" class="login-btn">
                    <span class="btn-text">Sign In</span>
                    <div class="btn-loader">
                        <div class="loader-dot"></div>
                        <div class="loader-dot"></div>
                        <div class="loader-dot"></div>
                    </div>
                </button>
            </form>

            <!-- <div class="divider">
                <span>or</span>
            </div> -->

            <div class="signup-link">
                <p>Don't have an account? <a href="regisForm.php">Sign up free</a></p>
            </div>

         
        </div>
    </div>

</main>




<?php
include("footer.php")
?>
