document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  // Handle login or registration form submission
  form.addEventListener("submit", function (event) {
      event.preventDefault(); // Prevent default form submission

      let username = document.getElementById("username-login")?.value.trim() || "";
      let password = document.getElementById("password-login")?.value.trim() || "";
      let email = document.getElementById("email-login") ? document.getElementById("email-login").value.trim() : "";
      let phone = document.getElementById("phone-login") ? document.getElementById("phone-login").value.trim() : "";

      // If it's login form (no email & phone required)
      let isLoginForm = !document.getElementById("email-login") && !document.getElementById("phone-login");

      if (isLoginForm) {
          if (!username || !password) {
              showPopup("error", "🛑 Please enter both username and password.");
              return;
          }
      } else {
          if (!username || !email || !password || !phone) {
              showPopup("error", "🛑 Please fill in all fields before submitting.");
              return;
          }
      }

      // Email validation (only if it's not a login form)
      if (!isLoginForm && (!email.includes("@") || !email.includes("."))) {
          showPopup("error", "📧 Please enter a valid email address.");
          return;
      }

      // Password validation
      if (password.length < 8) {
          showPopup("error", "🔒 Password must be at least 8 characters.");
          return;
      }

      // Phone number validation (only if it's not a login form)
      if (!isLoginForm) {
          let phonePattern = /^\+966\d{9}$/;
          if (!phone.match(phonePattern)) {
              showPopup("error", "📞 Please enter a valid phone number starting with +966 and consisting of 12 digits.");
              return;
          }
      }

      // Prepare form data for submission
      let formData = new FormData();
      formData.append("username", username);
      formData.append("password", password);
      if (!isLoginForm) {
          formData.append("email", email);
          formData.append("phone", phone);
      }

      // Determine backend URL (login or register)
      let backendUrl = isLoginForm ? "login.php" : "register.php";

      fetch(backendUrl, {
          method: "POST",
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.status) {
              showPopup(data.status, data.message);
              if (data.status === "success") {
                  setTimeout(() => {
                      window.location.href = "Home.html"; // Redirect after success
                  }, 2000);
              }
          } else {
              showPopup("error", "⚠️ Something went wrong. Please try again.");
          }
      })
      .catch(error => {
          console.error("Error occurred:", error);
          showPopup("error", "⚠️ Something went wrong. Please try again.");
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

      setTimeout(() => {
          closePopup(type);
      }, 3000);
  }

  // Function to close popups
  function closePopup(type) {
      let popupId = type === "success" ? "success-popup" : "alert-popup";
      let popup = document.getElementById(popupId);
      popup.style.visibility = "hidden";
      popup.style.opacity = "0";
  }
});
