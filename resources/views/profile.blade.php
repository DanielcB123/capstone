@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    {{-- Display Success Message --}}
    @if (session('success'))
    <div class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Success!</strong>
        <br>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Display Error Message --}}
    @if ($errors->any())
    <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Whoops!</strong>
        <br>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif





    <h2 class="text-2xl font-semibold mb-4">User Profile</h2>
    <div class="flex flex-wrap -mx-2">
        <!-- Profile Information -->
        <div class="w-full lg:w-1/2 px-2 mb-4">
            <p class="text-lg"><strong>Name:</strong> {{ $user->name }}</p>
            <p class="text-lg"><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <!-- Profile Picture -->
        <div class="w-full lg:w-1/2 px-2 mb-4">
            <img src="{{ $user->profile_image_path }}" alt="Profile Picture" class="rounded-lg shadow-md mb-4 w-1/4">
            <form action="{{ route('profile.uploadImage') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="file" name="profile_picture" class="block w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:border-0
                  file:text-sm file:font-semibold
                  file:bg-violet-50 file:text-violet-700
                  hover:file:bg-violet-100
                ">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Change Picture</button>
            </form>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <form action="{{ route('profile.update') }}" method="POST" class="mt-6 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h4 class="w-full flex justify-center items-center text-2xl">Edit Personal Information</h4>
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" value="{{ $user->name }}">
        </div>
        <div class="mb-6">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="email" value="{{ $user->email }}">
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Profile</button>
    </form>


    <form action="{{ route('profile.updatePassword') }}" method="POST" class="mt-6 bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h4 class="w-full flex justify-center items-center text-2xl">Change Password</h4>
        @csrf
    
        <div class="mb-4">
            <label for="current_password" class="block text-gray-700 text-sm font-bold mb-2">Current Password:</label>
            <input type="password" id="current_password" name="current_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
    
        <div class="mb-4">
            <label for="newpassword" class="block text-gray-700 text-sm font-bold mb-2">New Password:</label>
            <input type="password" id="newpassword" name="newpassword" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
    
        <div class="mb-6">
            <label for="confirmpassword" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password:</label>
            <input type="password" id="confirmpassword" name="confirmpassword" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
    
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Change Password</button>
    </form>
    


</div>

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            if (alerts) {
                alerts.forEach(alert => {
                    alert.style.display = 'none';
                });
            }
        }, 2000);
    });
</script>

@endsection
