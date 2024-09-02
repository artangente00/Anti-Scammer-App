document.addEventListener('DOMContentLoaded', function() {

    const sidebarButtons = document.querySelectorAll('#sidebar button');
    const contentSections = document.querySelectorAll('#content > div');
    

    sidebarButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sectionId = this.id.replace('Button', 'Content'); // Get corresponding content section id
            showContentSection(sectionId); // Show the corresponding content section
        });
    });

    function showContentSection(sectionId) {
        contentSections.forEach(section => {
            if (section.id === sectionId) {
                section.style.display = 'block'; // Show the selected content section
            } else {
                section.style.display = 'none'; // Hide other content sections
            }
        });
    }


    // Button actions within the Posts section
    document.getElementById('publishButton').addEventListener('click', function() {
        // Implement logic for publishing
        console.log('Publishing post...');
    });
    document.getElementById('unpublishedButton').addEventListener('click', function() {
        // Implement logic for marking as unpublished
        console.log('Marking post as unpublished...');
    });
    document.getElementById('cancelButton').addEventListener('click', function() {
        // Implement logic for canceling action
        console.log('Canceling action...');
    });

    // Show/hide the terms popup when terms link is clicked
    const termsLink = document.getElementById('termsLink');
    const termsPopup = document.getElementById('termsPopup');
    const closeTermsButton = document.getElementById('closeTermsButton');

    termsLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Display the terms popup
        termsPopup.style.display = 'block';
    });

    closeTermsButton.addEventListener('click', function() {
        // Close the terms popup
        termsPopup.style.display = 'none';
    });


    // Show/hide the description popup
    const descriptionButton = document.getElementById('descriptionButton');
    const descriptionPopup = document.getElementById('descriptionPopup');

    descriptionButton.addEventListener('click', function() {
        descriptionPopup.style.display = 'block';
    });

    // Save and cancel actions for description
    const saveDescriptionButton = document.getElementById('saveDescriptionButton');
    const cancelDescriptionButton = document.getElementById('cancelDescriptionButton');

    saveDescriptionButton.addEventListener('click', function() {
        // Get values from input fields
        const title = document.getElementById('titleInput').value;
        const description = document.getElementById('descriptionInput').value;

        // Perform save operation (e.g., send data to server)
        console.log('Title:', title);
        console.log('Description:', description);

        // Close the popup
        descriptionPopup.style.display = 'none';
    });

    cancelDescriptionButton.addEventListener('click', function() {
        // Reset input fields
        document.getElementById('titleInput').value = '';
        document.getElementById('descriptionInput').value = '';

        // Close the popup
        descriptionPopup.style.display = 'none';
    });

    // Show/hide the e-wallet info popup
const scammerEwalletInfoButton = document.getElementById('scammerEwalletInfoButton');
const ewalletInfoPopup = document.getElementById('ewalletInfoPopup');

scammerEwalletInfoButton.addEventListener('click', function() {
    ewalletInfoPopup.style.display = 'block';
});

// Upload QR code action
//const uploadQRCodeButton = document.getElementById('uploadQRCodeButton');
const qrCodeInput = document.getElementById('qrCodeInput');

//uploadQRCodeButton.addEventListener('click', function() {
  //  qrCodeInput.click(); // Trigger the file input click to select a QR code image
// });

// Save and cancel actions for e-wallet info
const saveEwalletInfoButton = document.getElementById('saveEwalletInfoButton');
const cancelEwalletInfoButton = document.getElementById('cancelEwalletInfoButton');

saveEwalletInfoButton.addEventListener('click', function() {
    // Get values from input fields
    const phoneNumber = document.getElementById('phoneNumberInput').value;
    const qrCodeFile = document.getElementById('qrCodeInput').files[0]; // Get selected file

    // Perform save operation (e.g., send data to server)
    console.log('Phone Number:', phoneNumber);
    console.log('QR Code File:', qrCodeFile);

    // Close the popup
    ewalletInfoPopup.style.display = 'none';
});

cancelEwalletInfoButton.addEventListener('click', function() {
    // Reset input fields
    document.getElementById('phoneNumberInput').value = '';
    document.getElementById('qrCodeInput').value = ''; // Reset file input (clear selection)

    // Close the popup
    ewalletInfoPopup.style.display = 'none';
});
    // Show/hide the bank info popup
    const scammerBankInfoButton = document.getElementById('scammerBankInfoButton');
    const bankInfoPopup = document.getElementById('bankInfoPopup');

    scammerBankInfoButton.addEventListener('click', function() {
        bankInfoPopup.style.display = 'block';
    });

    // Save and cancel actions
    const saveBankInfoButton = document.getElementById('saveBankInfoButton');
    const cancelBankInfoButton = document.getElementById('cancelBankInfoButton');

    saveBankInfoButton.addEventListener('click', function() {
        // Get values from input fields
        const bankName = document.getElementById('bankNameSelect').value;
        const accountName = document.getElementById('accountNameInput').value;
        const accountNumber = document.getElementById('accountNumberInput').value;

        // Perform save operation (e.g., send data to server)
        console.log('Bank Name:', bankName);
        console.log('Account Name:', accountName);
        console.log('Account Number:', accountNumber);

        // Close the popup
        bankInfoPopup.style.display = 'none';
    });

    cancelBankInfoButton.addEventListener('click', function() {
        // Reset input fields
        document.getElementById('bankNameSelect').value = '';
        document.getElementById('accountNameInput').value = '';
        document.getElementById('accountNumberInput').value = '';

        // Close the popup
        bankInfoPopup.style.display = 'none';
    });
    const categories = {
        "HOME & GARDEN": ["Furniture", "Kitchenware", "Gardening tools & Supplies", "Cleaning Materials", "Food"],
        "ENTERTAINMENT": ["Music, Books, Movies", "Event Tickets", "Toys & Games", "Pageant", "Sing/Dance Competition", "GFE", "Massage"],
        "FAMILY": ["Health & Beauty", "Baby & Kids", "Clothes", "Accessories", "Jewelry", "Pet Accessories", "Pet Food", "Pet Purchase", "Wedding Supplier", "Wedding Coordinator"],
        "ELECTRONICS": ["Video Games", "Personal Computer", "Laptop", "Tablet", "Phone", "Accessories"],
        "HOBBIES": ["Restaurant", "Amusement Park", "Musical Instrument", "Sports", "Collections", "Cars for sale", "Parts"],
        "BUSINESS": ["Investment", "Loan", "Supplier", "Partnership"]
    };

    // Get the category select dropdown element
    const categorySelect = document.getElementById('categorySelect');

    // Function to populate the category select dropdown with options
    function populateCategorySelect() {
        // Loop through each category in the object
        for (const category in categories) {
            // Create a new optgroup for the category
            const optGroup = document.createElement('optgroup');
            optGroup.label = category; // Set the optgroup label to the category name

            // Loop through each subcategory in the current category
            categories[category].forEach(subcategory => {
                // Create a new option for the subcategory
                const option = document.createElement('option');
                option.value = subcategory.toLowerCase().replace(/ /g, '_'); // Set the option value (replace spaces with underscores)
                option.textContent = subcategory; // Set the option text content to the subcategory name
                optGroup.appendChild(option); // Append the option to the optgroup
            });

            // Append the optgroup to the category select dropdown
            categorySelect.appendChild(optGroup);
        }
    }

    // Call the function to populate the category select dropdown
    populateCategorySelect();

    // Sample data for the chart (dates and number of posts)
    const postData = [
        { date: '2024-04-01', posts: 5 },
        { date: '2024-04-05', posts: 8 },
        { date: '2024-04-10', posts: 12 },
        { date: '2024-04-15', posts: 10 },
        { date: '2024-04-20', posts: 15 }
    ];

    // Extract dates and posts counts from postData
    const dates = postData.map(entry => entry.date);
    const postCounts = postData.map(entry => entry.posts);

    const ctx = document.getElementById('postChart').getContext('2d');

    // Create a line chart for User Activity
    const postChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Number of Posts',
                data: postCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'day',
                        displayFormats: {
                            day: 'MMM D'
                        }
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of Posts'
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    // Function to show/hide content based on button click
    function showContent(contentId) {
        const contentSections = document.querySelectorAll('#content > div');
        contentSections.forEach(section => {
            section.style.display = section.id === contentId ? 'block' : 'none';
        });
    }
    // Show User Activity content by default
    document.getElementById('userActivityContent').style.display = 'block';

    // Hide other content sections initially
    document.getElementById('postsContent').style.display = 'none';
    document.getElementById('challengesContent').style.display = 'none';
    document.getElementById('profileContent').style.display = 'none';
    document.getElementById('validIdContent').style.display = 'none';


    // Attach click event listeners to sidebar buttons
    document.getElementById('userActivityButton').addEventListener('click', function() {
        showContent('userActivityContent');
    });
    document.getElementById('postsButton').addEventListener('click', function() {
        showContent('postsContent');
    });
    document.getElementById('challengesButton').addEventListener('click', function() {
        showContent('challengesContent');
    });
    document.getElementById('profileButton').addEventListener('click', function() {
        showContent('profileContent');
    });
    document.getElementById('validIdButton').addEventListener('click', function() {
        showContent('validIdContent');
    });

    // Button click event to add scammer name input field
    const addScammerNameButton = document.getElementById('addScammerNameButton');
    const removeScammerNameButton = document.getElementById('removeScammerNameButton');
    const scammerNameContainer = document.getElementById('scammerNameContainer');
    let scammerInputCount = 0;

    addScammerNameButton.addEventListener('click', function() {
        scammerInputCount++;
        const scammerInput = document.createElement('input');
        scammerInput.type = 'text';
        scammerInput.name = `scammerName${scammerInputCount}`;
        scammerInput.placeholder = `Scammer Name ${scammerInputCount}`;
        scammerNameContainer.appendChild(scammerInput);
    });

    // Button click event to remove the last scammer name input field
    removeScammerNameButton.addEventListener('click', function() {
        const scammerInputs = scammerNameContainer.querySelectorAll('input[type="text"]');
        if (scammerInputs.length > 0) {
            scammerNameContainer.removeChild(scammerInputs[scammerInputs.length - 1]);
            scammerInputCount--;
        }
    });
});
// script.js

function logout() {
    // Redirect to home.html
    window.location.href = "home.html";
}
var commentFormCounter = 1;

        function addCommentForm() {
            // Create a new comment form
            var commentFormId = 'commentForm' + commentFormCounter;
            var commentInputId = 'commentInput' + commentFormCounter;

            var newCommentForm = document.createElement('form');
            newCommentForm.id = commentFormId;
            newCommentForm.className = 'comment-form';

            // Create label and input elements for the new comment form
            var label = document.createElement('label');
            label.htmlFor = commentInputId;
            label.textContent = 'Add your comment:';
            newCommentForm.appendChild(label);

            var input = document.createElement('input');
            input.type = 'text';
            input.id = commentInputId;
            input.className = 'comment-input';
            input.placeholder = 'Type your comment here';
            newCommentForm.appendChild(input);

            var button = document.createElement('button');
            button.type = 'button';
            button.textContent = 'Post';
            button.className = 'comment-button';
            button.onclick = function() {
                postComment(commentFormId);
            };
            newCommentForm.appendChild(button);

            // Append the new comment form to the container
            var commentFormsContainer = document.getElementById('commentFormsContainer');
            commentFormsContainer.appendChild(newCommentForm);

            // Display the new comment form
            newCommentForm.style.display = 'block';

            // Increment the counter for the next comment form
            commentFormCounter++;
        }

        function postComment(commentFormId) {
            var commentInput = document.getElementById(commentFormId).querySelector('.comment-input');
            var commentText = commentInput.value;

            var commentList = document.getElementById('commentList');
            var newComment = document.createElement('li');
            newComment.innerHTML = '<strong>You:</strong> ' + commentText;
            commentList.appendChild(newComment);

            commentInput.value = '';
        }