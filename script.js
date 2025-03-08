document.addEventListener("DOMContentLoaded", function () {
    // Select all forms on the page
    document.querySelectorAll("form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent form submission

            // Get input values from the CURRENT form
            let username = form.querySelector("input[name='username-login']")?.value;
            let email = form.querySelector("input[name='email-login']")?.value; // Only for register
            let password = form.querySelector("input[name='password-login']")?.value;
            let phone = form.querySelector("input[name='phone-login']")?.value; // Only for register

            // Validate password length
            if (password && password.length < 8) {
                showPopup('alert', "🚨 Password must be at least 8 characters long!");
                return;
            }

            // Validate email format (must contain "@")
            if (email && !email.includes("@")) {
                showPopup('alert', "📧 Please enter a valid email address that contains '@'.");
                return;
            }

            // Validate phone format (only for Register form)
            if (phone && !/^\d{3}-\d{3}-\d{4}$/.test(phone)) {
                showPopup('alert', "📞 Phone number must be in the format: 123-456-7890");
                return;
            }

            // Show success message for successful submission
            showPopup('success', "✅ Successfully Submitted! Redirecting...");

            // Wait for 2 seconds, then redirect to homepage
            setTimeout(function () {
                window.location.href = "Home.html"; // Change this to your actual homepage
            }, 2000);
        });
    });
});

// Function to show the popup (alert or success)
function showPopup(type, message) {
    if (type === 'alert') {
        document.getElementById("alert-popup-message").textContent = message;
        let alertPopup = document.getElementById("alert-popup");
        alertPopup.style.visibility = "visible";
        alertPopup.style.opacity = "1";
    } else if (type === 'success') {
        document.getElementById("success-popup-message").textContent = message;
        let successPopup = document.getElementById("success-popup");
        successPopup.style.visibility = "visible";
        successPopup.style.opacity = "1";
    }
}

// Function to close the popup
function closePopup(type) {
    if (type === 'alert') {
        let alertPopup = document.getElementById("alert-popup");
        alertPopup.style.visibility = "hidden";
        alertPopup.style.opacity = "0";
    } else if (type === 'success') {
        let successPopup = document.getElementById("success-popup");
        successPopup.style.visibility = "hidden";
        successPopup.style.opacity = "0";
    }
}
