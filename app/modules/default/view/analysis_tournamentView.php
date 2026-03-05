<style>
    /* --- YENİ EKLENEN APP BAR (KOMUTA MERKEZİ) STİLLERİ --- */
    .deep-dive-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .back-btn-custom {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 600;
        transition: all 0.2s ease;
        backdrop-filter: blur(5px);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .back-btn-custom:hover {
        background-color: white;
        color: #1e3c72;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* --- MEVCUT TABLO VE ROZET STİLLERİ --- */
    .stat-badge { font-size: 1rem; font-weight: 700; padding: 5px 10px; border-radius: 6px; }

    .table-hover tbody tr:hover {
        background-color: #f4f6f9; transform: scale(1.01); transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 10; position: relative;
    }

    .agr-score {
        font-size: 1.3rem; font-weight: 900; color: #fff;
        background: linear-gradient(45deg, #FF416C, #FF4B2B); border: none;
        padding: 8px 15px; box-shadow: 0 4px 15px rgba(255, 75, 43, 0.4);
    }

    .official-rank {
        font-size: 1.2rem; font-weight: 900; color: #2c3e50;
        background: #f1c40f; padding: 5px 12px; border-radius: 50%;
        box-shadow: 0 2px 5px rgba(241, 196, 15, 0.4); display: inline-block;
    }

    .first-pick-badge { animation: pulse 1.5s infinite; font-size: 0.9rem; }

    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    .picked-row {
        background-color: #d6d8db !important;
        opacity: 0.6;
        filter: grayscale(100%);
        transition: all 0.3s ease;
    }
    .picked-row td {
        color: #495057 !important;
        text-decoration: line-through;
    }
    .btn-pick {
        transition: all 0.2s;
        border-radius: 8px;
    }

    table.hide-picked-teams tr.picked-row {
        display: none !important;
    }

    #togglePickedBtn { transition: all 0.3s ease; }
</style>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid mb-5">

            <div class="deep-dive-header mb-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column pe-3">
                    <h2 class="m-0 fw-bold fs-4 fs-md-3 text-truncate">
                        <i class="fas fa-chart-line text-info me-2"></i> Turnuva Analizi
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-info fs-6 shadow-sm d-inline-block">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= strtoupper($data['secilen_turnuva']) ?>
                        </span>
                    </div>
                </div>

                <a href="/default/analysis_tournaments_list" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline">Turnuva Seçimi</span>
                </a>
            </div>

            <div class="card shadow border-0 rounded-lg">
                <div class="card-header bg-dark text-white border-0 d-flex justify-content-between align-items-center p-3">
                    <h4 class="card-title fw-bold m-0 fs-5"><i class="fas fa-robot text-warning me-2"></i> İttifak İstihbaratı</h4>
                    <div>
                        <button id="togglePickedBtn" class="btn btn-sm btn-outline-light me-3 fw-bold shadow-sm rounded-pill">
                            <i class="fas fa-eye-slash"></i> <span id="togglePickedText">Seçilenleri Gizle</span>
                        </button>
                        <small class="text-warning fw-bold d-none d-md-inline border border-warning px-2 py-1 rounded">
                            <i class="fas fa-satellite-dish blink_me"></i> Canlı TBA Verisi
                        </small>
                    </div>
                </div>

                <div class="card-body p-0 table-responsive">
                    <table id="analizTablosu" class="table table-striped table-hover w-100 text-center align-middle m-0">
                        <thead class="bg-light text-secondary">
                        <tr>
                            <th class="text-dark bg-warning" title="Şu Anki Resmi Turnuva Sıralaması">🏆 RANK</th>
                            <th>Takım</th>
                            <th>İsim</th>
                            <th class="text-primary" title="Statbotics Genel EPA">Genel EPA</th>
                            <th class="text-info" title="Otonom Yakıt Ortalaması">Auto Ort.</th>
                            <th class="text-success" title="Teleop Yakıt Ortalaması">Teleop Ort.</th>
                            <th class="text-danger" title="Tırmanma Başarısı Oranı">Tırmanma %</th>
                            <th title="100 Üzerinden FRC 6459 Algoritma Puanı (AGR Score)">🔥 AGR SCORE</th>
                            <th>Öneri</th>
                            <th class="bg-dark text-white">Durum</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($data['takimlar'])):
                            $teamScores = [];

                            // 1. ADIM: Admin panelinden gelen dinamik ağırlık çarpanlarını (Weights) alıyoruz
                            $wEpa = isset($data['weights']['epa']) ? $data['weights']['epa'] : 30;
                            $wAuto = isset($data['weights']['auto']) ? $data['weights']['auto'] : 20;
                            $wTeleop = isset($data['weights']['teleop']) ? $data['weights']['teleop'] : 40;
                            $wClimb = isset($data['weights']['climb']) ? $data['weights']['climb'] : 10;

                            // 2. ADIM: İdeal Maksimum Kapasiteler (Algoritmanın oran yapabilmesi için)
                            // Bu değerler sistemin 100 üzerinden puanlama yapabilmesi için takımların ulaşabileceği tavan hedeflerdir.
                            $maxEpa = 250;
                            $maxAutoFuel = 100;
                            $maxTeleopFuel = 200;

                            foreach($data['takimlar'] as $team) {
                                $tKey = $team['key'];
                                $tNo = $team['team_number'];

                                // Canlı Sıralama Verileri
                                $liveRank = isset($data['live_rankings'][$tNo]) ? $data['live_rankings'][$tNo]['rank'] : '-';

                                // EPA ve Kendi Scout Verilerimiz
                                $epa = isset($data['epa_data'][$tKey]) ? $data['epa_data'][$tKey]['toplam_epa'] : 0;
                                $gozlem = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['match_count'] : 0;
                                $avgAuto = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['avg_auto_fuel'] : 0;
                                $avgTeleop = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['avg_teleop_fuel'] : 0;
                                $climbCount = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['total_teleop_climb'] : 0;

                                $climbRate = ($gozlem > 0) ? ($climbCount / $gozlem) : 0;

                                // --- YENİ DİNAMİK AGR POWER SCORE ALGORİTMASI ---
                                // Formül: min( (Takımın Değeri / Beklenen Maksimum) * Admin Ağırlığı, Admin Ağırlığı )
                                $epaPuan = min(($epa / $maxEpa) * $wEpa, $wEpa);
                                $autoPuan = min(($avgAuto / $maxAutoFuel) * $wAuto, $wAuto);
                                $teleopPuan = min(($avgTeleop / $maxTeleopFuel) * $wTeleop, $wTeleop);
                                $climbPuan = $climbRate * $wClimb; // Tırmanma zaten % (0 ile 1) arası olduğu için direkt çarpılır

                                $totalScore = round($epaPuan + $autoPuan + $teleopPuan + $climbPuan, 1);

                                // Gözlem (Scout verisi) olmayan takımları direkt listenin en altına (0 puan) atıyoruz.
                                if ($gozlem == 0) $totalScore = 0;

                                $teamScores[] = [
                                        'team' => $team,
                                        'score' => $totalScore,
                                        'epa' => $epa,
                                        'auto' => $avgAuto,
                                        'teleop' => $avgTeleop,
                                        'climbRate' => round($climbRate * 100),
                                        'gozlem' => $gozlem,
                                        'liveRank' => $liveRank,
                                ];
                            }

                            // AGR Score'a göre büyükten küçüğe sırala
                            usort($teamScores, function($a, $b) {
                                return $b['score'] <=> $a['score'];
                            });

                            $rank = 1;
                            foreach($teamScores as $ts):
                                ?>
                                <tr id="row_<?= $ts['team']['key'] ?>" data-agrrank="<?= $rank ?>" data-gozlem="<?= $ts['gozlem'] ?>">
                                    <td data-order="<?= $ts['liveRank'] === '-' ? 999 : $ts['liveRank'] ?>">
                                        <?php if ($ts['liveRank'] !== '-'): ?>
                                            <span class="official-rank">#<?= $ts['liveRank'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fs-5">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="fw-bold fs-5">
                                        <a href="/default/team_analysis/<?= $ts['team']['key'] ?>/<?= $data['secilen_turnuva'] ?>" class="text-decoration-none text-primary" title="Takım Derin Analizine Git">
                                            FRC <?= $ts['team']['team_number'] ?> <i class="fas fa-external-link-alt ms-1 text-info opacity-75" style="font-size: 0.85rem;"></i>
                                        </a>
                                    </td>
                                    <td class="text-start text-muted text-truncate" style="max-width: 150px; font-weight: 500;">
                                        <?= htmlspecialchars($ts['team']['nickname']) ?>
                                    </td>

                                    <td><span class="badge bg-primary stat-badge opacity-75"><?= $ts['epa'] ?></span></td>
                                    <td><span class="badge bg-info text-dark stat-badge border border-info"><?= $ts['auto'] ?></span></td>
                                    <td><span class="badge bg-success stat-badge opacity-75"><?= $ts['teleop'] ?></span></td>
                                    <td><span class="badge bg-danger stat-badge opacity-75">%<?= $ts['climbRate'] ?></span></td>

                                    <td><span class="badge agr-score"><?= number_format($ts['score'], 1) ?></span></td>

                                    <td class="recommendation-cell">
                                        <span class="text-muted">-</span>
                                    </td>

                                    <td>
                                        <?php if ($ts['liveRank'] !== '-' && $ts['liveRank'] <= 8): ?>
                                            <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-bold btn-pick border-2 shadow-sm" data-tkey="<?= $ts['team']['key'] ?>">
                                                <i class="fas fa-crown text-warning" style="text-shadow: 0 0 2px rgba(0,0,0,0.5);"></i> <?= $ts['liveRank'] ?>. KAPTAN
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-dark fw-bold btn-pick" data-tkey="<?= $ts['team']['key'] ?>">
                                                Seçim Yap
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                                $rank++;
                            endforeach;
                        endif;
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>