var weekday, month;

function getData(hourly, daily, weekly, monthly) {
    // Get the daily energy consumption data
    if(hourly)
        $.ajax({
            url: './res/scripts/hourly_usage.php',
            type: 'get',
            success: function (output) {
                handleData(JSON.parse(output), 'graph-hourly', 'hourly');
            }
        });
    
    // Get the daily energy consumption data
    if(daily)
        $.ajax({
            url: './res/scripts/daily_usage.php',
            type: 'get',
            success: function (output) {
                handleData(JSON.parse(output), 'graph-daily', 'daily');
            }
        });

    // Get the weekly energy consumption data
    if(weekly)
        $.ajax({
            url: './res/scripts/weekly_usage.php',
            type: 'get',
            success: function (output) {
                handleData(JSON.parse(output), 'graph-weekly', 'weekly');
            }
        });

    // Get the monthly energy consumption data
    if(monthly)
        $.ajax({
            url: './res/scripts/monthly_usage.php',
            type: 'get',
            success: function (output) {
                handleData(JSON.parse(output), 'graph-monthly', 'monthly');
            }
        });
}

function handleData(database, graph_id, label_type) {
    // Result: {timestamp, verbr_laag, verbr_hoog}
    // WARNING: This data is EXCLUDING any produce (e.g. from solar panels)

    var data = [];
    var labels = [];

    for (var i = 1; i < database.length; i++) {
        var usage = parseFloat(database[i].verbr_laag) + parseFloat(database[i].verbr_hoog) - parseFloat(database[i - 1].verbr_laag) - parseFloat(database[i - 1].verbr_hoog);
        data[i - 1] = usage;
        switch (label_type) {
            case 'hourly':
            case 'daily':
                labels[i - 1] = database[i].timestamp.toString().split(' ')[1].substr(0, 5);
                break;
            case 'weekly':
                labels[i - 1] = weekday[new Date(database[i].timestamp).getDay()];
                break;
            case 'monthly':
                labels[i - 1] = month[new Date(database[i].timestamp).getMonth()];
                break;
            default:
                labels[i - 1] = 'ERR';
        }

    }
    
    showLineGraph(graph_id, data, labels);
}

function showLineGraph(graph_id, data, labels) {
    var ctx = document.getElementById(graph_id);
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'kWh',
                data: data
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function setDateInfo(){
    weekday = new Array(7);
    weekday[0] =  "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";
    
    month = new Array(12);
    month[0] = "January";
    month[1] = "February";
    month[2] = "March";
    month[3] = "April";
    month[4] = "May";
    month[5] = "June";
    month[6] = "July";
    month[7] = "August";
    month[8] = "September";
    month[9] = "October";
    month[10] = "November";
    month[11] = "December";
}