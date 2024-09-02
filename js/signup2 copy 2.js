
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

document.addEventListener("DOMContentLoaded", function() {
    const signupForm = document.getElementById('signup-form');

    signupForm.addEventListener('submit', function(event) {
        event.preventDefault();

        fetch('signup.php', {
            method: 'POST',
            body: new FormData(signupForm)
        })
        .then(response => response.json())
        .then(data => {
            // Show success or error message in a popup
            alert(data.message);

            if (data.status === 'success') {
                // Reset the form after showing the success message
                signupForm.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const signinForm = document.getElementById('signin-form');

    signinForm.addEventListener('submit', function(event) {
        event.preventDefault();

        fetch('signin.php', {
            method: 'POST',
            body: new FormData(signinForm)
        })
        .then(response => response.json())
        .then(data => {
            // Show success or error message in a popup
            alert(data.message);

            if (data.status === 'success') {
                // Redirect the user after showing the success message
                window.location.href = data.redirect;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});




document.addEventListener("DOMContentLoaded", function() {
    const signupPopup = document.getElementById('signup-popup');
    const postScamButton = document.querySelector('.post-scam-btn');

    // Function to toggle signup popup visibility
    function toggleSignupPopup() {
        if (signupPopup.style.display === 'none') {
            signupPopup.style.display = 'block';
        } else {
            signupPopup.style.display = 'none';
        }
    }

    // Event listener for "Post a Scam" button click
    postScamButton.addEventListener('click', function(event) {
        event.preventDefault();
        toggleSignupPopup();
    });

    // Function to close signup popup
    function closeSignupPopup() {
        signupPopup.style.display = 'none';
    }

   

    // Close button event listener
    const closeButton = document.querySelector('.close-button');
    if (closeButton) {
        closeButton.addEventListener('click', function(event) {
            event.preventDefault();
            closeSignupPopup();
        });
    }
});
