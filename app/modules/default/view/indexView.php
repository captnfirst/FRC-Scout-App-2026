<style>
    /* Karşılama Afişi Stili */
    .welcome-hero {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 15px;
        padding: 2.5rem 2rem;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    /* Afişin arka planındaki dev transparan ikon */
    .welcome-hero .bg-icon {
        position: absolute;
        right: -20px;
        bottom: -30px;
        font-size: 10rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    /* Kutu Zıplama Efektleri */
    .hover-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-radius: 12px;
    }
    .hover-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }

    /* Kutu İçi Düzenlemeler */
    .small-box .inner {
        padding: 25px 20px;
    }
    .small-box h3 {
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 5px;
    }
    .small-box p {
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.9;
    }
    .small-box .icon {
        color: rgba(0,0,0,0.15);
        z-index: 0;
    }
    .small-box-footer {
        padding: 10px 0;
        font-weight: bold;
        letter-spacing: 0.5px;
        background: rgba(0,0,0,0.1) !important;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="welcome-hero mb-4">
                <i class="fas fa-satellite-dish bg-icon"></i>
                <div class="position-relative z-index-1">
                    <h1 class="fw-bold mb-2">🚀 AGR Komuta Merkezi</h1>
                    <p class="mb-0 fs-5 text-white-50">
                        Hoş geldin, <strong><?= isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : 'Kaptan' ?>!</strong>
                        FRC İstihbarat ve Skorlama platformu operasyona hazır.
                    </p>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-lg-6 col-xl-3 mb-4">
                    <div class="small-box bg-info hover-card h-100 shadow-sm d-flex flex-column">
                        <div class="inner flex-grow-1">
                            <h3>SCOUT</h3>
                            <p>Maç Skorlama Modülü</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <a href="/default/tournaments" class="small-box-footer mt-auto">Sahaya Git <i class="fas fa-arrow-circle-right ms-1"></i></a>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-3 mb-4">
                    <div class="small-box bg-success hover-card h-100 shadow-sm d-flex flex-column">
                        <div class="inner flex-grow-1">
                            <h3>ANALİZ</h3>
                            <p>Turnuva & İttifak İstihbaratı</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="/default/analysis_tournaments_list" class="small-box-footer mt-auto">Raporları Gör <i class="fas fa-arrow-circle-right ms-1"></i></a>
                    </div>
                </div>

                <?php if(isset($_SESSION['admin']) && $_SESSION['admin']['administrator'] == 1): ?>

                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="small-box bg-warning hover-card h-100 shadow-sm d-flex flex-column">
                            <div class="inner flex-grow-1">
                                <h3 class="text-dark">AĞIRLIK</h3>
                                <p class="text-dark">Algoritma & Puanlama Ayarı</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-sliders-h text-dark"></i>
                            </div>
                            <a href="/default/score_weights" class="small-box-footer mt-auto text-dark" style="background: rgba(0,0,0,0.05) !important;">
                                Ayarları Yap <i class="fas fa-arrow-circle-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="small-box bg-danger hover-card h-100 shadow-sm d-flex flex-column">
                            <div class="inner flex-grow-1">
                                <h3>YETKİ</h3>
                                <p>Admin & Scout Yönetimi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <a href="/default/adminekle" class="small-box-footer mt-auto">Kullanıcı Ekle <i class="fas fa-arrow-circle-right ms-1"></i></a>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div></section>
</div>