<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        body { overflow-x: hidden; }
        #wrapper { display: flex; width: 100%; min-height: 100vh; }
        #sidebar-wrapper { min-width: 250px; max-width: 250px; background: #343a40; color: white; transition: all 0.3s; }
        #page-content-wrapper { width: 100%; flex-grow: 1; background-color: #f8f9fa; }
        @media (max-width: 768px) { #sidebar-wrapper { margin-left: -250px; } #sidebar-wrapper.active { margin-left: 0; } }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar-wrapper" class="border-right">
            <div class="sidebar-heading p-4"><h5>ADMIN GESTION</h5></div>
            <div class="list-group list-group-flush">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white">Tableau de bord</a>
                <a href="{{ route('agents.index') }}" class="list-group-item list-group-item-action bg-dark text-white">Agents</a>
                <a href="{{ route('courriers.index') }}" class="list-group-item list-group-item-action bg-dark text-white">Courriers</a>
                <a href="{{ route('directions.index') }}" class="list-group-item list-group-item-action bg-dark text-white">Directions</a>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="menu-toggle">☰ Menu</button>
                    <div class="ms-auto">
                        <span class="me-3">{{ Auth::user()->name }}</span>
                        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("sidebar-wrapper").classList.toggle("active");
        });
    </script>
</body>
</html>
