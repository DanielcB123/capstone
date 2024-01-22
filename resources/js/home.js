
// Function to display friend request message
function displayFriendRequestMessage() {
    // Create the message element
    var messageElement = document.createElement('div');
    messageElement.innerText = 'Friend request sent!';
    messageElement.style.position = 'fixed'; // Make it fixed
    messageElement.style.top = '3rem'; // Position it vertically in the middle
    messageElement.style.left = '50%'; // Start it from the center
    messageElement.style.transform = 'translateX(-50%)'; // Center it horizontally
    messageElement.style.width = '50%'; // Set the width to 50%
    messageElement.style.backgroundColor = '#22c55e';
    messageElement.style.color = 'white';
    messageElement.style.padding = '20px';
    messageElement.style.borderRadius = '5px';
    messageElement.style.zIndex = '10'; // Set z-index to 10
    messageElement.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
    messageElement.style.transition = 'transform 1s cubic-bezier(0.4, 0, 0.2, 1)'; // Use cubic-bezier for smoother animation

    // Append the message to the body
    document.body.appendChild(messageElement);

    // Triggering a reflow to apply the initial transform property
    messageElement.getBoundingClientRect();

    // Delay the initial slide-in animation by 1 second
    setTimeout(function() {
        // Slide it in from the left
        messageElement.style.transform = 'translateX(-50%)';
    }, 0); // Delayed by 1 second

    // Remove the message after 2 seconds
    setTimeout(function() {
        // Slide it out to the right
        messageElement.style.transform = 'translateX(100%)';

        // Wait for the animation to finish before removing
        messageElement.addEventListener('transitionend', function() {
            document.body.removeChild(messageElement);
        });

    }, 700);
}

function getCoordinates(address) {
    return fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=AIzaSyDASA8fmLTGHD2P2wTN5Bh9S5NKOET-Gtc`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "OK" && data.results[0] && data.results[0].geometry && data.results[0].geometry.location) {
                return data.results[0].geometry.location;
            } else {
                console.error('Geocoding failed for address:', address);
                return null;
            }
        })
        .catch(error => {
            console.error('Error in geocoding:', error);
            return null;
        });
}



// Function to handle sending a friend request
function sendFriendRequest(friendId) {
    console.log('send req');
    fetch('/friend-request/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ receiver_id: friendId })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        displayFriendRequestMessage(); // Display the message
    })
    .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', (event) => {
    
    loadFriends();
    loadFavorites();


    // fetch(`/favorites`, {
    //         method: 'GET',
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         console.log(data);
    //         console.log('Is array?', Array.isArray(data.data));
    //         // Check if data.data is an array and not empty
    //         if (Array.isArray(data) && data.length > 0) {
    //             // displayFavorites(data);
    //         } else {
    //             // Handle the case where there are no favorites
    //             document.getElementById('favoritesTable').innerHTML = '<p>No favorites found.</p>';
    //         }
    //     })
    //     .catch(error => console.error('Error loading favorites:', error));


    document.getElementById('findMeetSpotsForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission
    
        const userAddress = document.getElementById('newAddress').value;
        const friendSelect = document.getElementById('friendList');
        const friendAddress = friendSelect.options[friendSelect.selectedIndex].dataset.address;
    
        Promise.all([getCoordinates(userAddress), getCoordinates(friendAddress)])
            .then(([userCoords, friendCoords]) => {
                if (userCoords && friendCoords) {
                    // Get selected place types
                    placesData = []; 
                    const selectedTypes = Array.from(document.querySelectorAll('.place-type:checked')).map(checkbox => checkbox.value);
                    console.log(selectedTypes);
                    if (selectedTypes.length > 0) {
                        // If checkboxes are selected, search for places and update the table
                        initMap(userCoords, friendCoords, selectedTypes);
                    } else {
                        // If no checkboxes are selected, clear the table
                        displayPlacesTable();
                    }
                } else {
                    console.error('Invalid addresses or coordinates not found');
                }
            })
            .catch(error => console.error('Error fetching coordinates:', error));
    });
    
    document.getElementById('backButton').addEventListener('click', function() {
        // Hide friend list and show address input
        document.getElementById('friendListContainer').classList.add('hidden');
        document.getElementById('addressInputContainer').classList.remove('hidden');
        document.getElementById('formTitle').textContent = 'Enter Your Address';
    
        // Clear the friend list selection
        document.getElementById('friendList').value = '';
    
        // Optionally, clear the friend address display if it exists
        let addressDiv = document.getElementById('friendAddress');
        if (addressDiv) {
            addressDiv.textContent = '';
        }
    });
    

    document.getElementById('addressSubmit').addEventListener('click', function() {

        // Hide address input and show friend list
        document.getElementById('addressInputContainer').classList.add('hidden');
        document.getElementById('friendListContainer').classList.remove('hidden');
        document.getElementById('formTitle').textContent = 'Pick A Friend';
        loadFriends();


        


        const userAddress = document.getElementById('newAddress').value || "New York City, NY";
        getCoordinates(userAddress).then(userCoords => {
            userCoords = userCoords || { lat: 40.7128, lng: -74.0060 }; // Default to New York City
    
            const friendSelect = document.getElementById('friendList');
            friendSelect.addEventListener('change', function() {
                const friendAddress = friendSelect.options[friendSelect.selectedIndex].dataset.address || "Los Angeles, CA";
                getCoordinates(friendAddress).then(friendCoords => {
                    friendCoords = friendCoords || { lat: 34.0522, lng: -118.2437 }; // Default to Los Angeles
    
                    initMap(userCoords, friendCoords);
                });
            });
        });

        const friendSelect = document.getElementById('friendList');
        const friendAddress = friendSelect.options[friendSelect.selectedIndex].dataset.address;
    
        // if (userAddress && friendAddress) {
        //     Promise.all([getCoordinates(userAddress), getCoordinates(friendAddress)])
        //         .then(([userLocation, friendLocation]) => {
        //             const midpoint = calculateMidpoint(userLocation.lat, userLocation.lng, friendLocation.lat, friendLocation.lng);
        //             initMap(userLocation, friendLocation, midpoint);
        //         })
        //         .catch(error => console.error('Error:', error));
        // }

        fetch('/update-address', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ address: userAddress })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message); // Log the response message
            // Additional actions upon successful update
        })
        .catch(error => console.error('Error:', error));
    });
    


    // Event listener for the 'Find Friend' button
    var findFriendButton = document.getElementById('findFriendButton');
    if (findFriendButton) {
        findFriendButton.addEventListener('click', function() {
            document.getElementById('findFriendModal').style.display = 'block';
        });
    }

    // Event listener for the 'Close' button
    var closeButton = document.getElementsByClassName('close')[0];
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            document.getElementById('findFriendModal').style.display = 'none';
        });
    }

    // Event listener for the 'Search Friend' input
    var searchFriendInput = document.getElementById('searchFriendInput');
    if (searchFriendInput) {
        searchFriendInput.addEventListener('keyup', function() {
            var query = this.value;

            if (query.length >= 3) {
                fetch(`/search-friends?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        var tableContent = '';
                        data.forEach(function(friend) {
                            tableContent += `
                                <tr class="w-full border border-gray-200">
                                    <td class="flex justify-center"><img src="${friend.profile_image_path}" alt="Profile Image" style="width:50px;height:50px;border-radius:50%;"></td>
                                    <td class="text-sm border border-gray-200 p-1">${friend.name}</td>
                                    <td class="w-full flex justify-center">
                                        <button class="friend-request-btn" data-friend-id="${friend.id}">Send Friend Request</button>
                                    </td>
                                </tr>`;
                        });
                        document.getElementById('friendResultsTable').innerHTML = tableContent;
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                document.getElementById('friendResultsTable').innerHTML = '';
            }
        });
    }

    // Event delegation for dynamically created 'Send Friend Request' buttons
    document.getElementById('friendResultsTable').addEventListener('click', function(event) {
        if (event.target && event.target.matches("button.friend-request-btn")) {
            const friendId = event.target.getAttribute('data-friend-id');
            sendFriendRequest(friendId);
        }
    });


    // Attach event listener to the table for delegation
    document.getElementById('placesTable').addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('favorite-btn')) {
            const name = event.target.getAttribute('data-name');
            const address = event.target.getAttribute('data-address');
            const lat = event.target.getAttribute('data-lat');
            const lng = event.target.getAttribute('data-lng');
            const type = event.target.getAttribute('data-type');
        
            // Get the selected friend's ID from the friend list
            const friendSelect = document.getElementById('friendList');
            const friendId = friendSelect.value;



            fetch('/save-favorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name, address, latitude: lat, longitude: lng, type, friendId })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Favorite added:', data);
                loadFavorites()
                // Optionally, update the UI to reflect the new favorite
            })
            .catch(error => console.error('Error adding favorite:', error));
        }
    });



    function loadFriends() {
        
        fetch('/friends')  // Adjust if you're using a prefix or different route
            .then(response => response.json())
            .then(friends => {
                const friendList = document.getElementById('friendList');
                friendList.innerHTML = '<option value="">Select a friend</option>'; // Clear existing options and add a default one
    
                friends.forEach(friend => {
                    let option = document.createElement('option');
                    option.value = friend.id; // Adjust if your friend object uses different key
                    option.textContent = friend.name; // Adjust if your friend object uses different key
                    option.dataset.address = friend.address; // Store the address in the option
                    friendList.appendChild(option);
                });
    
                friendList.onchange = function() {
                    displayFriendAddress(this);
                };
            })
            .catch(error => console.error('Error loading friends:', error));
    }
    
    function displayFriendAddress(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const friendName = selectedOption.textContent; // Get the friend's name from the option text
        const friendAddress = selectedOption.dataset.address || 'No address available';
    
        let message;
        if (selectedOption.value === "") {
            message = "Select a friend to see their address and find a meet spot halfway.";
        } else {
            message = `<span>${friendName} is at:</span> ${friendAddress}`;
        }
    
        let addressDiv = document.getElementById('friendAddress');
        if (!addressDiv) {
            addressDiv = document.createElement('div');
            addressDiv.id = 'friendAddress';
            selectElement.parentNode.insertBefore(addressDiv, selectElement.nextSibling);
        }
        addressDiv.innerHTML = message; // Use innerHTML since we're now including HTML in the message
    }
    

});


function displayFavorites(response) {
    let tableContent = '<table class="favorites-table"><tr><th>Name</th><th>Address</th><th>Type</th></tr>';

    // Check if the response is an array and not empty
    if (Array.isArray(response) && response.length > 0) {
        response.forEach((favorite, index) => {
            const rowClass = index % 2 === 0 ? 'even-row' : 'odd-row';
            tableContent += `<tr class="${rowClass}"><td>${favorite.name}</td><td>${favorite.address}</td><td>${favorite.type}</td></tr>`;
        });
    } else {
        // Handle case where the response is empty or not an array
        console.error('No favorites found or unexpected response structure:', response);
        tableContent += '<tr><td colspan="3">Error loading favorites</td></tr>';
    }

    tableContent += '</table>';
    document.getElementById('favoritesTable').innerHTML = tableContent;
}

function loadFavorites() {
    console.log('load favs');
    fetch('/favorites', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        console.log('Is array?', Array.isArray(data));
        // Check if data is an array and not empty
        if (Array.isArray(data) && data.length > 0) {
            displayFavorites(data);
        } else {
            // Handle the case where there are no favorites
            document.getElementById('favoritesTable').innerHTML = '<p>No favorites found.</p>';
        }
    })
    .catch(error => console.error('Error loading favorites:', error));
}
