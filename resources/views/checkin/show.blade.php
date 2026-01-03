@extends('layouts.app')

@section('title', 'Check In - ' . $event->title)

@section('content')
<div class="min-h-screen flex items-center justify-center p-4" x-data="checkIn()">
    <div class="w-full max-w-md">
        <!-- Event Header -->
        <div class="text-center mb-8">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Welcome to</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $event->title }}</h1>
            <p class="text-gray-500 mt-1">{{ $event->date->format('F j, Y') }} &bull; {{ $event->time->format('g:i A') }}</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Initial Selection -->
            <div x-show="step === 'select'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="p-8">
                <p class="text-center text-gray-600 mb-6">Is this your first time here?</p>

                <div class="space-y-3">
                    <button @click="step = 'new'" class="w-full py-4 px-6 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl transition-colors">
                        I'm New Here
                    </button>
                    <button @click="step = 'returning'" class="w-full py-4 px-6 bg-white hover:bg-gray-50 text-gray-900 font-semibold rounded-xl border-2 border-gray-200 transition-colors">
                        I've Been Before
                    </button>
                </div>
            </div>

            <!-- Returning Member Flow -->
            <div x-show="step === 'returning'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-8">
                <button @click="step = 'select'; resetReturning()" class="text-gray-500 hover:text-gray-700 mb-4 flex items-center gap-1 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>

                <h2 class="text-xl font-semibold text-gray-900 mb-6">Welcome back!</h2>

                <!-- Phone Input -->
                <div x-show="!foundMember">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Enter your phone number</label>
                    <div class="flex gap-3">
                        <input type="tel" x-model="returningPhone" @keyup.enter="lookupMember()"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="08012345678">
                        <button @click="lookupMember()" :disabled="lookingUp"
                            class="px-6 py-3 bg-gray-900 hover:bg-gray-800 disabled:bg-gray-400 text-white font-medium rounded-xl transition-colors">
                            <span x-show="!lookingUp">Find</span>
                            <span x-show="lookingUp">...</span>
                        </button>
                    </div>
                    <p x-show="lookupError" x-text="lookupError" class="mt-2 text-sm text-red-600"></p>
                </div>

                <!-- Found Member -->
                <div x-show="foundMember" x-transition class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-1">Welcome back,</p>
                    <p class="text-2xl font-bold text-gray-900 mb-6" x-text="foundMember?.name"></p>

                    <form method="POST" action="{{ route('checkin.store', $event->unique_code) }}">
                        @csrf
                        <input type="hidden" name="member_id" :value="foundMember?.id">
                        <input type="hidden" name="returning" value="1">
                        <button type="submit" class="w-full py-4 px-6 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors text-lg">
                            Check In
                        </button>
                    </form>
                </div>
            </div>

            <!-- New Member Flow -->
            <div x-show="step === 'new'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-8">
                <button @click="step = 'select'" class="text-gray-500 hover:text-gray-700 mb-4 flex items-center gap-1 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>

                <h2 class="text-xl font-semibold text-gray-900 mb-6">We're glad you're here!</h2>

                <form method="POST" action="{{ route('checkin.store', $event->unique_code) }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="Your full name">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" required value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="08012345678">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-gray-400">(optional)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="you@example.com">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="gender" value="male" class="peer sr-only" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                <div class="py-3 px-4 border-2 border-gray-200 rounded-xl text-center font-medium text-gray-700 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white transition-colors">
                                    Male
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="gender" value="female" class="peer sr-only" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                <div class="py-3 px-4 border-2 border-gray-200 rounded-xl text-center font-medium text-gray-700 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white transition-colors">
                                    Female
                                </div>
                            </label>
                        </div>
                        @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Zone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Where are you coming from?</label>
                        <select name="zone_id" id="zone_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            <option value="">Select your zone...</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                            @endforeach
                            <option value="other" {{ old('zone_id') == 'other' ? 'selected' : '' }}>Other location</option>
                        </select>
                        @error('zone_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Custom Location -->
                    <div id="custom-location-wrapper" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Location</label>
                        <input type="text" name="custom_location" id="custom_location" value="{{ old('custom_location') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="Enter your location">
                        @error('custom_location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Referral Source -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">How did you hear about us? <span class="text-gray-400">(optional)</span></label>
                        <select name="referral_source"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            <option value="">Select an option...</option>
                            <option value="friend" {{ old('referral_source') == 'friend' ? 'selected' : '' }}>Friend/Colleague</option>
                            <option value="family" {{ old('referral_source') == 'family' ? 'selected' : '' }}>Family Member</option>
                            <option value="social_media" {{ old('referral_source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="flyer" {{ old('referral_source') == 'flyer' ? 'selected' : '' }}>Flyer/Poster</option>
                            <option value="website" {{ old('referral_source') == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="passing_by" {{ old('referral_source') == 'passing_by' ? 'selected' : '' }}>Just Passing By</option>
                            <option value="other" {{ old('referral_source') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl transition-colors text-lg mt-6">
                        Check In
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-sm mt-6">{{ $event->location }}</p>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function checkIn() {
        return {
            step: 'select',
            returningPhone: '',
            lookingUp: false,
            lookupError: '',
            foundMember: null,

            resetReturning() {
                this.returningPhone = '';
                this.lookupError = '';
                this.foundMember = null;
            },

            async lookupMember() {
                if (!this.returningPhone.trim()) {
                    this.lookupError = 'Please enter your phone number';
                    return;
                }

                this.lookingUp = true;
                this.lookupError = '';

                try {
                    const response = await fetch('{{ route("checkin.lookup", $event->unique_code) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ phone: this.returningPhone })
                    });

                    const data = await response.json();

                    if (data.found) {
                        this.foundMember = data.member;
                    } else {
                        this.lookupError = "We couldn't find you. Are you sure you've been here before?";
                    }
                } catch (error) {
                    this.lookupError = 'Something went wrong. Please try again.';
                } finally {
                    this.lookingUp = false;
                }
            }
        }
    }

    // Custom location toggle
    document.addEventListener('DOMContentLoaded', function() {
        const zoneSelect = document.getElementById('zone_id');
        const customWrapper = document.getElementById('custom-location-wrapper');
        const customInput = document.getElementById('custom_location');

        if (zoneSelect) {
            zoneSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    customWrapper.classList.remove('hidden');
                    customInput.required = true;
                } else {
                    customWrapper.classList.add('hidden');
                    customInput.required = false;
                }
            });

            // Trigger on load if 'other' is selected
            if (zoneSelect.value === 'other') {
                customWrapper.classList.remove('hidden');
                customInput.required = true;
            }
        }
    });
</script>
@endsection
