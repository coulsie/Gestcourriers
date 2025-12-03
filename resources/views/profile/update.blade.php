<form method="POST" action="{{ route('profile.update', $user->id) }}">
    {{-- ... ou simplement action="{{ route('profile.update') }}" --}}
    @csrf
    @method('PATCH') {{-- Ou 'PUT' ou 'POST' --}}

    <!-- Champ Nom -->
    <div>
        <label for="name">Nom</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus>
        @error('name') <span>{{ $message }}</span> @enderror
    </div>

    <!-- Champ Email -->
    <div>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email') <span>{{ $message }}</span> @enderror
    </div>

    <!-- Bouton de soumission -->
    <button type="submit">
        Mettre à jour le profil
    </button>
</form>
