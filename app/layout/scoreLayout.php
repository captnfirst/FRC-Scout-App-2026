<!DOCTYPE html>
<html lang="<?= Lang::current() ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= __('admin_portal') ?></title>
    <link rel="shortcut icon" href="/dist/img/frc_logo.svg" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
<div class="wrapper">
    <?php View::renderView("default", "header") ?>
    <?= $data['VIEW'] ?>
    <?php View::renderView("default", "footer") ?>
</div>

<script src="/plugins/jquery/jquery.min.js"></script>
<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="/dist/js/adminlte.js"></script>
<script src="/dist/js/demo.js"></script>

<script>
    const isTurkish = <?= Lang::isTr() ? 'true' : 'false' ?>;
    const currentEvent = '<?= isset($data['secilen_turnuva']) ? $data['secilen_turnuva'] : '' ?>';

    $(document).ready(function () {
        // Toggle defense section visibility
        $('.role-radio').on('change', function() {
            if ($('#rr_defans').is(':checked')) {
                $('#defense_quality_section').slideDown(200);
            } else {
                $('#defense_quality_section').slideUp(200);
            }
        });

        // Toggle breakdown warning box
        $('.breakdown-radio').on('change', function() {
            var isBroken = !$('#br_sorunsuz').is(':checked');
            if (isBroken) {
                $('#breakdown_warning').slideDown(300);
            } else {
                $('#breakdown_warning').slideUp(300);
            }
        });

        // Toggle visibility of picked teams
        $('#togglePickedBtn').on('click', function (e) {
            e.preventDefault();
            let tableElem = $('#analizTablosu');
            tableElem.toggleClass('hide-picked-teams');

            if (tableElem.hasClass('hide-picked-teams')) {
                $(this).removeClass('btn-outline-light').addClass('btn-light text-dark');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                $('#togglePickedText').text(isTurkish ? 'Tümünü Göster' : 'Show All');
            } else {
                $(this).removeClass('btn-light text-dark').addClass('btn-outline-light');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                $('#togglePickedText').text(isTurkish ? 'Seçilenleri Gizle' : 'Hide Selected');
            }
        });

        if ($('#analizTablosu').length > 0) {
            var table = $('#analizTablosu').DataTable({
                "responsive": true,
                "paging": false,
                "info": false,
                "order": [[9, "desc"]],
                "language": {
                    "search": "<i class='fas fa-search text-muted'></i> " + (isTurkish ? "Robot Ara:" : "Search Robot:"),
                    "zeroRecords": isTurkish ? "Eşleşen robot bulunamadı" : "No matching robots found",
                    "infoEmpty": isTurkish ? "Kayıt yok" : "No records available"
                },
                "dom": '<"p-3 d-flex justify-content-between"f>rt<"p-3"i>'
            });

            function markAsPicked(btnElem, trElem) {
                $(trElem).addClass('picked-row');
                $(btnElem).removeClass('btn-outline-dark').addClass('btn-dark text-warning').html('<i class="fas fa-ban"></i> ' + (isTurkish ? 'SEÇİLDİ' : 'PICKED'));
            }

            function unmarkPicked(btnElem, trElem) {
                $(trElem).removeClass('picked-row');
                $(btnElem).removeClass('btn-dark text-warning').addClass('btn-outline-dark').html(isTurkish ? 'Seçim Yap' : 'Select');
            }

            function updateDynamicBadges() {
                let allRows = $(table.rows().nodes());
                allRows.find('.recommendation-cell').html('<span class="text-muted">-</span>');

                let eligibleRows = allRows.filter(function () {
                    let isPicked = $(this).hasClass('picked-row');
                    let gozlem = parseInt($(this).data('gozlem')) || 0;
                    return !isPicked && gozlem > 0;
                });

                eligibleRows.sort(function (a, b) {
                    return parseInt($(a).data('agrrank')) - parseInt($(b).data('agrrank'));
                });

                $(eligibleRows).each(function (index) {
                    let cell = $(this).find('.recommendation-cell');
                    if (index === 0) {
                        cell.html('<span class="badge bg-success first-pick-badge px-3 py-2 text-uppercase fw-bold"><i class="fas fa-crown me-1"></i> 1st Pick</span>');
                    } else if (index > 0 && index <= 7) {
                        cell.html('<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-star me-1"></i> ' + (isTurkish ? 'İttifak' : 'Alliance') + '</span>');
                    }
                });
            }

            function applySavedPicks() {
                $(table.rows().nodes()).each(function () {
                    if (this.id && this.id.startsWith('row_')) {
                        let tKey = this.id.replace('row_', '');
                        let btnElem = $(this).find('.btn-pick');

                        if (localStorage.getItem('picked_' + currentEvent + '_' + tKey) === 'true') {
                            markAsPicked(btnElem, this);
                        } else {
                            unmarkPicked(btnElem, this);
                        }
                    }
                });
                updateDynamicBadges();
            }

            $('#analizTablosu tbody').on('click', '.btn-pick', function () {
                let tKey = $(this).data('tkey');
                let trElem = $(this).closest('tr');
                let btnElem = $(this);

                let isPicked = localStorage.getItem('picked_' + currentEvent + '_' + tKey) === 'true';

                if (isPicked) {
                    localStorage.setItem('picked_' + currentEvent + '_' + tKey, 'false');
                    unmarkPicked(btnElem, trElem);
                } else {
                    localStorage.setItem('picked_' + currentEvent + '_' + tKey, 'true');
                    markAsPicked(btnElem, trElem);
                }

                updateDynamicBadges();
            });

            table.on('draw.dt', function () {
                applySavedPicks();
            });

            applySavedPicks();
        }
    });

    function checkSwerve() {
        var drivetrainElem = document.getElementById('dt_swerve');
        if (!drivetrainElem) return;

        var isSwerve = drivetrainElem.checked;
        var swerveDiv = document.getElementById('swerve_options');
        var swerveInputs = document.querySelectorAll('.swerve-req');

        if (isSwerve) {
            swerveDiv.style.display = 'block';
            swerveInputs.forEach(function (input) {
                input.setAttribute('required', 'required');
            });
        } else {
            swerveDiv.style.display = 'none';
            swerveInputs.forEach(function (input) {
                input.removeAttribute('required');
                input.checked = false;
            });
        }
    }

    function updateFuel(period, amount) {
        let inputEl = document.getElementById(period + '_fuel_input');
        if (!inputEl) return;

        let displayEl = document.getElementById(period + '_fuel_display');
        let currentFuel = parseInt(inputEl.value);
        currentFuel += amount;

        if (currentFuel < 0) currentFuel = 0;

        inputEl.value = currentFuel;
        displayEl.innerText = currentFuel;

        displayEl.style.transform = 'scale(1.1)';
        setTimeout(() => {
            displayEl.style.transform = 'scale(1)';
        }, 150);
    }

    function goToTeleop() {
        const canvas = document.getElementById('pathCanvas');
        if (canvas) document.getElementById('auto_path_data').value = canvas.toDataURL("image/png");

        document.getElementById('section-auto').classList.remove('active');
        document.getElementById('section-teleop').classList.add('active');

        document.getElementById('step-1').classList.remove('active-auto');
        document.getElementById('step-2').classList.add('active-teleop');

        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function goToAuto() {
        document.getElementById('section-teleop').classList.remove('active');
        document.getElementById('section-auto').classList.add('active');

        document.getElementById('step-2').classList.remove('active-teleop');
        document.getElementById('step-1').classList.add('active-auto');

        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function checkDefense() {
        var roleElem = document.getElementById('robot_role');
        if (!roleElem) return;

        var role = roleElem.value;
        var defSection = document.getElementById('defense_quality_section');
        if (role === 'defans') {
            defSection.style.display = 'block';
        } else {
            defSection.style.display = 'none';
        }
    }

    const canvas = document.getElementById('pathCanvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        ctx.strokeStyle = '#e74c3c';
        ctx.lineWidth = 6;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        function getMousePos(canvas, evt) {
            var rect = canvas.getBoundingClientRect();
            var clientX = evt.clientX;
            var clientY = evt.clientY;

            if (evt.touches && evt.touches.length > 0) {
                clientX = evt.touches[0].clientX;
                clientY = evt.touches[0].clientY;
            }

            var scaleX = canvas.width / rect.width;
            var scaleY = canvas.height / rect.height;

            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function startDrawing(e) {
            isDrawing = true;
            const pos = getMousePos(canvas, e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            if (e.cancelable) e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getMousePos(canvas, e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            if (e.cancelable) e.preventDefault();
        }

        function stopDrawing() {
            isDrawing = false;
            ctx.closePath();
        }

        window.clearCanvas = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing, {passive: false});
        canvas.addEventListener('touchmove', draw, {passive: false});
        canvas.addEventListener('touchend', stopDrawing, {passive: false});
    }

    function loadVideo(videoKey) {
        document.getElementById('youtubePlayer').src = "https://www.youtube.com/embed/" + videoKey + "?autoplay=1";
    }

    function stopVideo() {
        document.getElementById('youtubePlayer').src = "";
    }

    $('#videoModal').on('hidden.bs.modal', function () {
        stopVideo();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.weight-calc');
        const totalValue = document.getElementById('totalValue');
        const totalContainer = document.getElementById('totalScoreContainer');
        const totalMessage = document.getElementById('totalMessage');

        if (totalValue && inputs.length > 0) {
            function calculateTotal() {
                let sum = 0;
                inputs.forEach(input => {
                    sum += parseFloat(input.value) || 0;
                });

                totalValue.textContent = sum;

                if (sum === 100) {
                    totalContainer.className = 'total-score-badge bg-success text-white shadow';
                    totalMessage.className = 'mt-3 fw-bold text-success';
                    totalMessage.innerHTML = '<i class="fas fa-check-circle"></i> ' + (isTurkish ? 'Mükemmel Oran (%100)' : 'Ideal Distribution (100%)');
                } else {
                    totalContainer.className = 'total-score-badge bg-warning text-dark shadow';
                    totalMessage.className = 'mt-3 fw-bold text-warning text-dark';
                    totalMessage.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + (isTurkish ? 'Toplam ağırlık %100 değil' : 'Total weight does not equal 100%');
                }
            }

            inputs.forEach(input => {
                input.addEventListener('input', calculateTotal);
            });

            calculateTotal();
        }
    });

    $('#savePracticeMatch').click(function() {
        var matchNo = $('#pm_number').val();
        if (matchNo === "" || matchNo < 1) {
            alert(isTurkish ? "Lütfen geçerli bir maç numarası girin!" : "Please enter a valid match number!");
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> ' + (isTurkish ? "Hazırlanıyor..." : "Preparing..."));

        const currentEvent = '<?= isset($data['secilen_turnuva']) ? $data['secilen_turnuva'] : (isset($data['event_key']) ? $data['event_key'] : '') ?>';
        const currentTeam = '<?= isset($data['secilen_takim']) ? $data['secilen_takim'] : (isset($data['team_key']) ? $data['team_key'] : '') ?>';

        $.post('/default/addPracticeMatch', {
            tournament_id: currentEvent,
            team_id: currentTeam,
            match_no: matchNo
        }, function(res) {
            if(res.status == "success") {
                window.location.href = "/default/scout/" + res.match_key + "/" + res.team_key + "/" + res.event_key;
            } else {
                alert(isTurkish ? "Maç oluşturulamadı!" : "Failed to create match!");
                $btn.prop('disabled', false).text(isTurkish ? 'Maç Oluştur & Gözleme Başla' : 'Create & Scout Match');
            }
        }, 'json');
    });
</script>
</body>
</html>