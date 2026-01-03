<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Check In - {{ $event->title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { display: none; }
        body { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-gray-100 min-h-screen" x-data="kioskCheckIn()">

    <!-- Header Bar -->
    <div class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 px-6 py-4 z-10">
        <div class="flex items-center justify-between max-w-2xl mx-auto">
            <div>
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Check In</div>
                <div class="text-lg font-bold text-gray-900">{{ $event->title }}</div>
            </div>
            <div class="text-right text-sm text-gray-500">
                {{ $event->date->format('M j, Y') }}
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pt-24 pb-8 px-6 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-lg">

            <!-- Initial Selection -->
            <div x-show="step === 'select'" x-transition class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Welcome!</h2>
                <p class="text-xl text-gray-600 mb-10">Is this your first time here?</p>

                <div class="space-y-4">
                    <button @click="step = 'new'" class="w-full py-6 px-8 bg-gray-900 hover:bg-gray-800 active:bg-gray-700 text-white text-xl font-semibold rounded-2xl transition-colors">
                        I'm New Here
                    </button>
                    <button @click="step = 'returning'" class="w-full py-6 px-8 bg-white hover:bg-gray-50 active:bg-gray-100 text-gray-900 text-xl font-semibold rounded-2xl border-2 border-gray-200 transition-colors">
                        I've Been Before
                    </button>
                </div>
            </div>

            <!-- Returning Member -->
            <div x-show="step === 'returning'" x-transition class="text-center">
                <button @click="step = 'select'; resetReturning()" class="mb-8 text-gray-500 hover:text-gray-700 flex items-center gap-2 mx-auto text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>

                <div x-show="!foundMember">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Enter your phone number</h2>

                    <input type="tel" x-model="returningPhone" @keyup.enter="lookupMember()" autofocus
                        class="w-full px-6 py-5 text-2xl text-center border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        placeholder="08012345678">

                    <button @click="lookupMember()" :disabled="lookingUp"
                        class="w-full mt-6 py-5 px-8 bg-gray-900 hover:bg-gray-800 disabled:bg-gray-400 text-white text-xl font-semibold rounded-2xl transition-colors">
                        <span x-show="!lookingUp">Find Me</span>
                        <span x-show="lookingUp">Searching...</span>
                    </button>

                    <p x-show="lookupError" x-text="lookupError" class="mt-4 text-lg text-red-600"></p>
                </div>

                <div x-show="foundMember" x-transition class="py-8">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xl text-gray-600 mb-2">Welcome back,</p>
                    <p class="text-4xl font-bold text-gray-900 mb-10" x-text="foundMember?.name"></p>

                    <button @click="checkInReturning()" :disabled="submitting"
                        class="w-full py-6 px-8 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white text-2xl font-bold rounded-2xl transition-colors">
                        <span x-show="!submitting">Check In</span>
                        <span x-show="submitting">Checking in...</span>
                    </button>
                </div>
            </div>

            <!-- New Member -->
            <div x-show="step === 'new'" x-transition>
                <button @click="step = 'select'" class="mb-6 text-gray-500 hover:text-gray-700 flex items-center gap-2 text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>

                <h2 class="text-2xl font-bold text-gray-900 mb-8">We're glad you're here!</h2>

                <form @submit.prevent="checkInNew()" class="space-y-5">
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" x-model="newMember.name" required
                            class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="Your full name">
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" x-model="newMember.phone" required
                            class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="08012345678">
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2">Email <span class="text-gray-400">(optional)</span></label>
                        <input type="email" x-model="newMember.email"
                            class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="you@example.com">
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">Gender</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" x-model="newMember.gender" value="male" class="peer sr-only" required>
                                <div class="py-4 px-6 border-2 border-gray-200 rounded-xl text-center text-lg font-medium text-gray-700 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white transition-colors">
                                    Male
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" x-model="newMember.gender" value="female" class="peer sr-only">
                                <div class="py-4 px-6 border-2 border-gray-200 rounded-xl text-center text-lg font-medium text-gray-700 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white transition-colors">
                                    Female
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2">Where are you coming from?</label>
                        <select x-model="newMember.zone_id" required
                            class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            <option value="">Select your zone...</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                            <option value="other">Other location</option>
                        </select>
                    </div>

                    <div x-show="newMember.zone_id === 'other'" x-transition>
                        <label class="block text-lg font-medium text-gray-700 mb-2">Your Location</label>
                        <input type="text" x-model="newMember.custom_location" :required="newMember.zone_id === 'other'"
                            class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            placeholder="Enter your location">
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-2">How did you hear about us?</label>
                        <select x-model="newMember.referral_source"
                            class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            <option value="">Select an option...</option>
                            <option value="friend">Friend/Colleague</option>
                            <option value="family">Family Member</option>
                            <option value="social_media">Social Media</option>
                            <option value="flyer">Flyer/Poster</option>
                            <option value="website">Website</option>
                            <option value="passing_by">Just Passing By</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <p x-show="formError" x-text="formError" class="text-lg text-red-600"></p>

                    <button type="submit" :disabled="submitting"
                        class="w-full py-5 px-8 bg-gray-900 hover:bg-gray-800 disabled:bg-gray-400 text-white text-xl font-semibold rounded-2xl transition-colors mt-8">
                        <span x-show="!submitting">Check In</span>
                        <span x-show="submitting">Checking in...</span>
                    </button>
                </form>
            </div>

            <!-- Success State -->
            <div x-show="step === 'success'" x-transition class="text-center py-12">
                <div class="w-32 h-32 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h2 class="text-4xl font-bold text-gray-900 mb-4">You're Checked In!</h2>
                <p class="text-2xl text-gray-600 mb-12" x-text="successName"></p>

                <div class="text-gray-500">
                    <span class="text-lg">Returning to start in </span>
                    <span class="text-2xl font-bold" x-text="countdown"></span>
                    <span class="text-lg"> seconds</span>
                </div>

                <button @click="reset()" class="mt-8 text-gray-500 hover:text-gray-700 text-lg underline">
                    Check in another person
                </button>
            </div>
        </div>
    </div>

    <script>
        function kioskCheckIn() {
            return {
                step: 'select',
                returningPhone: '',
                lookingUp: false,
                lookupError: '',
                foundMember: null,
                submitting: false,
                formError: '',
                successName: '',
                countdown: 10,
                countdownInterval: null,
                newMember: {
                    name: '',
                    phone: '',
                    email: '',
                    gender: '',
                    zone_id: '',
                    custom_location: '',
                    referral_source: ''
                },

                resetReturning() {
                    this.returningPhone = '';
                    this.lookupError = '';
                    this.foundMember = null;
                },

                reset() {
                    this.step = 'select';
                    this.returningPhone = '';
                    this.lookupError = '';
                    this.foundMember = null;
                    this.submitting = false;
                    this.formError = '';
                    this.successName = '';
                    this.countdown = 10;
                    this.newMember = {
                        name: '',
                        phone: '',
                        email: '',
                        gender: '',
                        zone_id: '',
                        custom_location: '',
                        referral_source: ''
                    };
                    if (this.countdownInterval) {
                        clearInterval(this.countdownInterval);
                    }
                },

                showSuccess(name) {
                    this.step = 'success';
                    this.successName = name;
                    this.countdown = 10;
                    this.countdownInterval = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            this.reset();
                        }
                    }, 1000);
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
                            this.lookupError = "We couldn't find you. Try the 'I'm New Here' option.";
                        }
                    } catch (error) {
                        this.lookupError = 'Something went wrong. Please try again.';
                    } finally {
                        this.lookingUp = false;
                    }
                },

                async checkInReturning() {
                    this.submitting = true;

                    try {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('returning', '1');
                        formData.append('member_id', this.foundMember.id);

                        const response = await fetch('{{ route("checkin.store", $event->unique_code) }}', {
                            method: 'POST',
                            body: formData
                        });

                        // The server redirects, but we want to show success inline
                        this.showSuccess(this.foundMember.name);
                    } catch (error) {
                        this.lookupError = 'Something went wrong. Please try again.';
                        this.submitting = false;
                    }
                },

                async checkInNew() {
                    if (!this.newMember.name || !this.newMember.phone || !this.newMember.gender || !this.newMember.zone_id) {
                        this.formError = 'Please fill in all required fields.';
                        return;
                    }

                    if (this.newMember.zone_id === 'other' && !this.newMember.custom_location) {
                        this.formError = 'Please enter your location.';
                        return;
                    }

                    this.submitting = true;
                    this.formError = '';

                    try {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('name', this.newMember.name);
                        formData.append('phone', this.newMember.phone);
                        formData.append('email', this.newMember.email);
                        formData.append('gender', this.newMember.gender);
                        formData.append('zone_id', this.newMember.zone_id);
                        formData.append('custom_location', this.newMember.custom_location);
                        formData.append('referral_source', this.newMember.referral_source);

                        const response = await fetch('{{ route("checkin.store", $event->unique_code) }}', {
                            method: 'POST',
                            body: formData
                        });

                        this.showSuccess(this.newMember.name);
                    } catch (error) {
                        this.formError = 'Something went wrong. Please try again.';
                        this.submitting = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
