function openTab(evt, tabName) {
    // Get all elements with class "tabcontent" and hide them
    var tabcontents = document.getElementsByClassName("tabcontent");
    for (var i = 0; i < tabcontents.length; i++) {
        tabcontents[i].style.display = "none";
    }

    // Get all elements with class "tablinks" and remove the "active" class
    var tablinks = document.getElementsByClassName("tablinks");
    for (var i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    // Show the current tab content and add an "active" class to the clicked tab button
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
}

// Function to toggle settings menu visibility
function toggleSettingsMenu(button) {
    const settingsMenu = button.nextElementSibling;
    settingsMenu.style.display = (settingsMenu.style.display === 'block') ? 'none' : 'block';
}

// Function to show activities for a specific user
function showActivities(username) {
    alert(`Showing activities for ${username}`);
    // Add your logic to display activities
}

// Function to edit a user
function editUser(username) {
    alert(`Editing user: ${username}`);
    // Add your logic to edit user details
}

// Function to block a user
function blockUser(username) {
    alert(`Blocking user: ${username}`);
    // Add your logic to block user
}

function editContent(contentName) {
    // Navigate to contents.html with the appropriate query parameter or path
    window.location.href = 'contents.html'; // Update the URL as needed
}
