<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAHA</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <a href="{{ route('read') }}" class="sidebar-link">
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
                            <a href="{{ route('proposalkegiatan') }}" class="sidebar-link">Kegiatan</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('proposaleksidentil') }}" class="sidebar-link">Insedentil</a>
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
                            <a href="{{ route('laporankegiatan') }}" class="sidebar-link">Kegiatan</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('laporantahunan') }}" class="sidebar-link">Tahunan</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('tambahUser') }}" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Tambah User</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('monitoring') }}" class="sidebar-link">
                        <i class="lni lni-search-alt"></i>
                        <span>Monitoring</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('struktur') }}" class="sidebar-link">
                        <i class="lni lni-folder"></i>
                        <span>Struktur</span>
                    </a>
                </li>
                
                <li class="sidebar-item">
                    <a href="{{ route('find.similar.proposals') }}" class="sidebar-link">
                        <i class="lni lni-bar-chart"></i>
                        <span>Analisa</span>
                    </a>
                </li>
               

                 <li class="sidebar-item">
                    <a href="{{ route('inputdata') }}" class="sidebar-link">
                        <i class="lni lni-star-half"></i>
                        <span>Peringkat</span>
                    </a>
                </li>
            </ul>
        </aside>
        <div class="main">
            <nav class="navbar bg-body-tertiary">
                <div class="container-fluid">
                    <div class="navbar-brand">
                        @if (Auth::check())
                            Welcome <b>{{ Auth::user()->username }}</b><br>
                            <div class="realtime-clock"> <!-- Container untuk waktu -->
                                <i class="lni lni-alarm-clock" style="vertical-align: middle; margin-right: 5px;"></i>
                                <span style="vertical-align: middle;">
                                    <a id="realtime-clock" class="navbar-text"></a>
                                </span>
                            </div>
                        @else
                            <script>
                                window.location = "{{ route('login') }}"; // Redirect to login if not authenticated
                            </script>
                        @endif
                    </div>
                    <form class="d-flex" role="search">
                        <div class="dropdown">
                            <button class="btn btn-outline-dark" type="button" id="dropdownMenuButton"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="lni lni-user"></i> User
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('password') }}">Ganti Password</a>
                                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                <div class="dropdown-divider"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </nav>
            <div>
                @yield('content')
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script src="script.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
