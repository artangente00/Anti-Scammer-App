

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
