<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
</nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/default/index" class="brand-link border-bottom border-secondary">
        <img src="/dist/img/AdminLTELogo.png" alt="AGR" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-bold text-uppercase" style="letter-spacing: 1px;">AGR SKORLAMA</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-secondary">
            <div class="image">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center elevation-2" style="width: 35px; height: 35px;">
                    <i class="fas fa-user-astronaut text-white"></i>
                </div>
            </div>
            <?php if (isset($_SESSION['admin'])): ?>
                <div class="info lh-1">
                    <a href="/default/index" class="d-block text-white fw-bold mb-1"><?=$_SESSION['admin']['name'] ?></a>
                    <span class="badge bg-success" style="font-size: 0.65rem;"><i class="fas fa-circle text-white" style="font-size: 0.4rem; vertical-align: middle; margin-right: 3px;"></i> Çevrimiçi</span>
                </div>
            <?php endif; ?>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header text-uppercase text-secondary fw-bold mt-1" style="font-size: 0.75rem; letter-spacing: 1px;">Sahadan Veri Girişi</li>

                <li class="nav-item">
                    <a href="/default/tournaments" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list text-info"></i>
                        <p>Maç Gözlemi (Scout)</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/default/pit_tournaments" class="nav-link">
                        <i class="nav-icon fas fa-tools text-warning"></i>
                        <p>Pit Scout Ekleme</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-secondary fw-bold mt-2" style="font-size: 0.75rem; letter-spacing: 1px;">İstihbarat Masası</li>

                <li class="nav-item">
                    <a href="/default/analysis_tournaments_list" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie text-success"></i>
                        <p>Turnuva Analizi</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-secondary fw-bold mt-2" style="font-size: 0.75rem; letter-spacing: 1px;">Sistem</li>

                <li class="nav-item">
                    <a href="/default/logout" class="nav-link">
                        <i class="nav-icon fas fa-power-off text-danger"></i>
                        <p>Güvenli Çıkış</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>