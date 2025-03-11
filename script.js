document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        let username = document.getElementById("username-login")?.value.trim() || "";
        let password = document.getElementById("password-login")?.value.trim() || "";

        // Initialize email and phone as empty string (they might not be present)
        let email = "";
        let phone = "";

        // Check if the email and phone fields exist on the page (for registration)
        if (document.getElementById("email-login")) {
            email = document.getElementById("email-login").value.trim();
        }
        if (document.getElementById("phone-login")) {
            phone = document.getElementById("phone-login").value.trim();
        }

        // If we are in the login form (only username and password are required)
        if (!document.getElementById("email-login") && !document.getElementById("phone-login")) {
            if (!username || !password) {
                showPopup("error", "🛑 Please enter both username and password.");
                return;
            }
        } 
        // If we are in the registration form (username, email, password, and phone are required)
        else {
            if (!username || !email || !password || !phone) {
                showPopup("error", "🛑 Please fill in all fields before submitting.");
                return;
            }
        }

        // Validate email format (if email field exists)
        if (document.getElementById("email-login") && email && (!email.includes("@") || !email.includes("."))) {
            showPopup("error", "📧 Please enter a valid email address.");
            return;
        }

        // Validate password length
        if (password.length < 8) {
            showPopup("error", "🔒 Password must be at least 8 characters.");
            return;
        }

       // Validate phone number format (if phone field exists)
if (document.getElementById("phone-login") && phone) {
    let phonePattern = /^\+966\d{9}$/;
    if (!phone.match(phonePattern)) {
        showPopup("error", "📞 Please enter a valid phone number starting with +966 and consisting of 12 digits.");
        return;
    }
}



        // Show success popup
        showPopup("success", "✅ Success! Redirecting...");

        setTimeout(() => {
            window.location.href = "Home.html"; // Redirect based on form type
        }, 2000);
    });
});

// Function to show popups
function showPopup(type, message) {
    let popupId = type === "success" ? "success-popup" : "alert-popup";
    let messageId = type === "success" ? "success-popup-message" : "alert-popup-message";

    document.getElementById(messageId).textContent = message;
    let popup = document.getElementById(popupId);
    popup.style.visibility = "visible";
    popup.style.opacity = "1";

    // Automatically hide the popup after 3 seconds (3000 ms)
    setTimeout(() => {
        closePopup(type);
    }, 3000); // You can adjust the time as needed (in milliseconds)
}

// Function to close popups
function closePopup(type) {
    let popupId = type === "success" ? "success-popup" : "alert-popup";
    let popup = document.getElementById(popupId);
    popup.style.visibility = "hidden";
    popup.style.opacity = "0";
}
