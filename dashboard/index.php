<?php 
    require_once __DIR__ . "/../config/app.php"; 
    require_once __DIR__ . "/../config/auth.php"; 

    require_once __DIR__ . "/../vendor/autoload.php";

    use App\Models\Movement;

    $type = isset($_GET["type"]) && in_array($_GET["type"], ["E", "O", "A"], true) ? $_GET["type"] : null;
    $start = isset($_GET["start"]) && strtotime($_GET["start"]) ? date("Y-m-d", strtotime($_GET["start"])) : null;
    $end = isset($_GET["end"]) && strtotime($_GET["end"]) ? date("Y-m-d", strtotime($_GET["end"])) : null;

    $totals = Movement::getTotals($_SESSION["auth"], $type, $start, $end);
    $monthly = Movement::getMonthly($_SESSION["auth"], $type, $start, $end);
    $top_products = Movement::getTopProducts($_SESSION["auth"], $type, $start, $end, 5);

    $month_names = [1 => "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];

    $chart_months = [];
    $chart_data = [];

    foreach ($monthly as $row) {
        $m = $row["month"];

        if (!isset($chart_data[$m])) {
            $chart_data[$m] = ["E" => 0, "O" => 0, "A" => 0];
            $chart_months[] = $m;
        }

        $chart_data[$m][$row["type"]] = (float) $row["total"];
    }

    $chart_labels = array_map(function ($m) use ($month_names) {
        return $month_names[(int) substr($m, 5, 2)] . " " . substr($m, 0, 4);
    }, $chart_months);

    $chart_entrada = array_map(fn ($m) => $chart_data[$m]["E"], $chart_months);
    $chart_saida = array_map(fn ($m) => $chart_data[$m]["O"], $chart_months);

    $pie_labels = array_column($top_products, "description");
    $pie_values = array_map(fn ($p) => (float) $p["total"], $top_products);

    $total_entrada = (float) $totals["entrada"];
    $total_saida = (float) $totals["saida"];

    function fmt_qty($value)
    {
        return number_format((float) $value, 2, ",", ".");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= APP_NAME ?> - Dashboard</title>

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../resources/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php 
            $active = "dashboard";
            require __DIR__ . "/../resources/components/navbar.php";  
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php 
                    require __DIR__ . "/../resources/components/header.php";
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Filter Row -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-filter"></i> Filters
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="./index.php" class="form-row align-items-end">
                                <div class="form-group col-md-3 mb-0">
                                    <label for="start">Start date</label>
                                    <input type="date" class="form-control" name="start" id="start" value="<?= htmlspecialchars($start ?? "") ?>">
                                </div>

                                <div class="form-group col-md-3 mb-0">
                                    <label for="end">End date</label>
                                    <input type="date" class="form-control" name="end" id="end" value="<?= htmlspecialchars($end ?? "") ?>">
                                </div>

                                <div class="form-group col-md-3 mb-0">
                                    <label for="type">Movement type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">All</option>
                                        <option <?= $type === "E" ? "selected" : "" ?> value="E">Entry</option>
                                        <option <?= $type === "O" ? "selected" : "" ?> value="O">Outflow</option>
                                        <option <?= $type === "A" ? "selected" : "" ?> value="A">Adjust</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3 mb-0 d-flex">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    <a href="./index.php" class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> Clean
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Total Entrada Card -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Entrys</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= fmt_qty($total_entrada) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Saída Card -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Outflows</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= fmt_qty($total_saida) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Products Movementation</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Top Movemented Products</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small" id="pieLegend">
                                        <?php foreach ($top_products as $i => $product): ?>
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-<?= ["primary", "success", "info", "warning", "danger"][$i % 5] ?>"></i>
                                                <?= htmlspecialchars($product["description"]) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require __DIR__ . "/../resources/components/footer.php" ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Font awesome --->
    <script src="https://kit.fontawesome.com/9b074a5685.js" crossorigin="anonymous"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/components/jquery/jquery.min.js"></script>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../resources/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/nnnick/chartjs/dist/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        var areaLabels = <?= json_encode($chart_labels) ?>;
        var areaEntrada = <?= json_encode($chart_entrada) ?>;
        var areaSaida = <?= json_encode($chart_saida) ?>;
        var areaType = <?= json_encode($type) ?>;

        var areaDatasets = [];

        if (areaType === null || areaType === 'E') {
            areaDatasets.push({
                label: "Entrada",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: areaEntrada,
            });
        }

        if (areaType === null || areaType === 'O') {
            areaDatasets.push({
                label: "Saída",
                lineTension: 0.3,
                backgroundColor: "rgba(28, 200, 138, 0.05)",
                borderColor: "rgba(28, 200, 138, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(28, 200, 138, 1)",
                pointBorderColor: "rgba(28, 200, 138, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: areaSaida,
            });
        }

        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: areaLabels,
                datasets: areaDatasets,
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: true
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });

        var pieLabels = <?= json_encode($pie_labels) ?>;
        var pieValues = <?= json_encode($pie_values) ?>;
        var pieColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];

        var ctxPie = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieValues,
                    backgroundColor: pieColors,
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#e02d1b'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var label = chart.labels[tooltipItem.index] || '';
                            return label + ': ' + number_format(chart.datasets[0].data[tooltipItem.index]);
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    </script>

</body>

</html>
