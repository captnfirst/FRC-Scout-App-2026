<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
        <!-- Language Switcher Dropdown -->
        <li class="nav-item dropdown mr-2">
            <a class="nav-link dropdown-toggle btn btn-sm btn-light border px-2 py-1 font-weight-bold text-dark d-inline-flex align-items-center" 
               href="#" id="langDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 8px;">
                <i class="fas fa-globe text-primary mr-1"></i> <?= Lang::isTr() ? '🇹🇷 Türkçe' : '🇬🇧 English' ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" aria-labelledby="langDropdown" style="border-radius: 10px; min-width: 140px;">
                <a class="dropdown-item d-flex align-items-center justify-content-between <?= Lang::isTr() ? 'active font-weight-bold' : '' ?>" href="/default/set_language/tr">
                    <span>🇹🇷 Türkçe</span>
                    <?php if (Lang::isTr()): ?><i class="fas fa-check small ml-2"></i><?php endif; ?>
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between <?= Lang::isEn() ? 'active font-weight-bold' : '' ?>" href="/default/set_language/en">
                    <span>🇬🇧 English</span>
                    <?php if (Lang::isEn()): ?><i class="fas fa-check small ml-2"></i><?php endif; ?>
                </a>
            </div>
        </li>

        <?php if (isset($_SESSION['admin'])): ?>
            <li class="nav-item mr-1">
                <span class="badge badge-primary px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    <i class="fas fa-robot mr-1"></i> <?= htmlspecialchars(strtoupper($_SESSION['admin']['team_number'] ?? 'FRC6459')) ?>
                </span>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/default/index" class="brand-link border-bottom border-secondary d-flex align-items-center">
        <img src="/dist/img/frc_logo.svg" alt="FRC Logo" class="brand-image elevation-2" style="width: 33px; height: 33px; object-fit: contain;">
        <span class="brand-text font-weight-bold text-uppercase ml-2" style="letter-spacing: 1px;"><?= __('app_name') ?></span>
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
                    <a href="/default/index" class="d-block text-white fw-bold mb-1"><?= htmlspecialchars($_SESSION['admin']['name']) ?></a>
                    <span class="badge bg-success" style="font-size: 0.65rem;"><i class="fas fa-circle text-white" style="font-size: 0.4rem; vertical-align: middle; margin-right: 3px;"></i> <?= __('nav_online') ?></span>
                </div>
            <?php endif; ?>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header text-uppercase text-secondary fw-bold mt-1" style="font-size: 0.75rem; letter-spacing: 1px;"><?= __('nav_field_scouting') ?></li>

                <li class="nav-item">
                    <a href="/default/tournaments" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list text-info"></i>
                        <p><?= __('nav_match_scout') ?></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/default/pit_tournaments" class="nav-link">
                        <i class="nav-icon fas fa-tools text-warning"></i>
                        <p><?= __('nav_pit_scout') ?></p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-secondary fw-bold mt-2" style="font-size: 0.75rem; letter-spacing: 1px;"><?= __('nav_strategy_analytics') ?></li>

                <li class="nav-item">
                    <a href="/default/analysis_tournaments_list" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie text-success"></i>
                        <p><?= __('nav_tournament_analytics') ?></p>
                    </a>
                </li>

                <?php if (isset($_SESSION['admin']) && $_SESSION['admin']['administrator'] == 1): ?>
                    <?php 
                        $dm = new defaultModel();
                        $pendingList = $dm->getPendingTransferRequestsModel($_SESSION['admin']['team_number'] ?? '');
                        $pCount = count($pendingList);
                    ?>
                    <li class="nav-header text-uppercase text-secondary fw-bold mt-2" style="font-size: 0.75rem; letter-spacing: 1px;"><?= __('nav_management_settings') ?></li>

                    <li class="nav-item">
                        <a href="/default/settings" class="nav-link">
                            <i class="nav-icon fas fa-sliders-h text-primary"></i>
                            <p><?= __('nav_team_settings') ?></p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/default/score_weights" class="nav-link">
                            <i class="nav-icon fas fa-balance-scale text-warning"></i>
                            <p><?= __('nav_algorithm_weights') ?></p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/default/members" class="nav-link d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="nav-icon fas fa-users-cog text-light"></i>
                                <p class="m-0"><?= __('nav_user_management') ?></p>
                            </div>
                            <?php if ($pCount > 0): ?>
                                <span class="badge badge-warning font-weight-bold ml-auto"><?= $pCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-header text-uppercase text-secondary fw-bold mt-2" style="font-size: 0.75rem; letter-spacing: 1px;"><?= __('nav_account') ?></li>

                <li class="nav-item">
                    <a href="/default/profile" class="nav-link">
                        <i class="nav-icon fas fa-user-circle text-info"></i>
                        <p><?= __('nav_profile') ?></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/default/logout" class="nav-link">
                        <i class="nav-icon fas fa-power-off text-danger"></i>
                        <p><?= __('nav_logout') ?></p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>