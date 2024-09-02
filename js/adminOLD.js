document.addEventListener('DOMContentLoaded', () => {
    const allUsersBtn = document.getElementById('allUsersBtn');
    const verifyBtn = document.getElementById('verifyBtn');
    const blockedInfoBtn = document.getElementById('blockedInfoBtn');
    const challengeRequestBtn = document.getElementById('challengeRequestBtn');
    const allUsersTable = document.getElementById('allUsersTable');
    const verifyUsersTable = document.getElementById('verifyUsersTable');
    const verificationStatusTable = document.getElementById('verificationStatusTable');
    const blockedUsersTable = document.getElementById('blockedUsersTable');
    const challengeRequestsTable = document.getElementById('challengeRequestsTable');
    const deletePostTable = document.getElementById('deletePostTable'); // Retrieve the delete post table
    const statsTable = document.getElementById('statsTable'); // Retrieve the stats table
    const consolidateTable = document.getElementById('consolidateTable'); // Retrieve consolidate table
    const tagPostBtns = document.querySelectorAll('button[onclick^="tagPost"]');
    const tagPostModal = document.getElementById('tagPostModal');
    const postSearchInput = document.getElementById('postSearchInput');
    const searchResultsDiv = document.getElementById('searchResults');

    
 
     
     // Function to handle tag post button click
    function handleTagPostButtonClick(postId) {
        // Show the tag post modal
        tagPostModal.style.display = 'block';

        // Clear previous search input and results
        postSearchInput.value = '';
        searchResultsDiv.innerHTML = '';

        // Simulated data for search results (replace with actual data)
        const posts = [
            { id: 1, title: 'Important Announcement', username: 'johndoe' },
            { id: 2, title: 'Project Update', username: 'janesmith' },
            { id: 3, title: 'Event Recap', username: 'davidjohnson' }
            // Add more posts here as needed
        ];

        // Function to display search results
        function displaySearchResults(results) {
            if (results.length === 0) {
                searchResultsDiv.innerHTML = '<p>No results found.</p>';
            } else {
                const ul = document.createElement('ul');
                results.forEach(post => {
                    const li = document.createElement('li');
                    li.textContent = `${post.title} (by ${post.username})`;

                    // Create a button for tagging this post
                    const tagButton = document.createElement('button');
                    tagButton.textContent = 'Tag Post';
                    tagButton.classList.add('tagButton'); // Add a class for styling or event handling
                    tagButton.addEventListener('click', () => {
                        handleTagButtonClick(post.id, post.title);
                    });

                    // Append the tag button to the list item
                    li.appendChild(tagButton);

                    ul.appendChild(li);
                });
                searchResultsDiv.appendChild(ul);
            }
        }

        // Function to handle tagging a specific post
        function handleTagButtonClick(postId, postTitle) {
            console.log(`Tagging Post: ${postTitle} (ID: ${postId})`);
            // Implement your tagging logic here
            // For example, you can close the modal and display a success message
            tagPostModal.style.display = 'none';
            alert(`Post ${postId} has been tagged successfully!`);
        }

        // Event listener for search input
        postSearchInput.addEventListener('input', () => {
            const searchTerm = postSearchInput.value.trim().toLowerCase();
            const filteredPosts = posts.filter(post =>
                post.title.toLowerCase().includes(searchTerm) || post.username.toLowerCase().includes(searchTerm)
            );
            displaySearchResults(filteredPosts);
        });

        // Display all posts initially
        displaySearchResults(posts);
    }

    // Attach click event listeners to all tag post buttons
    tagPostBtns.forEach(button => {
        const postId = button.getAttribute('onclick').match(/\d+/)[0]; // Extract postId from onclick attribute
        button.addEventListener('click', () => {
            handleTagPostButtonClick(postId);
        });
    });

    // Close the tag post modal when clicking the close button (×)
    const closeButton = document.querySelector('#tagPostModal .close');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            tagPostModal.style.display = 'none';
        });
    }

    // Close the tag post modal when clicking outside of the modal
    window.addEventListener('click', event => {
        if (event.target === tagPostModal) {
            tagPostModal.style.display = 'none';
        }
    });




    // Function to handle delete post action
    function deletePost(postId) {
        // Implement your delete post logic here
        console.log(`Deleting post with ID: ${postId}`);
        // You can perform post deletion actions, such as making an API call to delete the post
    }

    consolidateBtn.addEventListener('click', () => {
        // Show only the 'All Users' table
        allUsersTable.style.display = 'none';
        verifyUsersTable.style.display = 'none';
        verificationStatusTable.style.display = 'none';
        blockedUsersTable.style.display = 'none'; // Hide blocked users table
        challengeRequestsTable.style.display = 'none'; // Hide challenge requests table
        deletePostTable.style.display = 'none'; 
        statsTable.style.display = 'none'; 
        consolidateTable.style.display = 'block'; 
    });

    allUsersBtn.addEventListener('click', () => {
        // Show only the 'All Users' table
        allUsersTable.style.display = 'block';
        verifyUsersTable.style.display = 'none';
        verificationStatusTable.style.display = 'none';
        blockedUsersTable.style.display = 'none'; // Hide blocked users table
        challengeRequestsTable.style.display = 'none'; // Hide challenge requests table
        deletePostTable.style.display = 'none'; 
        statsTable.style.display = 'none'; 
        consolidateTable.style.display = 'none';
    });

    verifyBtn.addEventListener('click', () => {
        // Show only the 'Verify Users' table
        allUsersTable.style.display = 'none';
        verifyUsersTable.style.display = 'block';
        verificationStatusTable.style.display = 'none';
        blockedUsersTable.style.display = 'none'; // Hide blocked users table
        challengeRequestsTable.style.display = 'none'; // Hide challenge requests table
        deletePostTable.style.display = 'none';
        statsTable.style.display = 'none'; 
        consolidateTable.style.display = 'none';
    });

    blockedInfoBtn.addEventListener('click', () => {
        // Show only the 'Blocked Users' table
        allUsersTable.style.display = 'none';
        verifyUsersTable.style.display = 'none';
        verificationStatusTable.style.display = 'none';
        blockedUsersTable.style.display = 'block';
        challengeRequestsTable.style.display = 'none'; // Hide challenge requests table
        deletePostTable.style.display = 'none';
        statsTable.style.display = 'none'; 
        consolidateTable.style.display = 'none';
    });

    challengeRequestBtn.addEventListener('click', () => {
        // Show only the 'Challenge Requests' table
        allUsersTable.style.display = 'none';
        verifyUsersTable.style.display = 'none';
        verificationStatusTable.style.display = 'none';
        blockedUsersTable.style.display = 'none'; // Hide blocked users table
        challengeRequestsTable.style.display = 'block';
        deletePostTable.style.display = 'none';
        statsTable.style.display = 'none'; 
        consolidateTable.style.display = 'none';
    });

    // Event listener for 'Delete Post' button
    deletedPostBtn.addEventListener('click', () => {
        
        
        allUsersTable.style.display = 'none';
        verifyUsersTable.style.display = 'none';
        verificationStatusTable.style.display = 'none';
        blockedUsersTable.style.display = 'none';
        challengeRequestsTable.style.display = 'none';
        deletePostTable.style.display = 'block'; // Show the specified table
        statsTable.style.display = 'none'; 
        consolidateTable.style.display = 'none';
        
    });

    statsBtn.addEventListener('click', () => {
    // Display charts for number of posts vs categories, subcategories, and country
    createBarChart('postsByCategoryChart', Object.keys(categoriesData), Object.values(categoriesData), 'Number of Posts by Category');
    createBarChart('postsBySubcategoryChart', Object.keys(subcategoriesData), Object.values(subcategoriesData), 'Number of Posts by Subcategory');
    createBarChart('postsByCountryChart', Object.keys(countryData), Object.values(countryData), 'Number of Posts by Country');
    
    // Hide other tables and show the Stats table
    allUsersTable.style.display = 'none';
    verifyUsersTable.style.display = 'none';
    verificationStatusTable.style.display = 'none';
    blockedUsersTable.style.display = 'none';
    challengeRequestsTable.style.display = 'none';
    deletePostTable.style.display = 'none';
    statsTable.style.display = 'block';
    consolidateTable.style.display = 'none';
});


    const saveVerificationBtns = document.querySelectorAll('#verifyUsersTable .saveVerificationBtn');
    saveVerificationBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const statusSelect = row.querySelector('.statusSelect');
            const reasonInput = row.querySelector('.reasonInput');
            const status = statusSelect.value;
            const reason = reasonInput.value;

            console.log(`User: ${row.cells[0].textContent}, Status: ${status}, Reason: ${reason}`);
            alert('Verification status and reason saved successfully!');
        });
    });

    const userVerificationBtn = document.getElementById('userVerificationBtn');
    const verificationStatusBtn = document.getElementById('verificationStatusBtn');

    userVerificationBtn.addEventListener('click', () => {
        // Show only the user verification status table
        allUsersTable.style.display = 'none';
        verifyUsersTable.style.display = 'none';
        verificationStatusTable.style.display = 'block';
        blockedUsersTable.style.display = 'none'; // Hide blocked users table
        challengeRequestsTable.style.display = 'none'; // Hide challenge requests table
        deletePostTable.style.display = 'none'; // Show the specified table
        statsTable.style.display = 'none'; 
        consolidateTable.style.display = 'none';
    });

    // Example data (already defined in your code)
    const categoriesData = {
        "Category A": 20,
        "Category B": 30,
        "Category C": 15
    };

    const subcategoriesData = {
        "Subcategory X": 25,
        "Subcategory Y": 18,
        "Subcategory Z": 12
    };

    const countryData = {
        "United States": 40,
        "Canada": 25,
        "United Kingdom": 20
    };

  // Function to create bar chart
  function createBarChart(chartId, labels, data, chartTitle) {
        const ctx = document.getElementById(chartId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: chartTitle,
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
    
    

    
});

    // Variables to store mouse position and modal element
    let mouseX, mouseY, modalLeft, modalTop;
    let modal = document.getElementById('tagPostModal');
    let modalContent = modal.querySelector('.modal-content');

    // Function to handle mouse down event on draggable area
    modalContent.addEventListener('mousedown', startDrag);

    // Function to start dragging the modal
    function startDrag(event) {
        event.preventDefault();

        // Store initial mouse position and modal position
        mouseX = event.clientX;
        mouseY = event.clientY;
        modalLeft = modal.offsetLeft;
        modalTop = modal.offsetTop;

        // Add event listeners for mousemove and mouseup events
        document.addEventListener('mousemove', dragModal);
        document.addEventListener('mouseup', stopDrag);
    }

    // Function to drag the modal
    function dragModal(event) {
        event.preventDefault();

        // Calculate the new modal position based on mouse movement
        let deltaX = event.clientX - mouseX;
        let deltaY = event.clientY - mouseY;
        let newLeft = modalLeft + deltaX;
        let newTop = modalTop + deltaY;

        // Update the modal position
        modal.style.left = newLeft + 'px';
        modal.style.top = newTop + 'px';
    }

    // Function to stop dragging the modal
    function stopDrag() {
        // Remove event listeners for mousemove and mouseup events
        document.removeEventListener('mousemove', dragModal);
        document.removeEventListener('mouseup', stopDrag);
    }

