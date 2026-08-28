<style>
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
        opacity: 0.55 !important;
        filter: grayscale(80%);
    }

    .picked-row td {
        text-decoration: line-through;
    }

    .picked-row td:last-child,
    .picked-row td:first-child {
        text-decoration: none !important;
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
                        <i class="fas fa-chart-line text-info me-2"></i> <?= __('tournament_analytics_title') ?>
                    </h2>
                    <div class="mt-2">
                        <span class="badge bg-white text-info fs-6 shadow-sm d-inline-block">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= strtoupper($data['secilen_turnuva']) ?>
                        </span>
                    </div>
                </div>

                <a href="/default/analysis_tournaments_list" class="back-btn-custom flex-shrink-0">
                    <i class="fas fa-arrow-left me-md-2"></i> <span class="d-none d-md-inline"><?= __('back') ?></span>
                </a>
            </div>

            <div class="card shadow border-0 rounded-lg">
                <div class="card-header bg-dark text-white border-0 d-flex justify-content-between align-items-center p-3">
                    <h4 class="card-title fw-bold m-0 fs-5"><i class="fas fa-robot text-warning me-2"></i> <?= __('alliance_intelligence') ?></h4>
                    <div class="d-flex align-items-center">
                        <div class="btn-group me-3" role="group" aria-label="Role Filters" id="roleFilters">
                            <button type="button" class="btn btn-sm btn-light fw-bold" data-filter=""><?= __('filter_all') ?></button>
                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold" data-filter="defans"><i class="fas fa-shield-alt"></i> <?= __('filter_defense') ?></button>
                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold" data-filter="lojistik"><i class="fas fa-box"></i> <?= __('filter_feeder') ?></button>
                            <button type="button" class="btn btn-sm btn-outline-warning fw-bold text-dark" data-filter="dengeli"><i class="fas fa-balance-scale"></i> <?= __('filter_balanced') ?></button>
                        </div>
                        <button id="togglePickedBtn" class="btn btn-sm btn-outline-light me-3 fw-bold shadow-sm rounded-pill">
                            <i class="fas fa-eye-slash"></i> <span id="togglePickedText"><?= __('hide_selected') ?></span>
                        </button>
                        <small class="text-warning fw-bold d-none d-md-inline border border-warning px-2 py-1 rounded">
                            <i class="fas fa-satellite-dish blink_me"></i> <?= __('live_tba_data') ?>
                        </small>
                    </div>
                </div>

                <div class="card-body p-0 table-responsive">
                    <table id="analizTablosu" class="table table-striped table-hover w-100 text-center align-middle m-0">
                        <thead class="bg-light text-secondary">
                        <tr>
                            <th class="text-dark bg-warning" title="<?= __('rank') ?>">🏆 <?= __('rank') ?></th>
                            <th><?= __('team') ?></th>
                            <th><?= __('name') ?></th>
                            <th class="text-primary" title="<?= __('total_epa') ?>"><?= __('total_epa') ?></th>
                            <th class="text-info" title="<?= __('auto_avg') ?>"><?= __('auto_avg') ?></th>
                            <th class="text-success" title="<?= __('teleop_avg') ?>"><?= __('teleop_avg') ?></th>
                            <th class="text-secondary" title="<?= __('defense_score') ?>"><?= __('defense_score') ?></th>
                            <th class="text-secondary" title="<?= __('feeding_score') ?>"><?= __('feeding_score') ?></th>
                            <th title="<?= __('first_pick_score') ?>"><?= __('first_pick_score') ?></th>
                            <th class="text-warning" title="<?= __('second_pick_score') ?>"><?= __('second_pick_score') ?></th>
                            <th><?= __('role_suggestion') ?></th>
                            <th class="bg-dark text-white"><?= __('status') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($data['takimlar'])):
                            $teamScores = [];

                            $wEpa = isset($data['weights']['epa']) ? $data['weights']['epa'] : 30;
                            $wAuto = isset($data['weights']['auto']) ? $data['weights']['auto'] : 20;
                            $wTeleop = isset($data['weights']['teleop']) ? $data['weights']['teleop'] : 40;
                            $wClimb = isset($data['weights']['climb']) ? $data['weights']['climb'] : 10;

                            $maxEpa = 250;
                            $maxAutoFuel = 100;
                            $maxTeleopFuel = 200;

                            foreach($data['takimlar'] as $team) {
                                $tKey = $team['key'];
                                $tNo = $team['team_number'];

                                $liveRank = isset($data['live_rankings'][$tNo]) ? $data['live_rankings'][$tNo]['rank'] : '-';

                                $epa = isset($data['epa_data'][$tKey]) ? $data['epa_data'][$tKey]['toplam_epa'] : 0;
                                $gozlem = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['match_count'] : 0;
                                $avgAuto = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['avg_auto_fuel'] : 0;
                                $avgTeleop = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['avg_teleop_fuel'] : 0;
                                $climbCount = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['total_teleop_climb'] : 0;

                                $defensePlayed = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['defense_played_count'] : 0;
                                $goodDefense = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['good_defense_count'] : 0;
                                $medDefense = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['medium_defense_count'] : 0;
                                
                                $goodFeed = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['good_feed_count'] : 0;
                                $medFeed = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['medium_feed_count'] : 0;
                                $goodDamper = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['good_damper_count'] : 0;
                                $medDamper = isset($data['scout_stats'][$tKey]) ? $data['scout_stats'][$tKey]['medium_damper_count'] : 0;

                                $climbRate = ($gozlem > 0) ? ($climbCount / $gozlem) : 0;

                                $defenseScore = 0;
                                if ($gozlem > 0 && $defensePlayed > 0) {
                                    $earnedDefPoints = ($goodDefense * 4) + ($medDefense * 2);
                                    $maxPossibleDefPoints = $gozlem * 4;
                                    $defenseScore = round(($earnedDefPoints / $maxPossibleDefPoints) * 100);
                                }

                                $logisticsScore = 0;
                                if ($gozlem > 0) {
                                    $earnedLogPoints = ($goodFeed * 3) + ($medFeed * 1) + ($goodDamper * 3) + ($medDamper * 1);
                                    $maxPossibleLogPoints = $gozlem * 6;
                                    $logisticsScore = round(($earnedLogPoints / $maxPossibleLogPoints) * 100);
                                }

                                $epaPuan = min(($epa / $maxEpa) * $wEpa, $wEpa);
                                $autoPuan = min(($avgAuto / $maxAutoFuel) * $wAuto, $wAuto);
                                $teleopPuan = min(($avgTeleop / $maxTeleopFuel) * $wTeleop, $wTeleop);
                                $climbPuan = $climbRate * $wClimb;

                                $firstPickScore = round($epaPuan + $autoPuan + $teleopPuan + $climbPuan, 1);
                                if ($gozlem == 0) $firstPickScore = 0;

                                $drivetrain = isset($data['pit_data'][$tKey]) ? strtolower($data['pit_data'][$tKey]['drivetrain_type']) : null;

                                $secondPickScore = round(($defenseScore * 0.40) + ($logisticsScore * 0.40) + (($autoPuan / $wAuto) * 100 * 0.20), 1);
                                if ($gozlem == 0) {
                                    $secondPickScore = 0;
                                } else {
                                    if ($drivetrain === 'swerve') {
                                        $secondPickScore += 10;
                                    }
                                    if ($secondPickScore > 100) $secondPickScore = 100;
                                }

                                $roles = [];
                                $roleClasses = [];
                                if ($defenseScore >= 50 && $defensePlayed >= 2) {
                                    $roles[] = Lang::isTr() ? '🛡️ Elit Defans' : '🛡️ Elite Defense';
                                    $roleClasses[] = 'defans';
                                }
                                if ($logisticsScore >= 50) {
                                    $roles[] = Lang::isTr() ? '📦 Usta Besleyici' : '📦 Master Feeder';
                                    $roleClasses[] = 'lojistik';
                                }
                                if ($defenseScore >= 40 && $logisticsScore >= 40) {
                                    $roles[] = Lang::isTr() ? '⚖️ Dengeli Joker' : '⚖️ Balanced Joker';
                                    $roleClasses[] = 'dengeli';
                                }

                                $teamScores[] = [
                                        'team' => $team,
                                        'score' => $firstPickScore,
                                        'secondPickScore' => $secondPickScore,
                                        'epa' => $epa,
                                        'auto' => $avgAuto,
                                        'teleop' => $avgTeleop,
                                        'climbRate' => round($climbRate * 100),
                                        'defenseScore' => $defenseScore,
                                        'logisticsScore' => $logisticsScore,
                                        'defensePlayed' => $defensePlayed,
                                        'drivetrain' => $drivetrain,
                                        'gozlem' => $gozlem,
                                        'liveRank' => $liveRank,
                                        'roles' => $roles,
                                        'roleClasses' => implode(' ', $roleClasses)
                                ];
                            }

                            usort($teamScores, function($a, $b) {
                                return $b['score'] <=> $a['score'];
                            });

                            $rank = 1;
                            foreach($teamScores as $ts):

                                $logColor = ($ts['logisticsScore'] >= 70) ? 'bg-primary' : (($ts['logisticsScore'] >= 40) ? 'bg-info text-dark' : 'bg-secondary');

                                if ($ts['defensePlayed'] == 0) {
                                    $defColor = 'bg-secondary opacity-50';
                                } else {
                                    $defColor = ($ts['defenseScore'] >= 60) ? 'bg-danger' : (($ts['defenseScore'] >= 30) ? 'bg-warning text-dark' : 'bg-secondary opacity-75');
                                }

                                ?>
                                <tr id="row_<?= $ts['team']['key'] ?>" data-agrrank="<?= $rank ?>" data-gozlem="<?= $ts['gozlem'] ?>" data-roleclass="<?= $ts['roleClasses'] ?>">
                                    <td data-order="<?= $ts['liveRank'] === '-' ? 999 : $ts['liveRank'] ?>">
                                        <?php if ($ts['liveRank'] !== '-'): ?>
                                            <span class="official-rank">#<?= $ts['liveRank'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fs-5">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td data-order="<?= htmlspecialchars(str_replace('frc', '', $ts['team']['key'] ?? '')) ?>" class="fw-bold fs-5">
                                        <a href="/default/team_analysis/<?= $ts['team']['key'] ?>/<?= $data['secilen_turnuva'] ?>" class="text-decoration-none text-primary" title="Open Dossier">
                                            FRC <?= $ts['team']['team_number'] ?> <i class="fas fa-external-link-alt ms-1 text-info opacity-75" style="font-size: 0.85rem;"></i>
                                        </a>
                                    </td>
                                    <td class="text-start text-muted text-truncate" style="max-width: 150px; font-weight: 500;">
                                        <?= htmlspecialchars($ts['team']['nickname']) ?>
                                        <?php if ($ts['drivetrain'] === 'swerve'): ?>
                                            <span class="badge bg-dark ms-1 shadow-sm" title="Swerve Drive" style="font-size: 0.65rem;">Swerve</span>
                                        <?php elseif ($ts['drivetrain'] === 'tank'): ?>
                                            <span class="badge bg-secondary ms-1 shadow-sm" title="Tank Drive" style="font-size: 0.65rem;">Tank</span>
                                        <?php elseif ($ts['drivetrain']): ?>
                                            <span class="badge bg-light text-dark ms-1 shadow-sm border" title="<?= ucfirst($ts['drivetrain']) ?>" style="font-size: 0.65rem;"><?= ucfirst($ts['drivetrain']) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td><span class="badge bg-primary stat-badge opacity-75"><?= $ts['epa'] ?></span></td>
                                    <td><span class="badge bg-info text-dark stat-badge border border-info"><?= $ts['auto'] ?></span></td>
                                    <td><span class="badge bg-success stat-badge opacity-75"><?= $ts['teleop'] ?></span></td>
                                    
                                    <td data-order="<?= $ts['defenseScore'] ?>">
                                        <span class="badge stat-badge <?= $defColor ?>">%<?= $ts['defenseScore'] ?></span>
                                    </td>
                                    <td data-order="<?= $ts['logisticsScore'] ?>">
                                        <span class="badge stat-badge <?= $logColor ?>">%<?= $ts['logisticsScore'] ?></span>
                                    </td>

                                    <td data-order="<?= $ts['score'] ?>"><span class="badge agr-score"><?= number_format($ts['score'], 1) ?></span></td>
                                    <td data-order="<?= $ts['secondPickScore'] ?>"><span class="badge bg-warning text-dark stat-badge border border-warning fs-6" style="box-shadow: 0 2px 5px rgba(255,193,7,0.5);"><?= number_format($ts['secondPickScore'], 1) ?></span></td>

                                    <td class="recommendation-cell">
                                        <?php if (!empty($ts['roles'])): ?>
                                            <div class="d-flex flex-column gap-1 align-items-center">
                                                <?php foreach($ts['roles'] as $r): 
                                                    $rColor = (strpos($r, 'Defense') !== false || strpos($r, 'Defans') !== false) ? 'bg-danger' : ((strpos($r, 'Feeder') !== false || strpos($r, 'Besleyici') !== false) ? 'bg-primary' : 'bg-warning text-dark');
                                                ?>
                                                    <span class="badge <?= $rColor ?> shadow-sm" style="font-size: 0.75rem;"><?= $r ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size:0.8rem;">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($ts['liveRank'] !== '-' && $ts['liveRank'] <= 8): ?>
                                            <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-bold btn-pick border-2 shadow-sm" data-tkey="<?= $ts['team']['key'] ?>">
                                                <i class="fas fa-crown text-warning" style="text-shadow: 0 0 2px rgba(0,0,0,0.5);"></i> <?= __('captain') ?> #<?= $ts['liveRank'] ?>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-dark fw-bold btn-pick" data-tkey="<?= $ts['team']['key'] ?>">
                                                <?= __('select') ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var table = $('#analizTablosu').DataTable();

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var activeFilter = $('#roleFilters button.active').data('filter');
                if (!activeFilter) return true;

                var rowNode = table.row(dataIndex).node();
                var roleClass = $(rowNode).data('roleclass') || '';

                return roleClass.indexOf(activeFilter) !== -1;
            }
        );

        $('#roleFilters button').on('click', function() {
            var clicked = $(this);
            $('#roleFilters button').removeClass('active btn-danger btn-primary btn-warning btn-dark text-white');
            $('#roleFilters button[data-filter=""]').addClass('btn-light text-dark');
            $('#roleFilters button[data-filter="defans"]').addClass('btn-outline-danger');
            $('#roleFilters button[data-filter="lojistik"]').addClass('btn-outline-primary');
            $('#roleFilters button[data-filter="dengeli"]').addClass('btn-outline-warning text-dark');

            clicked.addClass('active').removeClass('btn-outline-danger btn-outline-primary btn-outline-warning btn-light');

            if (clicked.data('filter') === '') clicked.addClass('btn-dark text-white');
            if (clicked.data('filter') === 'defans') clicked.removeClass('btn-outline-danger').addClass('btn-danger text-white');
            if (clicked.data('filter') === 'lojistik') clicked.removeClass('btn-outline-primary').addClass('btn-primary text-white');
            if (clicked.data('filter') === 'dengeli') clicked.removeClass('btn-outline-warning').addClass('btn-warning text-dark');

            if (clicked.data('filter') !== '') {
                table.order([9, 'desc']).draw();
            } else {
                table.order([8, 'desc']).draw();
            }
        });
    }, 500);
});
</script>
