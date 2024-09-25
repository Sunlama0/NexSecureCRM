@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md mt-4">
        <!-- Titre principal -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center space-x-2">
            <i class="fas fa-user-circle text-indigo-600"></i>
            <span>Profil de l'utilisateur</span>
        </h2>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulaire de modification du profil -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Image de profil -->
            <div class="flex items-center mb-6">
                <div class="mr-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image de profil</label>
                    <div
                        class="relative w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-300 shadow-sm">
                        <img id="profile-image-preview" src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/nexsecure.png') }}"
                            alt="Image de profil" class="h-full w-full object-cover">
                    </div>
                </div>
                <div class="flex flex-col justify-between">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Changer l'image</label>
                    <input type="file" name="profile_image" id="profile-image-upload" accept="image/*"
                        class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none">
                    <small class="text-gray-500 mt-2">Formats acceptés: JPEG, PNG, JPG (Max: 2MB)</small>
                    @error('profile_image')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Section d'informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-user text-gray-500"></i>
                        <span>Prénom</span>
                    </label>
                    <input type="text" id="first_name" name="first_name"
                        value="{{ old('first_name', $user->first_name) }}" required
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('first_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-user text-gray-500"></i>
                        <span>Nom</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                        required
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('last_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-envelope text-gray-500"></i>
                        <span>Email</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-phone text-gray-500"></i>
                        <span>Numéro de téléphone</span>
                    </label>
                    <input type="text" id="phone_number" name="phone_number"
                        value="{{ old('phone_number', $user->phone_number) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="job_title" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-briefcase text-gray-500"></i>
                        <span>Poste</span>
                    </label>
                    <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $user->job_title) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('job_title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Section d'adresse -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-globe text-gray-500"></i>
                        <span>Pays</span>
                    </label>
                    <select id="country" name="country"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="France" {{ old('country', $user->country) == 'France' ? 'selected' : '' }}>France</option>
                        <option value="United States" {{ old('country', $user->country) == 'United States' ? 'selected' : '' }}>États-Unis</option>
                        <option value="Canada" {{ old('country', $user->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="Germany" {{ old('country', $user->country) == 'Germany' ? 'selected' : '' }}>Allemagne</option>
                    </select>
                    @error('country')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-city text-gray-500"></i>
                        <span>Ville</span>
                    </label>
                    <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('city')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-home text-gray-500"></i>
                        <span>Adresse</span>
                    </label>
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="linkedin" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fab fa-linkedin text-gray-500"></i>
                        <span>LinkedIn</span>
                    </label>
                    <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('linkedin')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="github" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fab fa-github text-gray-500"></i>
                        <span>Github</span>
                    </label>
                    <input type="url" id="github" name="github" value="{{ old('github', $user->github) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('github')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="instagram" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fab fa-instagram text-gray-500"></i>
                        <span>Instagram</span>
                    </label>
                    <input type="url" id="instagram" name="instagram"
                        value="{{ old('instagram', $user->instagram) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('instagram')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Section Bio -->
            <div class="mt-6">
                <label for="bio" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                    <i class="fas fa-pen-nib text-gray-500"></i>
                    <span>Biographie</span>
                </label>
                <textarea id="bio" name="bio" rows="4"
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Mot de passe et confirmation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-lock text-gray-500"></i>
                        <span>Mot de passe (laisser vide pour ne pas changer)</span>
                    </label>
                    <input type="password" id="password" name="password"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-lock text-gray-500"></i>
                        <span>Confirmer le mot de passe</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Bouton de mise à jour -->
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Mettre à jour le profil
                </button>
            </div>
        </form>
    </div>

    <!-- Script pour prévisualiser l'image lors de l'upload -->
    <script>
        document.getElementById('profile-image-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-image-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
