document.addEventListener("DOMContentLoaded", function() {
    const signupLink = document.getElementById("signup-link");
    const signupPopup = document.getElementById("signup-popup");

    function openSignupPopup() {
        signupPopup.style.display = "block";
    }

    function closeSignupPopup() {
        signupPopup.style.display = "none";
    }

    signupLink.addEventListener("click", function(event) {
        event.preventDefault();
        openSignupPopup();
    });

    window.addEventListener("click", function(event) {
        if (event.target === signupPopup) {
            closeSignupPopup();
        }
    });
});
