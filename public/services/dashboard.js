(function($) {
    "use strict";

	 $(document).ready(function() {



	});

	$(window).load(async function() {

		checkLogin();

        dash_currency = localStorage.getItem('dash_currency');
        console.log(dash_currency)
        if (dash_currency == null){
            dash_currency = 'TRY';
            localStorage.setItem('dash_currency', 'TRY');
            document.getElementById('dash_currency').value = 'TRY';
        }else{
            document.getElementById('dash_currency').value = dash_currency;
        }

        await getApprovedMontlySales();
        await getPotentialSales();
        await getCancelledPotentialSales();

	});

})(window.jQuery);
let dash_currency;
function changeDashCurrency(){
    dash_currency = document.getElementById('dash_currency').value;
    localStorage.setItem('dash_currency', dash_currency);
    location.reload();
}


async function getApprovedMontlySales(){

    let data = await serviceGetApprovedMonthlySales();
    console.log(data)
    let sales = data.sales.reverse();

    let xAxisArray = [];
    let yAxisArray = [];

    $.each(sales, function (i, sale) {
        xAxisArray.push(sale.month + "/" + sale.year);

        if (dash_currency == 'TRY'){
            yAxisArray.push(sale.try_sale);
        }else if (dash_currency == 'USD'){
            yAxisArray.push(sale.usd_sale);
        }else if (dash_currency == 'EUR'){
            yAxisArray.push(sale.eur_sale);
        }
    });

    let apexColumnChartOptions = {
        chart: {
            height: 350,
            type: 'bar'
        },
        title: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                fontFamily: FONT_FAMILY,
                color: COLOR_WHITE
            },
        },
        legend: {
            fontFamily: FONT_FAMILY,
            labels: {
                colors: '#fff'
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        colors: ['#90ee7e'],
        series: [{
            name: dash_currency,
            data: yAxisArray
        }],
        xaxis: {
            categories: xAxisArray,
            labels: {
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        yaxis: {
            title: {
                text: 'Kazanç',
                style: {
                    color: hexToRgba(COLOR_WHITE, .5),
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400
                }
            },
            labels: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                },
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                }
            }
        }
    };
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-approved-monthly'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

}

async function getApprovedMontlySales(){

    let data = await serviceGetCompletedMonthlySales();
    console.log(data)
    let sales = data.sales.reverse();

    let xAxisArray = [];
    let yAxisArray = [];

    $.each(sales, function (i, sale) {
        xAxisArray.push(sale.month + "/" + sale.year);

        if (dash_currency == 'TRY'){
            yAxisArray.push(sale.try_sale);
        }else if (dash_currency == 'USD'){
            yAxisArray.push(sale.usd_sale);
        }else if (dash_currency == 'EUR'){
            yAxisArray.push(sale.eur_sale);
        }
    });

    let apexColumnChartOptions = {
        chart: {
            height: 350,
            type: 'bar'
        },
        title: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                fontFamily: FONT_FAMILY,
                color: COLOR_WHITE
            },
        },
        legend: {
            fontFamily: FONT_FAMILY,
            labels: {
                colors: '#fff'
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        colors: ['#90ee7e'],
        series: [{
            name: dash_currency,
            data: yAxisArray
        }],
        xaxis: {
            categories: xAxisArray,
            labels: {
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        yaxis: {
            title: {
                text: 'Kazanç',
                style: {
                    color: hexToRgba(COLOR_WHITE, .5),
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400
                }
            },
            labels: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                },
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                }
            }
        }
    };
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-completed-monthly'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

}

async function getPotentialSales(){

    let data = await serviceGetPotentialSales();
    console.log(data)
    let sales = data.sales.reverse();

    let xAxisArray = [];
    let yAxisArray = [];

    $.each(sales, function (i, sale) {
        xAxisArray.push(sale.month + "/" + sale.year);
        if (dash_currency == 'TRY'){
            yAxisArray.push(sale.try_sale);
        }else if (dash_currency == 'USD'){
            yAxisArray.push(sale.usd_sale);
        }else if (dash_currency == 'EUR'){
            yAxisArray.push(sale.eur_sale);
        }
    });

    let apexColumnChartOptions = {
        chart: {
            height: 350,
            type: 'bar'
        },
        title: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                fontFamily: FONT_FAMILY,
                color: COLOR_WHITE
            },
        },
        legend: {
            fontFamily: FONT_FAMILY,
            labels: {
                colors: '#fff'
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                endingShape: 'rounded',
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        colors: ['#4ecdc4'],
        series: [{
            name: dash_currency,
            data: yAxisArray
        }],
        xaxis: {
            categories: xAxisArray,
            labels: {
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        yaxis: {
            title: {
                text: 'Kazanç',
                style: {
                    color: hexToRgba(COLOR_WHITE, .7),
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400
                }
            },
            labels: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                },
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                }
            }
        }
    };
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-potential-sales'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

}

async function getCancelledPotentialSales(){

    let data = await serviceGetCancelledPotentialSales();
    console.log(data)
    let sales = data.sales.reverse();

    let xAxisArray = [];
    let yAxisArray = [];

    $.each(sales, function (i, sale) {
        xAxisArray.push(sale.month + "/" + sale.year);
        if (dash_currency == 'TRY'){
            yAxisArray.push(sale.try_sale);
        }else if (dash_currency == 'USD'){
            yAxisArray.push(sale.usd_sale);
        }else if (dash_currency == 'EUR'){
            yAxisArray.push(sale.eur_sale);
        }
    });

    let apexColumnChartOptions = {
        chart: {
            height: 350,
            type: 'bar'
        },
        title: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                fontFamily: FONT_FAMILY,
                color: COLOR_WHITE
            },
        },
        legend: {
            fontFamily: FONT_FAMILY,
            labels: {
                colors: '#fff'
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        colors: ['#4ecdc4'],
        series: [{
            name: dash_currency,
            data: yAxisArray
        }],
        xaxis: {
            categories: xAxisArray,
            labels: {
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        yaxis: {
            title: {
                text: 'Kazanç',
                style: {
                    color: hexToRgba(COLOR_WHITE, .5),
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400
                }
            },
            labels: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                },
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2))
                }
            }
        }
    };
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-cancelled-potential-sales'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

}
