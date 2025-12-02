{{-- CODE CORRIGÉ DANS affectations.index.blade.php --}}

@extends('layouts.app')

@section('content')
          {{ __('Liste des affectations') }}
                    <a href="{{ route('affectations.create') }}" class="btn btn-primary float-end">
                        {{ __('Nouvelle affectations') }}
                    </a>
    <table class="table">
        <thead>
            <tr>
                <th>ID Affectation</th>
                <th>Sujet du Courrier</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {{-- Supposons que $affectations est passé à cette vue --}}
            @foreach($affectations as $affectation)
                <tr>
                    <td>{{ $affectation->id }}</td>
                    {{-- Assurez-vous que votre modèle Affectation a une relation 'courrier' --}}
                    <td>{{ $affectation->courrier->sujet }}</td>
                    <td>
                        {{-- CORRECTION APPLIQUÉE ICI --}}
                        {{-- On passe l'objet $affectation->courrier à la route nommée --}}
                        <a href="{{ route('courriers.show', ['courrier' => $affectation->courrier->id]) }}" class="btn btn-info">
                            Voir le Courrier
                        </a>

                        {{-- Syntaxe Laravel plus courte et équivalente : --}}
                        {{-- <a href="{{ route('courriers.show', $affectation->courrier) }}" class="btn btn-info">Voir le Courrier</a> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
