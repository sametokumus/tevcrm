(function($) {
    "use strict";

	 $(document).ready(function() {



	});

	$(window).load(async function() {

		checkLogin();
        await getApprovedMontlySales();
        await getPotentialSales();
        await getCancelledPotentialSales();

	});

})(window.jQuery);

async function getApprovedMontlySales(){

    let data = await serviceGetApprovedMonthlySales();
    console.log(data)
    let sales = data.sales.reverse();

    let xAxisArray = [];
    let tryArray = [];
    let usdArray = [];
    let eurArray = [];
    let gbpArray = [];

    $.each(sales, function (i, sale) {
        xAxisArray.push(sale.month + "/" + sale.year);
        tryArray.push(sale.try_sale);
        usdArray.push(sale.usd_sale);
        eurArray.push(sale.eur_sale);
        gbpArray.push(sale.gbp_sale);
    });

    console.log(tryArray)

    let apexColumnChartOptions = {
        chart: {
            height: 350,
            type: 'bar'
        },
        title: {
            text: '',
            align: 'center'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
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
        colors: [COLOR_GRAY_600, COLOR_GRAY_500, COLOR_GRAY_400, COLOR_GRAY_300],
        series: [{
            name: 'TRY',
            data: tryArray
        }, {
            name: 'USD',
            data: usdArray
        }, {
            name: 'EUR',
            data: eurArray
        }, {
            name: 'GBP',
            data: gbpArray
        }],
        xaxis: {
            categories: xAxisArray,
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

async function getPotentialSales(){

    let data = await serviceGetPotentialSales();
    console.log(data)
    let sales = data.sales.reverse();

    let xAxisArray = [];
    let tryArray = [];
    let usdArray = [];
    let eurArray = [];
    let gbpArray = [];

    $.each(sales, function (i, sale) {
        xAxisArray.push(sale.month + "/" + sale.year);
        tryArray.push(sale.try_sale);
        usdArray.push(sale.usd_sale);
        eurArray.push(sale.eur_sale);
        gbpArray.push(sale.gbp_sale);
    });

    let apexColumnChartOptions = {
        chart: {
            height: 350,
            type: 'bar'
        },
        title: {
            text: '',
            align: 'center'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
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
        colors: [COLOR_GRAY_600, COLOR_GRAY_500, COLOR_GRAY_400, COLOR_GRAY_300],
        series: [{
            name: 'TRY',
            data: tryArray
        }, {
            name: 'USD',
            data: usdArray
        }, {
            name: 'EUR',
            data: eurArray
        }, {
            name: 'GBP',
            data: gbpArray
        }],
        xaxis: {
            categories: xAxisArray,
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
    let tryArray = [];
    let usdArray = [];
    let eurArray = [];
    let gbpArray = [];

    $.each(sales, function (i, sale) {
        xAxisArray.push(sale.month + "/" + sale.year);
        tryArray.push(sale.try_sale);
        usdArray.push(sale.usd_sale);
        eurArray.push(sale.eur_sale);
        gbpArray.push(sale.gbp_sale);
    });

    let apexColumnChartOptions = {
        chart: {
            height: 350,
            type: 'bar'
        },
        title: {
            text: '',
            align: 'center'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
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
        colors: [COLOR_GRAY_600, COLOR_GRAY_500, COLOR_GRAY_400, COLOR_GRAY_300],
        series: [{
            name: 'TRY',
            data: tryArray
        }, {
            name: 'USD',
            data: usdArray
        }, {
            name: 'EUR',
            data: eurArray
        }, {
            name: 'GBP',
            data: gbpArray
        }],
        xaxis: {
            categories: xAxisArray,
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
