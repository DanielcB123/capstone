@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 w-full">
    <h2 class="text-2xl font-semibold mb-4">Messages</h2>

    <div class="flex flex-wrap -mx-2 w-full">
        <!-- Sidebar for message categories -->
        <div class="w-full lg:w-1/4 px-2 mb-4">
            <div class="bg-white rounded-lg shadow px-4 py-6 w-full">
                <ul class="space-y-4 w-full">
                    <li><a href="#" class="w-full text-sky-600 hover:text-sky-500 font-semibold text-lg">Inbox</a></li>
                    <li><a href="#" class="text-sky-600 hover:text-sky-500 font-semibold text-lg">Sent</a></li>
                    <li><a href="#" class="text-sky-600 hover:text-sky-500 font-semibold text-lg">New Messages</a></li>
                </ul>
                <!-- Compose Message Button -->
                <button id="composeMessageBtn" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Compose Message</button>
            </div>
        </div>

        <!-- Main content area for messages -->
        <div class="w-full lg:w-3/4 px-2">
            <div class="bg-white rounded-lg shadow px-4 py-6">
                <p>Select a category to view messages.</p>
            </div>
        </div>
    </div>

    <!-- Compose Message Modal -->
    <div id="composeMessageModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
            <!-- Close Button -->
            <button id="closeModalBtn" class="absolute top-0 right-0 mt-4 mr-4 text-2xl text-gray-600 hover:text-gray-900">
                &times;
            </button>

            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Compose New Message</h3>
                <div class="mt-2 px-7 py-3">
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="friend" class="block text-gray-700 text-sm font-bold mb-2">Select Friend:</label>
                            <select id="friend" name="friend" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                {{-- TODO: Populate with friends --}}
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Message:</label>
                            <textarea id="message" name="message" rows="4" class="shadow border rounded w-full py-2 px-3 text-gray-700" required></textarea>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="sendMessageBtn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Conversation View -->
                <div class="mt-4 overflow-y-auto" style="max-height: 50vh;">
                    <div id="conversation" class="space-y-2">
                        <!-- Dummy Messages For DEV -->
                        <div class="p-2 bg-gray-200 rounded-lg">Hi there! How are you?</div>
                        <div class="p-2 bg-blue-200 rounded-lg text-right">I'm good, thanks for asking!</div>
                        <div class="p-2 bg-gray-200 rounded-lg">Glad to hear that. Are we still meeting tomorrow?</div>
                        <div class="p-2 bg-blue-200 rounded-lg text-right">Yes, absolutely.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('composeMessageBtn').addEventListener('click', function() {
            document.getElementById('composeMessageModal').classList.remove('hidden');
        });

        document.getElementById('closeModalBtn').addEventListener('click', function() {
            document.getElementById('composeMessageModal').classList.add('hidden');
        });
    });
</script>

@endsection
