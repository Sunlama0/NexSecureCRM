@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md mt-4">
        <!-- Titre principal -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center space-x-2">
            <i class="fas fa-building text-indigo-600"></i>
            <span>Paramètres de la société</span>
        </h2>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulaire de modification de la société -->
        <form action="{{ route('company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Logo de la société -->
            <div class="flex items-center mb-6">
                <div class="mr-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo de la société</label>
                    <div
                        class="relative w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-300 shadow-sm">
                        <img id="logo-preview" src="{{ $company->logo ? asset('storage/' . $company->logo) : asset('images/nexsecure.png') }}"
                            alt="Logo de la société" class="h-full w-full object-cover">
                    </div>
                </div>
                <div class="flex flex-col justify-between">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Changer le logo</label>
                    <input type="file" name="logo" id="logo-upload" accept="image/*"
                        class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <small class="text-gray-500 mt-2">Formats acceptés: JPEG, PNG, JPG (Max: 2MB)</small>
                    @error('logo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Section d'informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom de la société -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-building text-gray-500"></i>
                        <span>Nom de la société</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Structure de la société -->
                <div class="mb-6">
                    <label for="structure" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-building text-gray-500"></i>
                        <span>Structure (Type d'entreprise)</span>
                    </label>
                    <select id="structure" name="structure"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Sélectionnez la structure</option>
                        <option value="SARL" {{ old('structure', $company->structure) == 'SARL' ? 'selected' : '' }}>SARL</option>
                        <option value="SAS" {{ old('structure', $company->structure) == 'SAS' ? 'selected' : '' }}>SAS</option>
                        <option value="Auto-entrepreneur" {{ old('structure', $company->structure) == 'Auto-entrepreneur' ? 'selected' : '' }}>Auto-entrepreneur</option>
                        <option value="EURL" {{ old('structure', $company->structure) == 'EURL' ? 'selected' : '' }}>EURL</option>
                        <option value="SA" {{ old('structure', $company->structure) == 'SA' ? 'selected' : '' }}>SA</option>
                    </select>
                    @error('structure')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email de la société -->
                <div>
                    <label for="mail" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-envelope text-gray-500"></i>
                        <span>Adresse Email</span>
                    </label>
                    <input type="email" id="mail" name="mail" value="{{ old('mail', $company->mail) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('mail')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Téléphone de la société -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-phone text-gray-500"></i>
                        <span>Téléphone</span>
                    </label>
                    <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $company->telephone) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('telephone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Site Web de la société -->
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-globe text-gray-500"></i>
                        <span>Site Web</span>
                    </label>
                    <input type="url" id="website" name="website" value="{{ old('website', $company->website) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('website')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Numéro de TVA -->
                <div>
                    <label for="tva_number" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-file-invoice text-gray-500"></i>
                        <span>Numéro de TVA</span>
                    </label>
                    <input type="text" id="tva_number" name="tva_number" value="{{ old('tva_number', $company->tva_number) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('tva_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- SIRET -->
                <div>
                    <label for="siret" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-file-alt text-gray-500"></i>
                        <span>SIRET</span>
                    </label>
                    <input type="text" id="siret" name="siret" value="{{ old('siret', $company->siret) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('siret')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- SIREN -->
                <div>
                    <label for="siren" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-file-alt text-gray-500"></i>
                        <span>SIREN</span>
                    </label>
                    <input type="text" id="siren" name="siren" value="{{ old('siren', $company->siren) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('siren')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Adresse de la société -->
                <div class="col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 flex items-center space-x-1">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                        <span>Adresse</span>
                    </label>
                    <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Bouton de mise à jour -->
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <!-- Script pour prévisualiser l'image lors de l'upload -->
    <script>
        document.getElementById('logo-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logo-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
