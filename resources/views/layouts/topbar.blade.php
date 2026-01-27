<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Bouton pour masquer la sidebar sur mobile -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Titre Stylisé -->
    <div class="d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100">
        <h5 class="mb-0 fw-bold" style="letter-spacing: 1px;">
            <span style="color: #4e73df;">Système de Gestion</span>
            <span class="badge badge-primary px-3 py-2" style="background: linear-gradient(45deg, #4e73df, #224abe); border-radius: 8px shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                Direction de la  Stratégie, des Etudes et Statistiques Fiscales
            </span>
        </h5>
    </div>

    <!-- Barre de Recherche Globale (Courriers, RH, Présences) -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-md-5 my-2 my-md-0 mw-100 navbar-search" action="{{ route('home') }}" method="GET">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-light border-0 small"
                   placeholder="Rechercher (Courrier, Agent, Présence...)"
                   aria-label="Search" aria-describedby="basic-addon2"
                   style="width: 350px; border-radius: 20px 0 0 20px !important;">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit" style="border-radius: 0 20px 20px 0 !important;">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto">
        <!-- Icone Recherche pour Mobile uniquement -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Rechercher..." aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Informations Utilisateur -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-flex flex-column align-items-end mr-3">
                    <span class="text-gray-800 small font-weight-bold">{{ Auth::user()->name }}</span>
                    <span class="text-gray-500 x-small" style="font-size: 10px;">Connecté</span>
                </div>
                <img class="img-profile rounded-circle border shadow-sm"
                     src="https://ui-avatars.com{{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff&bold=true">
            </a>

            <!-- Menu déroulant Profil -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.show') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Mon Profil
                </a>
                <a class="dropdown-item" href="{{ route('presences.index') }}">
                    <i class="fas fa-calendar-check fa-sm fa-fw mr-2 text-gray-400"></i>
                    Mes Présences
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Déconnexion
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>

</nav>
<!-- End of Topbar -->
