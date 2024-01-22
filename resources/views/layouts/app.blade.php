<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css', 'resources/css/home.css'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            var modal = document.getElementById('notificationModal');
            var btn = document.getElementById('notification-btn');
            var span = document.getElementById('close');

            btn.onclick = function() {
                modal.style.display = 'block';
                loadFriendRequests(); // Function to load friend requests
            }

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });


        // Function to calculate the midpoint
        function calculateMidpoint(lat1, lng1, lat2, lng2) {
            // Convert degrees to radians
            const rad = Math.PI / 180;
            lat1 *= rad;
            lng1 *= rad;
            lat2 *= rad;
            lng2 *= rad;

            // Calculate differences
            const dLng = lng2 - lng1;
            
            // Calculate midpoint coordinates
            const bx = Math.cos(lat2) * Math.cos(dLng);
            const by = Math.cos(lat2) * Math.sin(dLng);
            const latMid = Math.atan2(Math.sin(lat1) + Math.sin(lat2),
                                    Math.sqrt((Math.cos(lat1) + bx) * (Math.cos(lat1) + bx) + by * by));
            const lngMid = lng1 + Math.atan2(by, Math.cos(lat1) + bx);
            
            // Convert radians back to degrees
            return {
                lat: latMid / rad,
                lng: lngMid / rad
            };
        }
        
        let placesData = [];
        function initMap(userCoords = { lat: 40.7128, lng: -74.0060 }, 
                 friendCoords = { lat: 34.0522, lng: -118.2437 },
                 selectedTypes = []) {
            const midpoint = calculateMidpoint(userCoords.lat, userCoords.lng, friendCoords.lat, friendCoords.lng);


            // Calculate rough distance
            const latDiff = Math.abs(userCoords.lat - friendCoords.lat);
            const lngDiff = Math.abs(userCoords.lng - friendCoords.lng);

            // Determine approximate zoom level
            let zoomLevel = approximateZoomLevel(latDiff, lngDiff);

    
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: zoomLevel,
                center: midpoint
            });

            new google.maps.Marker({ position: userCoords, map: map, title: 'Your Location' });
            new google.maps.Marker({ position: friendCoords, map: map, title: 'Friend Location' });
            // new google.maps.Marker({ position: midpoint, map: map, title: 'Meetup Spot' });

            clearMapMarkers();
            placesData = [];

            // Search for selected types
            selectedTypes.forEach(type => {
                searchPlaces(map, midpoint, type);
            });
        }


        
        function searchPlaces(map, location, type) {
            placesData = []; // Clear existing data
            clearMapMarkers(); // Clear existing markers from the map
            const service = new google.maps.places.PlacesService(map);
            const radius = document.getElementById('searchRadius').value; // Get the selected radius

            service.nearbySearch({
                location: location,
                radius: parseInt(radius, 10), // Parse the radius to an integer
                type: type
            }, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK && results) {
                    results.forEach(place => {
                        createMarker(map, place);
                        // Add place data to the array
                        placesData.push({
                            name: place.name,
                            address: place.vicinity,
                            type: type,
                            geometry: place.geometry 
                        });
                    });
                    // Call the function to display the table with new results
                    displayPlacesTable();
                } else {
                    // If no results or an error, display an empty table
                    displayPlacesTable();
                }
            });
        }




        function displayPlacesTable() {
            let tableContent = '<table class="places-table"><thead><tr><th>Name</th><th>Address</th><th>Type</th><th>Favorite</th></tr></thead><tbody>';
            console.log(placesData);
            if (placesData.length === 0) {
                tableContent += '<tr><td colspan="5">No places found. Please select types and submit again.</td></tr>';
            } else {
                placesData.forEach(place => {
                    const lat = place.geometry && place.geometry.location ? place.geometry.location.lat() : 'N/A';
                    const lng = place.geometry && place.geometry.location ? place.geometry.location.lng() : 'N/A';
                    
                    tableContent += `<tr>
                                        <td>${place.name}</td>
                                        <td>${place.address}</td>
                                        <td>${place.type}</td>
                                        <td>
                                            <button class="favorite-btn rounded p-2 bg-yellow-500 hover:bg-yellow-400"
                                                data-type="${place.type}" 
                                                data-name="${place.name}" 
                                                data-address="${place.address}" 
                                                data-lat="${lat}" 
                                                data-lng="${lng}">Favorite</button>
                                        </td>
                                    </tr>`;
                });
            }
            tableContent += '</tbody></table>';
            document.getElementById('placesTable').innerHTML = tableContent;
        }



        let mapMarkers = [];

        function clearMapMarkers() {
            for (let marker of mapMarkers) {
                marker.setMap(null);
            }
            mapMarkers = []; // Clear the array
        }

        function createMarker(map, place) {
            if (!place.geometry || !place.geometry.location) return;

            const marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location
            });

            // Optionally, add an info window
            google.maps.event.addListener(marker, 'click', () => {
                const infowindow = new google.maps.InfoWindow({
                    content: place.name
                });
                infowindow.open(map, marker);
            });

            // Add the marker to the global array
            mapMarkers.push(marker);
        }



        function approximateZoomLevel(latDiff, lngDiff) {
            const maxDiff = Math.max(latDiff, lngDiff);
            if (maxDiff < 0.01) return 15; // Very close
            if (maxDiff < 0.02) return 14; // Close
            if (maxDiff < 0.05) return 13; // Moderate distance
            if (maxDiff < 0.1) return 12; // City scale
            if (maxDiff < 0.2) return 11; // Larger area
            if (maxDiff < 0.5) return 10; // Regional
            if (maxDiff < 1) return 9; // Larger regional
            if (maxDiff < 2) return 8; // Country scale
            return 7; // Continental scale
        }



        function loadFriendRequests() {
            fetch('/pending-friends-requests')
                .then(response => response.json())
                .then(data => {
                    var requestsContainer = document.getElementById('friendRequestsContainer');
                    var table = document.createElement('table');
                    table.classList.add('w-full', 'table-auto');
                    table.innerHTML = `
                        <thead>
                            <tr>
                                <th class="px-0 py-2">Profile Picture</th>
                                <th class="px-0 py-2">Sender Name</th>
                                <th colspan="2" class="px-0 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="w-full">
                        </tbody>`;
                    var tbody = table.querySelector('tbody');

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="px-0 py-2">No new friend requests.</td></tr>';
                    } else {
                        data.forEach(function(request) {
                            var row = tbody.insertRow();
                            row.id = 'request-row-' + request.id; 
                            row.classList.add('border-b', 'border-gray-200');

                            row.innerHTML = `
                                <td class="px-0 py-2"><img src="${request.profilePictureUrl}" alt="Profile Picture" class="h-10 w-10 rounded-full"></td>
                                <td class="px-0 py-2">${request.senderName}</td>
                                <td class="px-1 py-2"><button class="rounded text-white px-2 py-2 bg-sky-600 hover:bg-sky-500" onclick="acceptRequest(${request.id})">Accept</button></td>
                                <td class="px-1 py-2"><button class="rounded text-white px-2 py-2 bg-red-600 hover:bg-red-500" onclick="rejectRequest(${request.id})">Reject</button></td>`;
                        });
                    }

                    requestsContainer.innerHTML = ''; // Clear existing content
                    requestsContainer.appendChild(table); // Append the table to the container
                })
                .catch(error => console.error('Error:', error));
        }


        function showNotification(message) {
            var notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.remove('hidden');

            // Hide the notification after 3 seconds
            setTimeout(function() {
                notification.classList.add('hidden');
            }, 3000);
        }

        function acceptRequest(requestId) {
            fetch(`/friend-requests/accept/${requestId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                removeRequestRow(requestId);
                updateFriendRequestsCount();
                showNotification('Friend request accepted.');
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error processing request.');
            });
        }

        function rejectRequest(requestId) {
            fetch(`/friend-requests/reject/${requestId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
                removeRequestRow(requestId);
                updateFriendRequestsCount();
                showNotification('Friend request rejected.');
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error processing request.');
            });
        }

        function removeRequestRow(requestId) {
            var row = document.getElementById('request-row-' + requestId);
            if (row) {
                row.remove();
            }
        }


        function updateFriendRequestsCount() {
            fetch('/friend-requests/count')
                .then(response => response.json())
                .then(data => {
                    const count = data.count;
                    const countElements = document.querySelectorAll('.friend-requests-count');

                    countElements.forEach(element => {
                        if (count > 0) {
                            element.textContent = count;
                            element.classList.remove('hidden'); 
                        } else {
                            element.classList.add('hidden'); 
                        }
                    });
                })
                .catch(error => console.error('Error:', error));
        }



// Function to get coordinates from an address
function getCoordinates(address) {
    return fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=AIzaSyDASA8fmLTGHD2P2wTN5Bh9S5NKOET-Gtc`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "OK") {
                return data.results[0].geometry.location;
            } else {
                throw new Error('Geocoding failed');
            }
        });
}




        updateFriendRequestsCount();

    </script>

</head>
<body>


    

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container flex justify-between">

                <div id="notification" class="hidden z-1000 fixed top-0 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded">
                    <!-- Notification message will go here -->
                </div>

                <div class="flex">
                    <div class="flex justify-center items-center pr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40">
                            <path fill="#0369a1" d="M320 64A64 64 0 1 0 192 64a64 64 0 1 0 128 0zm-96 96c-35.3 0-64 28.7-64 64v48c0 17.7 14.3 32 32 32h1.8l11.1 99.5c1.8 16.2 15.5 28.5 31.8 28.5h38.7c16.3 0 30-12.3 31.8-28.5L318.2 304H320c17.7 0 32-14.3 32-32V224c0-35.3-28.7-64-64-64H224zM132.3 394.2c13-2.4 21.7-14.9 19.3-27.9s-14.9-21.7-27.9-19.3c-32.4 5.9-60.9 14.2-82 24.8c-10.5 5.3-20.3 11.7-27.8 19.6C6.4 399.5 0 410.5 0 424c0 21.4 15.5 36.1 29.1 45c14.7 9.6 34.3 17.3 56.4 23.4C130.2 504.7 190.4 512 256 512s125.8-7.3 170.4-19.6c22.1-6.1 41.8-13.8 56.4-23.4c13.7-8.9 29.1-23.6 29.1-45c0-13.5-6.4-24.5-14-32.6c-7.5-7.9-17.3-14.3-27.8-19.6c-21-10.6-49.5-18.9-82-24.8c-13-2.4-25.5 6.3-27.9 19.3s6.3 25.5 19.3 27.9c30.2 5.5 53.7 12.8 69 20.5c3.2 1.6 5.8 3.1 7.9 4.5c3.6 2.4 3.6 7.2 0 9.6c-8.8 5.7-23.1 11.8-43 17.3C374.3 457 318.5 464 256 464s-118.3-7-157.7-17.9c-19.9-5.5-34.2-11.6-43-17.3c-3.6-2.4-3.6-7.2 0-9.6c2.1-1.4 4.8-2.9 7.9-4.5c15.3-7.7 38.8-14.9 69-20.5z"/>
                        </svg>
                    </div>
    
                    <a class="navbar-brand -ml-3 sm:-ml-0" href="{{ url('/home') }}">
                        {{ config('app.name', 'Meet Me Halfway') }}
                    </a>
    
                </div>



                @if(Auth::user())
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <span class="friend-requests-count bg-red-600 text-white rounded-full text-xs px-2 ml-1 hidden"></span>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end pt-0 pb-0" aria-labelledby="navbarDropdown">

                        <a href="/messages" class="dropdown-item cursor-pointer hover:bg-gray-200" id="profile-btn">
                            Messages
                        </a>

                        <div class="dropdown-item cursor-pointer hover:bg-gray-200" id="notification-btn">
                            Friend Requests <span class="friend-requests-count bg-red-600 text-white rounded-full text-xs px-2 ml-1"></span>
                        </div>
                        
                        <a href="/profile" class="dropdown-item cursor-pointer hover:bg-gray-200" id="profile-btn">
                            Profile
                        </a>
                        <a class="dropdown-item  hover:bg-gray-200 border border-t-black" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                @endif

            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>


    <!-- Notification Modal -->
    <div id="notificationModal" class="notification-modal">
        <div class="notification-modal-content rounded">
            <span id="close" class="close text-4xl -mt-5">&times;</span>
            <h2 class="w-full flex justify-center text-xl text-sky-600 mb-5">Friend Requests</h2>
            <div id="friendRequestsContainer">
                <!-- Friend requests will be populated here -->
            </div>
        </div>
    </div>



    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDASA8fmLTGHD2P2wTN5Bh9S5NKOET-Gtc&callback=initMap&libraries=places"></script>

</body>
</html>
