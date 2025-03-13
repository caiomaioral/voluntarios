<?php 

    echo $AddCss;
    echo $AddJavascripts;

?>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />

    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h3">Dashboard</h3>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Compartilhar</button>
                <button class="btn btn-sm btn-outline-secondary">Exportar</button>
                </div>
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                Esta semana
                </button>
            </div>
        </div>

        <canvas class="my-4 w-100" id="myChart" width="900" height="250"></canvas>
        <br>

    </main>
    

<!-- Gráficos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script>

    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        datasets: [{
        data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        borderWidth: 4,
        pointBackgroundColor: '#007bff'
        }]
    },
    options: {
        scales: {
        yAxes: [{
            ticks: {
            beginAtZero: false
            }
        }]
        },
        legend: {
        display: false,
        }
    }
    });
    
</script>