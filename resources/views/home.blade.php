@extends('layouts.app')

@section('content')




<div class="container">
    <div class="row justify-content-center">

        

        <div class="col-md-8">
            <button id="findFriendButton" class="w-full bg-cyan-600 hover:bg-cyan-500 mb-4 rounded px-2 py-2 text-lg text-white cursor-pointer">Find A Friend</button>
            <!-- Modal Structure -->
            <div id="findFriendModal" class="find-friend-modal hidden">
                <div class="find-friend-modal-content rounded">
                    <span class="close">&times;</span>
                    <h6 class="flex justify-center text-2xl text-sky-600">Search For Friends</h6>
                    <input type="text" id="searchFriendInput" placeholder="Search for a friend by name..." class="search-input" autocomplete="off">
                    <div id="searchFriendResults" class="pb-12">
                        <table id="friendResultsTable" class="w-full">
                            <!-- Search results will be populated here -->
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ __('Meet Halfway Now') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            
                    <div>
                        <h3 id="formTitle" class="pb-6 pt-2 text-2xl text-sky-600">{{ __('Enter Your Address') }}</h3>
                        <form id="findMeetSpotsForm">
                            <div class="form-group" id="addressInputContainer">
                                <label for="newAddress">Your Address:</label>
                                <input type="text" id="newAddress" class="form-control" placeholder="Enter your starting address">
                                <button type="button" id="addressSubmit" class="btn btn-primary bg-sky-600 hover:bg-sky-500 mt-2.5">Submit Address</button>
                            </div>
                            <div class="form-group hidden" id="friendListContainer">
                                
                                <label for="friendList">Select Friend:</label>
                                <select id="friendList" class="form-control">
                                    <!-- Options will be populated dynamically -->
                                </select>
                                <label for="types" class="text-lg mt-3">Select Types Of Meetup Spots:</label>
                                <div id="placeTypeCheckboxes" class="places-checkboxes">
                                    <label><input type="checkbox" class="place-type" value="establishment"> Establishments</label>
                                    <label><input type="checkbox" class="place-type" value="hospital"> Hospitals</label>
                                    <label><input type="checkbox" class="place-type" value="fire_station"> Fire Stations</label>
                                    <label><input type="checkbox" class="place-type" value="police"> Police Stations</label>
                                    <label><input type="checkbox" class="place-type" value="restaurant"> Restaurants</label>
                                    <label><input type="checkbox" class="place-type" value="cafe"> Cafes</label>
                                    <label><input type="checkbox" class="place-type" value="bar"> Bars</label>
                                </div>
                                <label for="searchRadius"  class="text-lg mt-3">Search Radius From Midway Point (miles):</label>
                                <select id="searchRadius" class="form-control">
                                    <option value="804.672" selected>0.5 miles</option>
                                    <option value="1609.34">1 mile</option>
                                    <option value="3218.68">2 miles</option>
                                    <option value="8046.72">5 miles</option>
                                </select>


                                <div class="w-full flex justify-between">
                                    <button type="submit" class="btn btn-primary bg-sky-600 hover:bg-sky-500 mt-2">Find Meet Spots Halfway</button>
                                    <button type="button" id="backButton" class="btn btn-secondary bg-gray-600 hover:bg-gray-500 mt-2">Back To Address</button>
                                </div>
                            </div>

                            
                        </form>

                        <div id="map" class="mt-2" style="height: 400px; width: 100%;"></div>
                        <div id="placesTable"></div>
                    </div>
                </div>
            </div>
            

            <div class="card mt-5">
                <div class="card-header">{{ __('Favorites') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        <h3 class="pb-6 pt-2 text-2xl text-sky-600">{{ __('Favorite Meet Spots') }}</h3>
                        <div id="favoritesTable"><!-- Favorites will be populated here --></div>
                    </div>
                </div>
            </div>


            {{-- <div class="card mt-5">
                <div class="card-header">{{ __('History') }}</div>
            
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            
                    <div>
                        <h3 class="pb-6 pt-2 text-2xl text-sky-600">{{ __('Previous Meets') }}</h3>
                        <form id="updateAddressForm">

                            
                        </form>
                    </div>
                </div>
            </div> --}}


        </div>
    </div>
</div>


@endsection
