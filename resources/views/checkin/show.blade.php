@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Event Check-In</h1>
                    <p class="text-gray-600">{{ $event->title }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $event->date->format('M d, Y') }} • {{ $event->time->format('g:i A') }}</p>
                </div>

                <form method="POST" action="{{ route('checkin.store', $event->unique_code) }}" class="space-y-6" id="checkin-form">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Enter your full name"
                            value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" name="phone" id="phone" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Enter your phone number"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Enter your email (optional)"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                        <div class="flex gap-4">
                            <label class="flex-1">
                                <input type="radio" name="gender" value="male" class="sr-only peer" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                <div class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center cursor-pointer peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 hover:bg-gray-50 transition">
                                    Male
                                </div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="gender" value="female" class="sr-only peer" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                <div class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center cursor-pointer peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 hover:bg-gray-50 transition">
                                    Female
                                </div>
                            </label>
                        </div>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="zone_id" class="block text-sm font-medium text-gray-700 mb-2">Zone / Location *</label>
                        <select name="zone_id" id="zone_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Select your zone...</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }}
                                </option>
                            @endforeach
                            <option value="other" {{ old('zone_id') == 'other' ? 'selected' : '' }}>Other (specify below)</option>
                        </select>
                        @error('zone_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="custom-location-wrapper" class="hidden">
                        <label for="custom_location" class="block text-sm font-medium text-gray-700 mb-2">Your Location *</label>
                        <input type="text" name="custom_location" id="custom_location"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Enter your location"
                            value="{{ old('custom_location') }}">
                        @error('custom_location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="referral_source" class="block text-sm font-medium text-gray-700 mb-2">How did you hear about us?</label>
                        <select name="referral_source" id="referral_source"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Select an option (optional)...</option>
                            <option value="friend" {{ old('referral_source') == 'friend' ? 'selected' : '' }}>Friend/Colleague</option>
                            <option value="family" {{ old('referral_source') == 'family' ? 'selected' : '' }}>Family Member</option>
                            <option value="social_media" {{ old('referral_source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="flyer" {{ old('referral_source') == 'flyer' ? 'selected' : '' }}>Flyer/Poster</option>
                            <option value="website" {{ old('referral_source') == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="passing_by" {{ old('referral_source') == 'passing_by' ? 'selected' : '' }}>Passing By</option>
                            <option value="other" {{ old('referral_source') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('referral_source')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white py-4 rounded-lg font-bold text-lg shadow-lg transform hover:scale-105 transition duration-300">
                        Check In Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const zoneSelect = document.getElementById('zone_id');
        const customLocationWrapper = document.getElementById('custom-location-wrapper');
        const customLocationInput = document.getElementById('custom_location');

        function toggleCustomLocation() {
            if (zoneSelect.value === 'other') {
                customLocationWrapper.classList.remove('hidden');
                customLocationInput.required = true;
            } else {
                customLocationWrapper.classList.add('hidden');
                customLocationInput.required = false;
            }
        }

        zoneSelect.addEventListener('change', toggleCustomLocation);
        toggleCustomLocation(); // Run on page load
    </script>
@endsection
