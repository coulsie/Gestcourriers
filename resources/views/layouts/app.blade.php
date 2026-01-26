<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>e-COURRIER - 2026</title>

    <!-- Fonts & Icons -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <div class="sidebar-brand-text mx-3">e-COURRIER</div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de Bord</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- SECTION COURRIERS -->
            <div class="sidebar-heading">Opérations</div>

            <li class="nav-item {{ request()->routeIs('courriers.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCourriers" aria-expanded="true">
                    <i class="fas fa-fw fa-envelope"></i>
                    <span>Gestion Courriers</span>
                </a>
                <div id="collapseCourriers" class="collapse {{ request()->routeIs('courriers.*') ? 'show' : '' }}" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('courriers.index') }}">Enrégistrement courriers</a>
                        <a class="collapse-item" href="{{ route('courriers.create') }}">créer un courriers</a>
                        <a class="collapse-item" href="{{ route('courriers.RechercheAffichage') }}">Recherche avancée</a>
                        <a class="collapse-item" href="{{ route('courriers.archives') }}">Archives</a>
                        <a class="collapse-item" href="{{ route('imputations.mes_imputations') }}">Mes Imputations</a>
                        <a class="collapse-item" href="{{ route('imputations.index') }}">Toutes les Imputations</a>
                        <a class="collapse-item" href="{{ route('statistiques.index') }}">Statistiques</a>
                        <a class="collapse-item" href="{{ route('statistiques.dashboard') }}">dashboard imputations</a>

                    </div>
                </div>
            </li>

            <!-- NOUVELLE SECTION : GESTION DES PRÉSENCES -->
            <li class="nav-item {{ request()->routeIs('presences.*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePresences" aria-expanded="true">
                    <i class="fas fa-fw fa-user-check"></i>
                    <span>Gestion Présences</span>
                </a>
                <div id="collapsePresences" class="collapse {{ request()->routeIs('presences.*') ? 'show' : '' }}" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pointage :</h6>
                        <a class="collapse-item" href="{{ route('presences.index') }}">Enrégistrement pointage</a>
                        <a class="collapse-item" href="{{ route('presences.monPointage') }}">Marquer Présence </a>
                        <a class="collapse-item" href="{{ route('presences.monHistorique') }}">Mon Historique </a>
                        <a class="collapse-item" href="{{ route('presences.listeFiltree') }}">Liste de présence </a>
                        <a class="collapse-item" href="{{ route('presences.validation-hebdo') }}">Validation hebdomadaire</a>
                        <a class="collapse-item" href="{{ route('rapports.presences.periodique') }}">Rapport Périodique</a>
                        <a class="collapse-item" href="{{ route('absences.index') }}">Congés et Permissions</a>
                        <a class="collapse-item" href="{{ route('typeabsences.index') }}">Autorisation d'Absence</a>
                        <!-- <a class="collapse-item" href="{{ route('notifications.index') }}">Notifications de tâches</a> -->
                        <a class="collapse-item" href="{{ route('presences.etat') }}">État des Présences</a>

                        <div class="dropdown-divider"></div>
                        <h6 class="collapse-header">Rapports :</h6>
                        <a class="collapse-item" href="#">Rapport Mensuel</a>
                    </div>
                </div>
            </li>

            <!-- SECTION ANNONCES -->
            <li class="nav-item {{ request()->routeIs('annonces.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('annonces.index') }}">
                    <i class="fas fa-fw fa-bullhorn"></i>
                    <span>Annonces</span>
                </a>
            </li>

            <!-- SECTION ADMINISTRATION -->
            @can('manage-users')
            <hr class="sidebar-divider">
            <div class="sidebar-heading text-warning">Administration Système</div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="true">
                    <i class="fas fa-fw fa-lock text-warning"></i>
                    <span class="text-warning">Contrôle & RH</span>
                </a>
                <div id="collapseAdmin" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded border-left-warning shadow">
                        <h6 class="collapse-header">Utilisateurs & RH:</h6>
                        <a class="collapse-item font-weight-bold" href="{{ route('users.index') }}">Liste Utilisateurs</a>
                        <a class="collapse-item" href="{{ route('agents.nouveau') }}">Nouveau Compte</a>
                        <a class="collapse-item" href="{{ route('agents.index') }}">Ressources Humaines</a>
                         <a class="collapse-item" href="{{ route('agents.par.service') }}">Agents par Service</a>
                        <a class="collapse-item" href="{{ route('agents.par.service.recherche') }}">Recherche Agents</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="collapse-header">Suivi & Stats:</h6>

                        <a class="collapse-item" href="{{ route('statistiques.dashboard') }}">Dashboard Stats</a>
                        <a class="collapse-item" href="{{ route('typeabsences.index') }}">Paramétrage Absence</a>
                    </div>
                </div>
            </li>
            @endcan

            <hr class="sidebar-divider">

            <!-- PROFIL -->
            <li class="nav-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profile.show') }}">
                    <i class="fas fa-fw fa-user-circle"></i>
                    <span>Mon Profil</span>
                </a>
                <a class="collapse-item" href="{{ route('password.request') }}">{{ __('Mot de passe oublié ?') }}</a>

            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.topbar')

                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
