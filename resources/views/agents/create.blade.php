@extends('layouts.app') {{-- Assurez-vous que 'layouts.app' existe --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Créer un Nouvel Agent</div>

                <div class="card-body">
                    {{-- Le formulaire doit supporter les fichiers (enctype) pour le champ 'Photo' --}}
                    <form method="POST" action="{{ route('agents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- Matricule --}}
                            <div class="form-group col-md-4 mb-3">
                                <label for="matricule">Matricule</label>
                                <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}" required>
                                @error('matricule')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Nom de famille (last_name) --}}
                            <div class="form-group col-md-4 mb-3">
                                <label for="last_name">Nom de famille</label>
                                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Prénom (first_name) --}}
                            <div class="form-group col-md-4 mb-3">
                                <label for="first_name">Prénom(s)</label>
                                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        <div class="row">
                             {{-- Sexe --}}
                            <div class="form-group col-md-3 mb-3">
                                <label for="sexe">Sexe</label>
                                <select name="sexe" id="sexe" class="form-control @error('sexe') is-invalid @enderror" required>
                                    <option value="">Choisir</option>
                                    <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('sexe')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Date de naissance (date_of_birth) --}}
                            <div class="form-group col-md-4 mb-3">
                                <label for="date_of_birth">Date de naissance</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Lieu de naissance (Place of birth) --}}
                            <div class="form-group col-md-5 mb-3">
                                <label for="Place_of_birth">Lieu de naissance</label>
                                <input type="text" name="Place_of_birth" id="Place_of_birth" class="form-control @error('Place_of_birth') is-invalid @enderror" value="{{ old('Place_of_birth') }}">
                                @error('Place_of_birth')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Email --}}
                            <div class="form-group col-md-6 mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Téléphone (phone_number) --}}
                            <div class="form-group col-md-6 mb-3">
                                <label for="phone_number">Téléphone</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}">
                                @error('phone_number')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                         {{-- Adresse (address) --}}
                        <div class="form-group mb-3">
                            <label for="address">Adresse</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>

                        <div class="row">
                            {{-- Emploi --}}
                            <div class="form-group col-md-4 mb-3">
                                <label for="Emploi">Emploi</label>
                                <input type="text" name="Emploi" id="Emploi" class="form-control @error('Emploi') is-invalid @enderror" value="{{ old('Emploi') }}">
                                @error('Emploi')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Grade --}}
                            <div class="form-group col-md-4 mb-3">
                                <label for="Grade">Grade</label>
                                <input type="text" name="Grade" id="Grade" class="form-control @error('Grade') is-invalid @enderror" value="{{ old('Grade') }}">
                                @error('Grade')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Date Prise de Service --}}
                            <div class="form-group col-md-4 mb-3">
                                <label for="Date_Prise_de_service">Date Prise de Service</label>
                                <input type="date" name="Date_Prise_de_service" id="Date_Prise_de_service" class="form-control @error('Date_Prise_de_service') is-invalid @enderror" value="{{ old('Date_Prise_de_service') }}">
                                @error('Date_Prise_de_service')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Personne a prevenir --}}
                            <div class="form-group col-md-6 mb-3">
                                <label for="Personne_a_prevenir">Personne à prévenir (Nom)</label>
                                <input type="text" name="Personne_a_prevenir" id="Personne_a_prevenir" class="form-control @error('Personne_a_prevenir') is-invalid @enderror" value="{{ old('Personne_a_prevenir') }}">
                                @error('Personne_a_prevenir')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Contact personne a prevenir --}}
                            <div class="form-group col-md-6 mb-3">
                                <label for="Contact_personne_a_prevenir">Contact Personne à prévenir</label>
                                <input type="text" name="Contact_personne_a_prevenir" id="Contact_personne_a_prevenir" class="form-control @error('Contact_personne_a_prevenir') is-invalid @enderror" value="{{ old('Contact_personne_a_prevenir') }}">
                                @error('Contact_personne_a_prevenir')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>


                        <div class="row">
                            {{-- Service de rattachement (service_id) --}}
                            {{-- NOTE: Assurez-vous de passer une variable $services depuis votre contrôleur --}}
                            <div class="form-group col-md-6 mb-3">
                                <label for="service_id">Service de rattachement</label>
                                <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                                    <option value="">-- Choisir un service --</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} ({{ $service->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>

                            {{-- Statut (status) --}}
                            <div class="form-group col-md-3 mb-3">
                                <label for="statut">Statut</label>
                                <select name="statut" id="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                    <option value="">Choisir</option>
                                    <option value="M" {{ old('statut') == 'Agent' ? 'selected' : '' }}>Agent</option>
                                    <option value="F" {{ old('statut') == 'Chef' ? 'selected' : '' }}>Chef de service</option>
                                    <option value="F" {{ old('statut') == 'S/D' ? 'selected' : '' }}>Sous-directeur</option>
                                    <option value="F" {{ old('statut') == 'Directeur' ? 'selected' : '' }}>Directeur</option>
                                    <option value="F" {{ old('statut') == 'CT' ? 'selected' : '' }}>Conseiller Technique</option>
                                    <option value="F" {{ old('statut') == 'CS' ? 'selected' : '' }}>Conseiller Spécial</option>
                                </select>
                                @error('statut')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        {{-- Photo --}}
                        <div class="form-group mb-4">
                            <label for="Photo">Photo de profil</label>
                            <input type="file" name="Photo" id="Photo" class="form-control-file @error('Photo') is-invalid @enderror">
                            @error('Photo')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                Enregistrer l'Agent
                            </button>
                            <a href="{{ route('agents.index') }}" class="btn btn-danger">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
