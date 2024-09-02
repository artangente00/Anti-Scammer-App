function applyFilters() {
    const country = document.getElementById('country-filter').value;
    const category = document.getElementById('category-filter').value;
    const title = document.getElementById('title-filter').value.toLowerCase();

    const listingCards = document.querySelectorAll('.listing-card');




    listingCards.forEach(card => {
        const cardCountry = card.dataset.country.toLowerCase();
        const cardCategory = card.dataset.category.toLowerCase();
        const cardTitle = card.querySelector('h3').innerText.toLowerCase();

        const showCard = 
            (country === 'all' || cardCountry === country) &&
            (category === 'all' || cardCategory === category) &&
            (cardTitle.includes(title));

        card.style.display = showCard ? 'block' : 'none';
    });
}


function toggleUserList(button) {
    const userList = button.nextElementSibling; // Get the <ul> element next to the button
    const sampleUsernames = ['username1', 'username2', 'username3', 'username4', 'username5']; // Sample usernames

    // Clear existing list content
    userList.innerHTML = '';

    // Populate the list with sample usernames
    sampleUsernames.forEach(username => {
        const li = document.createElement('li');
        li.textContent = username;
        userList.appendChild(li);
    });

    // Toggle visibility of the user list
    userList.style.display = userList.style.display === 'block' ? 'none' : 'block';
}

function resetFilters() {
    document.getElementById('country-filter').value = 'all';
    document.getElementById('category-filter').value = 'all';
    document.getElementById('title-filter').value = '';

    // Reset display of all listing cards
    const listingCards = document.querySelectorAll('.listing-card');
    listingCards.forEach(card => {
        card.style.display = 'block';
    });
}



// Get all listing cards
const listingCards = document.querySelectorAll('.listing-card');

// Define the number of people scammed for each listing (example data)
const scammedCounts = [3, 5, 1, 2, 0, 4]; // Example data for scammed counts

// Loop through each listing card and update the scammed count
listingCards.forEach((card, index) => {
    const scammedCountElement = card.querySelector('.scammed-count');
    if (scammedCountElement) {
        scammedCountElement.textContent = `${scammedCounts[index]} people`;
    }
});
function closeSignupPopup() {
    // Reset the values of form inputs inside the signup form
    const signupForm = document.getElementById('signup-form');

    // Loop through all form elements and reset their values to empty strings
    signupForm.querySelectorAll('input, select').forEach(input => {
        input.value = ''; // Reset input value to empty string
    });

    // Close the signup popup
    const signupPopup = document.getElementById('signup-popup');
    signupPopup.style.display = 'none';
}




