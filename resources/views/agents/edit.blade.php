@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Modifier les informations de l'agent : {{ $agent->first_name }} {{ $agent->last_name }}</div>

                <div class="card-body">
                    <!-- Le formulaire utilise la route 'agents.update' et la méthode PUT -->
                    <form action="{{ route('agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Section 1: Informations Générales --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="first_name">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $agent->first_name) }}" required>
                                @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="last_name">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $agent->last_name) }}" required>
                                @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="matricule">Matricule <span class="text-danger">*</span></label>
                                <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule', $agent->matricule) }}" required>
                                @error('matricule') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Section 2: Contact et Statut --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="email">E-mail Personnel</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $agent->email) }}">
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email_professionnel">E-mail Professionnel</label>
                                <input type="email" name="email_professionnel" id="email_professionnel" class="form-control @error('email_professionnel') is-invalid @enderror" value="{{ old('email_professionnel', $agent->email_professionnel) }}">
                                @error('email_professionnel') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Statut <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    {{-- Assurez-vous que ces options correspondent à votre ENUM dans la DB --}}
                                    <option value="Agent" {{ old('status', $agent->status) == 'Agent' ? 'selected' : '' }}>Agent</option>
                                    <option value="Chef de service" {{ old('status', $agent->status) == 'Chef de service' ? 'selected' : '' }}>Chef de service</option>
                                    <option value="Sous-directeur" {{ old('status', $agent->status) == 'Sous-directeur' ? 'selected' : '' }}>Sous-directeur</option>
                                    <option value="Directeur" {{ old('status', $agent->status) == 'Directeur' ? 'selected' : '' }}>Directeur</option>
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Section 3: Physique et Service --}}
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="sexe">Sexe</label>
                                <select name="sexe" id="sexe" class="form-control @error('sexe') is-invalid @enderror">
                                    <option value="">Choisir...</option>
                                    <option value="Male" {{ old('sexe', $agent->sexe) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sexe', $agent->sexe) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('sexe') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                             <div class="form-group col-md-3">
                                <label for="date_of_birth">Date de Naissance</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', optional($agent->date_of_birth)->format('Y-m-d')) }}">
                                @error('date_of_birth') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Place of birth">Lieu de Naissance</label>
                                <input type="text" name="Place of birth" id="Place of birth" class="form-control @error('Place of birth') is-invalid @enderror" value="{{ old('Place of birth', $agent->{'Place of birth'}) }}">
                                @error('Place of birth') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                             <div class="form-group col-md-3">
                                <label for="service_id">Service <span class="text-danger">*</span></label>
                                <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un service</option>
                                    {{-- Supposons que vous passez une variable $services depuis le contrôleur --}}
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id', $agent->service_id) == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} {{ $service->code }} {{ $service->description }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Section 4: Emploi et Contact d'Urgence --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="Emploi">Emploi</label>
                                <input type="text" name="Emploi" id="Emploi" class="form-control @error('Emploi') is-invalid @enderror" value="{{ old('Emploi', $agent->Emploi) }}">
                                @error('Emploi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Grade">Grade</label>
                                <input type="text" name="Grade" id="Grade" class="form-control @error('Grade') is-invalid @enderror" value="{{ old('Grade', $agent->Grade) }}">
                                @error('Grade') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Date_Prise_de_service">Date Prise de Service</label>
                                <input type="date" name="Date_Prise_de_service" id="Date_Prise_de_service" class="form-control @error('Date_Prise_de_service') is-invalid @enderror" value="{{ old('Date_Prise_de_service', optional($agent->Date_Prise_de_service)->format('Y-m-d')) }}">
                                @error('Date_Prise_de_service') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-row">
                             <div class="form-group col-md-6">
                                <label for="Personne_a_prevenir">Personne à prévenir (Urgence)</label>
                                <input type="text" name="Personne_a_prevenir" id="Personne_a_prevenir" class="form-control @error('Personne_a_prevenir') is-invalid @enderror" value="{{ old('Personne_a_prevenir', $agent->Personne_a_prevenir) }}">
                                @error('Personne_a_prevenir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Contact_personne_a_prevenir">Contact Personne à prévenir</label>
                                <input type="text" name="Contact_personne_a_prevenir" id="Contact_personne_a_prevenir" class="form-control @error('Contact_personne_a_prevenir') is-invalid @enderror" value="{{ old('Contact_personne_a_prevenir', $agent->Contact_personne_a_prevenir) }}">
                                @error('Contact_personne_a_prevenir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Section 5: Photo et Autres --}}
                        <div class="form-group">
                            <label for="photo">Photo de profil</label>
                            <input type="file" name="photo" id="photo" class="form-control-file @error('photo') is-invalid @enderror">
                            @error('photo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            @if($agent->photo)
                                <p class="mt-2">Photo actuelle: <a href="{{ asset($agent->photo) }}" target="_blank">Voir l'image</a></p>
                            @endif
                        </div>

                        {{-- Champs cachés si nécessaire, par exemple pour user_id si lié à un compte utilisateur --}}
                        {{-- <input type="hidden" name="user_id" value="{{ old('user_id', $agent->user_id) }}"> --}}

                        <button type="submit" class="btn btn-success">Mettre à jour l'agent</button>
                        <a href="{{ route('agents.index') }}" class="btn btn-danger">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
