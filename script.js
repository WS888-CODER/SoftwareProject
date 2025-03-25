document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("register-form");
    const loginForm = document.getElementById("loginForm");

    function handleFormSubmit(event, formType) {
        event.preventDefault();

        let form = formType === "register" ? registerForm : loginForm;
        let formData = new FormData(form);
        let backendUrl = formType === "register" ? "register.php" : "login.php";

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
                        window.location.href = "Home.html"; 
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
    }

    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            handleFormSubmit(event, "register");
        });
    }

    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            handleFormSubmit(event, "login");
        });
    }

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

    function closePopup(type) {
        let popupId = type === "success" ? "success-popup" : "alert-popup";
        let popup = document.getElementById(popupId);
        popup.style.visibility = "hidden";
        popup.style.opacity = "0";
    }
});
