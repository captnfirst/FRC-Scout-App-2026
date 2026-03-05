<style>
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white; border-radius: 12px; padding: 20px 25px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .back-btn-custom {
        background-color: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px; padding: 8px 16px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;
    }
    .back-btn-custom:hover { background-color: white; color: #1e3c72; }

    /* İttifak Kartları */
    .alliance-card { border: none; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); overflow: hidden; }
    .card-red { border-top: 6px solid #dc3545; }
    .card-blue { border-top: 6px solid #0d6efd; }

    .team-badge { font-size: 1.2rem; font-weight: 800; border-radius: 8px; padding: 10px; display: block; margin-bottom: 10px; text-align: center; }
    .team-red { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid #dc3545; }
    .team-blue { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; border: 1px solid #0d6efd; }

    /* Progress Çubukları */
    .sim-progress-bg { background-color: #e9ecef; border-radius: 10px; height: 35px; overflow: hidden; display: flex; box-shadow: inset 0 2px 5px rgba(0,0,0,0.1); }
    .sim-progress-bar { height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 900; color: white; transition: width 1s ease-in-out; }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-magic text-warning me-2"></i> AGR Strateji Masası
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-dark fs-6 shadow-sm">
                            Maç: <?= strtoupper(str_replace('qm', 'QM ', $data['match_key'])) ?>
                        </span>
                    </div>
                </div>
                <a href="#" onclick="history.back()" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline">Geri Dön</span>
                </a>
            </div>

            <?php
            // VERİLERİ HESAPLAMA BLOĞU
            $redTeams = $data['match_details']['alliances']['red']['team_keys'] ?? [];
            $blueTeams = $data['match_details']['alliances']['blue']['team_keys'] ?? [];

            $redEPA = 0; $blueEPA = 0;
            $redAGR = 0; $blueAGR = 0;
            $alerts = [];

            // KIRMIZI İTTİFAK HESAPLAMALARI
            foreach($redTeams as $tKey) {
                $redEPA += isset($data['epa_data'][$tKey]) ? $data['epa_data'][$tKey]['toplam_epa'] : 0;

                if(isset($data['scout_data'][$tKey]) && $data['scout_data'][$tKey]['matches'] > 0) {
                    $s = $data['scout_data'][$tKey];
                    $avgScout = ($s['auto_total'] + $s['teleop_total']) / $s['matches'];
                    $redAGR += $avgScout;

                    // TAKTİK UYARILAR (Yapay Zeka Mantığı)
                    if($s['last_role'] == 'calismadi') $alerts[] = "<div class='alert alert-danger shadow-sm'>🚨 <strong>KIRMIZI İTTİFAK ZAYIFLIĞI:</strong> FRC ".str_replace('frc','',$tKey)." robotunun son maçında <b>'Bozuldu/Çalışmadı'</b> raporlanmış. Hücumda eksik kalabilirler!</div>";
                    if($s['last_role'] == 'defans' && $s['last_defense'] == 'iyi') $alerts[] = "<div class='alert alert-warning shadow-sm text-dark'>🛡️ <strong>KIRMIZI DEFANS DİKKAT:</strong> FRC ".str_replace('frc','',$tKey)." son maçında çok İYİ bir defans yaptı. Skorer robotumuzu koruyun!</div>";
                }
            }

            // MAVİ İTTİFAK HESAPLAMALARI
            foreach($blueTeams as $tKey) {
                $blueEPA += isset($data['epa_data'][$tKey]) ? $data['epa_data'][$tKey]['toplam_epa'] : 0;

                if(isset($data['scout_data'][$tKey]) && $data['scout_data'][$tKey]['matches'] > 0) {
                    $s = $data['scout_data'][$tKey];
                    $avgScout = ($s['auto_total'] + $s['teleop_total']) / $s['matches'];
                    $blueAGR += $avgScout;

                    // TAKTİK UYARILAR
                    if($s['last_role'] == 'calismadi') $alerts[] = "<div class='alert alert-danger shadow-sm'>🚨 <strong>MAVİ İTTİFAK ZAYIFLIĞI:</strong> FRC ".str_replace('frc','',$tKey)." son maçında <b>'Bozuldu/Çalışmadı'</b>. Oraya baskı kurun!</div>";
                    if($s['last_role'] == 'defans' && $s['last_defense'] == 'iyi') $alerts[] = "<div class='alert alert-info shadow-sm'>🛡️ <strong>MAVİ DEFANS DİKKAT:</strong> FRC ".str_replace('frc','',$tKey)." iyi defans yapıyor. Trafiğe takılmayın.</div>";
                }
            }

            // Yüzde Hesaplamaları
            $totalEPA = $redEPA + $blueEPA;
            $redEpaPct = $totalEPA > 0 ? round(($redEPA / $totalEPA) * 100) : 50;
            $blueEpaPct = 100 - $redEpaPct;

            $totalAGR = $redAGR + $blueAGR;
            $redAgrPct = $totalAGR > 0 ? round(($redAGR / $totalAGR) * 100) : 50;
            $blueAgrPct = 100 - $redAgrPct;
            ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-4">
                        <h5 class="fw-bold text-center text-secondary mb-3"><i class="fas fa-robot text-muted"></i> STATBOTICS TAHMİNİ (Kağıt Üstü EPA)</h5>
                        <div class="sim-progress-bg mb-4">
                            <div class="sim-progress-bar bg-danger" style="width: <?= $redEpaPct ?>%"><?= $redEpaPct ?>% (<?= $redEPA ?> Puan)</div>
                            <div class="sim-progress-bar bg-primary" style="width: <?= $blueEpaPct ?>%"><?= $blueEPA ?> Puan (<?= $blueEpaPct ?>%)</div>
                        </div>

                        <h5 class="fw-bold text-center text-dark mb-3"><i class="fas fa-eye text-warning"></i> AGR SCOUT TAHMİNİ (Sahadaki Gerçeklik)</h5>
                        <div class="sim-progress-bg">
                            <div class="sim-progress-bar bg-danger" style="width: <?= $redAgrPct ?>%; box-shadow: inset 0 0 10px rgba(0,0,0,0.3);"><?= $redAgrPct ?>% (<?= round($redAGR) ?> Ort. Puan)</div>
                            <div class="sim-progress-bar bg-primary" style="width: <?= $blueAgrPct ?>%; box-shadow: inset 0 0 10px rgba(0,0,0,0.3);"><?= round($blueAGR) ?> Ort. Puan (<?= $blueAgrPct ?>%)</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card alliance-card card-red h-100 bg-light">
                        <div class="card-body">
                            <h4 class="text-center text-danger fw-bold mb-4">KIRMIZI İTTİFAK</h4>
                            <?php foreach($redTeams as $t): ?>
                                <div class="team-badge team-red">FRC <?= str_replace('frc','', $t) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card alliance-card card-blue h-100 bg-light">
                        <div class="card-body">
                            <h4 class="text-center text-primary fw-bold mb-4">MAVİ İTTİFAK</h4>
                            <?php foreach($blueTeams as $t): ?>
                                <div class="team-badge team-blue">FRC <?= str_replace('frc','', $t) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!empty($alerts)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-left: 5px solid #ffc107 !important;">
                            <div class="card-header bg-white border-0 pt-4 pb-2">
                                <h4 class="fw-bold text-dark m-0"><i class="fas fa-lightbulb text-warning me-2"></i> AGR Strateji Uyarıları</h4>
                            </div>
                            <div class="card-body">
                                <?php foreach($alerts as $alert) echo $alert; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>
</div>