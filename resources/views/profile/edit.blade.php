@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Account Settings</h1>

        <!-- Profile Information -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Profile Information</h2>
            
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <x-input-label for="name" value="Full Name" />
                    <x-text-input id="name" type="text" name="name" :value="old('name', $user->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" type="email" name="email" :value="old('email', $user->email)" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Bio -->
                <div>
                    <x-input-label for="bio" value="Bio" />
                    <textarea 
                        id="bio" 
                        name="bio" 
                        rows="3"
                        class="input"
                        placeholder="Tell us about yourself..."
                    >{{ old('bio', $user->bio) }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Update Profile</button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="mb-8 pt-8 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Change Password</h2>
            
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="password" value="New Password" />
                    <x-text-input id="password" type="password" name="password" autocomplete="new-password" />
                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirm New Password" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Update Password</button>
                </div>
            </form>
        </div>

        <!-- Logout -->
        <div class="mb-8 pt-8 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Logout</h2>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-outline">
                    Log Out
                </button>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="pt-8 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 text-secondary">Danger Zone</h2>
            
            <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you sure? This action cannot be undone. All your data will be permanently deleted.');">
                @csrf
                @method('DELETE')

                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-sm text-gray-700 mb-4">
                        Once you delete your account, there is no going back. Please be certain.
                    </p>
                    
                    <div class="mb-4">
                        <x-input-label for="delete_password" value="Enter your password to confirm" />
                        <x-text-input 
                            id="delete_password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="input-error"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <button type="submit" class="btn-secondary">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
