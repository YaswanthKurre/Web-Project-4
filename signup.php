<?php session_start(); ?>

<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    header('Location: home.php');
    exit();
}
$error_message = isset($_SESSION["error"]) ? $_SESSION["error"] : array();
?>

<?php include("header.php"); ?>

            <form action="signup-submit.php" method="post">
                <h1><strong>Register</strong></h1>
                <?php if (!empty($error_message)) : ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                    <?php $_SESSION["error"]="";
                endif; ?>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required oninput="checkUsernameValidity()">
                <div id="username-error" style="color: red; font-size: 16px; margin: 10px;">checking....</div>

                <label for="password1">Enter Password:</label>
                <input type="password" id="password1" name="password1" placeholder="Enter your password" required oninput="checkPasswordStrength()">
                <div id="password-error" style="color: red; font-size: 16px; margin: 10px;"></div>

                <label for="password2">Confirm Password:</label>
                <input type="password" id="password2" name="password2" placeholder="Confirm your password" required oninput="checkPasswordMatch()">
                <div id="password-match-error" style="color: red; font-size: 16px; margin: 10px;"></div>

                <button type="submit" id="registerButton" disabled>Register</button>

                <p>Already have an account? <a href="login.php" style="color: red;">Login here</a></p>
            </form>

<?php include("footer.html"); ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var submitButton = document.getElementById('registerButton');
        submitButton.disabled = true;
        submitButton.style.backgroundColor = "#ddd";
        submitButton.style.cursor = "not-allowed";

        document.getElementById("username").addEventListener("input", checkUsernameValidity);
        document.getElementById("password1").addEventListener("input", checkPasswordStrength);
        document.getElementById("password2").addEventListener("input", checkPasswordMatch);

        function disableSubmitButton(hasError) {
            var submitButton = document.getElementById('registerButton');
            var usernameErrorElement = document.getElementById("username-error");
            var passwordErrorElement = document.getElementById("password-error");
            var passwordMatchErrorElement = document.getElementById("password-match-error");

            var hasError = usernameErrorElement.textContent.trim() !== "" || passwordErrorElement.textContent.trim() !== "" || passwordMatchErrorElement.textContent.trim() !== "";

            submitButton.disabled = hasError;
            if (hasError) {
                submitButton.style.backgroundColor = "#ddd";
                submitButton.style.cursor = "not-allowed";
            } else {
                submitButton.style.backgroundColor = "";
                submitButton.style.cursor = "";
            }
        }

        function checkOnLoad() {
            checkUsernameValidity();
            checkPasswordStrength();
            checkPasswordMatch();
        }

        function checkUsernameValidity() {
            var username = document.getElementById("username").value;
            var usernameError = document.getElementById("username-error");

            var isAlphanumeric = /^[0-9a-z]+$/.test(username);

            if (!isAlphanumeric) {
                usernameError.textContent = "Username must contain only numbers and lowercase letters";
            } else {
                usernameError.textContent = "";
                checkUsernameAvailability();
            }
            disableSubmitButton();
        }

        function checkUsernameAvailability() {
            var username = document.getElementById("username").value;
            var usernameError = document.getElementById("username-error");

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check-username.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var response = xhr.responseText.trim();
                        console.log(response);
                        if (response === "exists") {
                            usernameError.textContent = "Username already exists";
                        } else {
                            usernameError.textContent = "";
                        }
                    } else {
                        console.error("Error checking username availability. Status: " + xhr.status);
                    }
                }
            };
            xhr.send("username=" + encodeURIComponent(username));
            disableSubmitButton();
        }

        function checkPasswordStrength() {
            var password = document.getElementById("password1").value;
            var minLength = 6;
            var hasUpperCase = /[A-Z]/.test(password);
            var hasLowerCase = /[a-z]/.test(password);
            var hasDigits = /\d/.test(password);
            var hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            var errorMessage = "";
            if (password.length < minLength) {
                errorMessage += "Password must be at least " + minLength + " characters long<br>";
            }
            if (!hasUpperCase) {
                errorMessage += "Password must contain at least one uppercase letter<br>";
            }
            if (!hasLowerCase) {
                errorMessage += "Password must contain at least one lowercase letter<br>";
            }
            if (!hasDigits) {
                errorMessage += "Password must contain at least one digit<br>";
            }
            if (!hasSpecialChars) {
                errorMessage += "Password must contain at least one special character<br>";
            }

            document.getElementById("password-error").innerHTML = errorMessage;
            disableSubmitButton();
        }

        function checkPasswordMatch() {
            var password1 = document.getElementById("password1").value;
            var password2 = document.getElementById("password2").value;
            var passwordMatchError = document.getElementById("password-match-error");

            if (password1 !== password2) {
                passwordMatchError.textContent = "Passwords do not match";
            } else {
                passwordMatchError.textContent = "";
            }
            disableSubmitButton();
        }

        // Perform initial validation on page load
        checkOnLoad();
    });
</script>
