<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAHA</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">SIMAHA</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="{{ route('dataHima') }}" class="sidebar-link">
                        <i class="lni lni-layers"></i>
                        <span>Data HIMA Dan UKM </span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link has-dropdown collapsed " data-bs-toggle="collapse"
                        data-bs-target="#proposal" aria-expanded="false" aria-controls="proposal">
                        <i class="lni lni-notepad"></i>
                        <span>Proposal</span>
                    </a>
                    <ul id="proposal" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar-item">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Kegiatan</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Insedentil</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link has-dropdown collapsed " data-bs-toggle="collapse"
                        data-bs-target="#laporan" aria-expanded="false" aria-controls="laporan">
                        <i class="lni lni-notepad"></i>
                        <span>Laporan</span>
                    </a>
                    <ul id="laporan" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Kegiatan</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Tahunan</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-user"></i>  
                        <span>Tambah User</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-search-alt"></i>   
                        <span>Monitoring</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-folder"></i>
                        <span>Struktur</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="http://localhost:8501" class="sidebar-link">
                        <i class="lni lni-bar-chart"></i>
                        <span>Analisa</span>
                    </a>
                </li>
            </ul>
        </aside>
        <div class="main">
<nav class="navbar bg-body-tertiary">
   <div class="container-fluid">
    <a class="navbar-brand">Wellcome <b>{{ Auth::user()->username }}</b></a>
    <form class="d-flex" role="search">
      <button class="btn btn-outline-dark" type="submit"><i class="lni lni-user"></i> User</button>
    </form>
  </div>
</nav>
<div >
    @yield('content')
        </div>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
