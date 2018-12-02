var weekday, month;

function generateGraph(item) {
    switch(item){
        case '1 Uur':
            getData('./res/php/read_hourly.php', 'hourly');
            break;
        case '24 Uur':
            getData('./res/php/read_daily.php', 'daily');
            break;
        case '7 Dagen':
            getData('./res/php/read_weekly.php', 'weekly');
            break;
        case '1 Jaar':
            getData('./res/php/read_monthly.php', 'yearly');
    }
}

function getData(file, type){
    $.ajax({
        url: file,
        type: 'get',
        success: function (output) {
            processData(output, type);
        }
    });
}

function processData(json_data, type){
    $('#chart').remove();
    var canvas = $('<canvas/>', {id: 'chart'})
    $('#content').prepend(canvas);
    
    var labels = [];
    var usageData = [];
    var productionData = [];
    
    json_data = JSON.parse(json_data);
    for (var i = 1; i < json_data.length; i++) {
        var usage = parseFloat(json_data[i].verbr_laag) + parseFloat(json_data[i].verbr_hoog) - parseFloat(json_data[i - 1].verbr_laag) - parseFloat(json_data[i - 1].verbr_hoog);
        var produced = parseFloat(json_data[i].terug_laag) + parseFloat(json_data[i].terug_hoog) - parseFloat(json_data[i - 1].terug_laag) - parseFloat(json_data[i - 1].terug_hoog);
        usageData[i - 1] = usage;
        productionData[i - 1] = produced;
        switch (type) {
            case 'hourly':
            case 'daily':
                labels[i - 1] = json_data[i].timestamp.toString().split(' ')[1].substr(0, 5);
                break;
            case 'weekly':
                labels[i - 1] = weekday[new Date(json_data[i].timestamp).getDay()];
                break;
            case 'yearly':
                labels[i - 1] = month[new Date(json_data[i].timestamp).getMonth()];
                break;
            default:
                labels[i - 1] = 'ERR';
        }
    }
    
    var ctx = document.getElementById("chart").getContext('2d');
    
    var myChart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Afgenomen",
                    backgroundColor: 'rgba(91, 192, 222, .7)',
                    borderColor: 'rgba(49, 176, 213, .7)',
                    data: usageData
                },
                {
                    label: "Opgewekt",
                    backgroundColor: 'rgba(100, 225, 50, .7)',
                    borderColor: 'rgba(50, 125, 25, .7)',
                    data: productionData
                }
                ]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                        min: 0
                    }
                }]
            }
        }
    });
    
    console.log('Refreshed data for ' + type + ' chart');
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