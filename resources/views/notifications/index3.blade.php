@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion de mes Notifications</h1>
        <a href="{{ route('notifications.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            + Nouvelle Notification
        </a>
    </div>

    <!-- Filtres rapides -->
    <div class="bg-white p-4 rounded-t-xl border-x border-t border-gray-200 flex gap-4 text-sm">
        <span class="font-semibold text-gray-600">Filtres :</span>
        <a href="#" class="text-blue-600 hover:underline">Toutes</a>
        <a href="#" class="text-gray-500 hover:underline">Non lues</a>
        <a href="#" class="text-gray-500 hover:underline">Urgentes</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-gray-50">
                <thead class="table-light">
                     <thead class="table-dark">
                <tr>
                    <th >Agent</th>
                    <th >Notification</th>
                    <th >Priorité</th>
                    <th >Statut</th>
                    <th >Échéance</th>
                    <th >Reponses</th>
                     <th>Progression</th>
                    <th >Actions</th>
                </tr>
                </thead>
                </thead>

            </thead>
            <tbody >
                @foreach($notifications as $notif)
                <tr class="hover:bg-gray-50 transition">
                    <!-- Infos Agent -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                @if($notif->agent->photo)
                                    <img class="h-10 w-10 rounded-full object-cover border" src="{{ asset('storage/' . $notif->agent->photo) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-bold">
                                        {{ substr($notif->agent->first_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $notif->agent->first_name }} {{ $notif->agent->last_name }}</div>
                                <div class="text-xs text-gray-500">{{ $notif->agent->matricule }} | {{ $notif->agent->status }}</div>
                            </div>
                        </div>
                    </td>

                    <!-- Infos Notification -->
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $notif->titre }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($notif->description, 50) }}</div>
                    </td>

                    <!-- Badge Priorité -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $prioriteColors = [
                                'Faible' => 'bg-gray-100 text-gray-800',
                                'Moyenne' => 'bg-blue-100 text-blue-800',
                                'Élevée' => 'bg-orange-100 text-orange-800',
                                'Urgent' => 'bg-red-100 text-red-800 animate-pulse',
                            ];
                        @endphp
                        @foreach($notifications as $notif)
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $prioriteColors[$notif->priorite->value] ?? 'bg-gray-100' }}">
                                {{ $notif->priorite }}
                            </span>
                        @endforeach
                    </td>

                    <!-- Badge Statut -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $statutColors = [
                                'Non lu' => 'text-gray-400',
                                'En cours' => 'text-yellow-600',
                                'Complétée' => 'text-green-600',
                                'Annulée' => 'text-red-500',
                            ];
                        @endphp
                                <span class="flex items-center font-medium {{ $statutColors[$notif->statut->value] ?? 'text-gray-500' }}">
                                    <span class="h-2 w-2 rounded-full bg-current mr-2"></span>
                                    {{ $notif->statut->value ?? $notif->statut }}
                                </span>
                    </td>

                    <!-- Date Échéance -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($notif->date_echeance)
                            <span class="{{ \Carbon\Carbon::parse($notif->date_echeance)->isPast() ? 'text-red-600 font-bold' : '' }}">
                                {{ \Carbon\Carbon::parse($notif->date_echeance)->format('d/m/Y H:i') }}
                            </span>
                        @else
                            -
                        @endif
                    </td>

                    <!-- Nombre de Réponses -->
                    <td class="badge bg-info text-dark">
                        {{ $notif->reponses->count() }}
                    </td>

                    <td>
                        @php
                            $percent = $notif->progression ?? 0;
                        @endphp

                        <div class="mb-2">
                            <strong>{{ $notif->titre }}</strong> ({{ $percent }}%)
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $percent > 80 ? 'bg-danger' : ($percent > 50 ? 'bg-warning' : 'bg-success') }}"
                                    role="progressbar"
                                    style="width: {{ $percent }}%;"
                                    aria-valuenow="{{ $percent }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">
                                Échéance le : {{ $notif->date_echeance ? \Carbon\Carbon::parse($notif->date_echeance)->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                    </td>


                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <!-- Le conteneur flex garantit l'alignement horizontal -->
                            <div class="flex justify-end items-center space-x-3">

                                <!-- Bouton Voir -->
                                <a href="{{ route('notifications.show', $notif->id_notification) }}" class="inline-flex text-blue-600 hover:text-blue-900" title="Voir">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <!-- Bouton Document (si présent) -->
                                @if($notif->document)
                                    <a href="{{ asset('storage/' . $notif->document) }}" target="_blank" class="inline-flex text-gray-600 hover:text-gray-900" title="Document">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                @endif

                                <!-- Bouton Créer/Répondre -->
                            <a href="{{ route('reponsesNotifications.create', $notif->id_notification) }}" class="inline-flex text-green-600 hover:text-green-900 my-new-class" title="Répondre">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M204.2 18.4c12 5 19.8 16.6 19.8 29.6l0 80 112 0c97.2 0 176 78.8 176 176 0 113.3-81.5 163.9-100.2 174.1-2.5 1.4-5.3 1.9-8.1 1.9-10.9 0-19.7-8.9-19.7-19.7 0-7.5 4.3-14.4 9.8-19.5 9.4-8.8 22.2-26.4 22.2-56.7 0-53-43-96-96-96l-96 0 0 80c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-160-160c-12.5-12.5-12.5-32.8 0-45.3l160-160c9.2-9.2 22.9-11.9 34.9-6.9z"/></svg>
                                </a>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
