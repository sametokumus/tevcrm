(function($) {
    "use strict";

	 $(document).ready(function() {



	});

	$(window).load(async function() {

		checkLogin();

        dash_currency = localStorage.getItem('dash_currency');
        if (dash_currency == null){
            dash_currency = 'TRY';
            localStorage.setItem('dash_currency', 'TRY');
            document.getElementById('dash_currency').value = 'TRY';
        }else{
            document.getElementById('dash_currency').value = dash_currency;
        }

        getAccountingStats();
        getCashFlows();

        // getTotalSales();
        // getLastMonthSales();
        // getApprovedMonthlySales();
        // getCompletedMonthlySales();
        // getPotentialMonthlySales();
        // getCancelledMonthlySales();
        // getAdminsSales();
        // initTopSaledProducts();

	});

})(window.jQuery);
let dash_currency;
function changeDashCurrency(){
    dash_currency = document.getElementById('dash_currency').value;
    localStorage.setItem('dash_currency', dash_currency);
    location.reload();
}

var randomizeArray = function (arg) {
    var array = arg.slice();
    var currentIndex = array.length, temporaryValue, randomIndex;

    while (0 !== currentIndex) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}

async function getAccountingStats(){

    let data = await serviceGetAccountingStats();
    let stats = data.stats;
    console.log(stats)

    $('#total-box h4').append(changeCommasToDecimal(stats.total.try_sale) + ' TRY');
    let text1 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.total.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.total.eur_sale);
    $('#total-text').append(text1);

    $('#pending-box h4').append(changeCommasToDecimal(stats.pending.try_sale) + ' TRY');
    let text2 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.pending.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.pending.eur_sale);
    $('#pending-text').append(text2);

    $('#late-box h4').append(changeCommasToDecimal(stats.late.try_sale) + ' TRY');
    let text3 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.late.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.late.eur_sale);
    $('#late-text').append(text3);

    $('#profit-box h4').append(changeCommasToDecimal(parseFloat(stats.profit_rate).toFixed(2)) + '%');
    // let text4 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.cancelled.usd_sale) +'<br/>\n' +
    //     '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.cancelled.eur_sale);
    // $('#cancelled-text').append(text4);



}

async function getCashFlows(){

    let data = await serviceGetCashFlows();
    let months = data.months;
    console.log(months)

    let pendingArray = [];
    let lateArray = [];

    $.each(months, function (i, month) {
        let month_box = '';

        month_box += '<div class="col-xl-3 col-md-4">\n' +
            '               <div class="card mb-3">\n' +
            '                   <div class="card-header d-flex align-items-center bg-white bg-opacity-15">\n' +
            '                       <span class="flex-grow-1 fw-400 fs-18px">'+ month.month +'/'+ month.year +'</span>\n' +
            '                       <span class="text-white text-decoration-none me-3">'+ changeCommasToDecimal(month.prices.try_sale) +' TRY<br>'+ changeCommasToDecimal(month.prices.eur_sale) +' EUR<br>'+ changeCommasToDecimal(month.prices.usd_sale) +' USD</span>\n' +
            '                   </div>\n' +
            '                   <div class="list-group list-group-flush">';

        $.each(month.payments, function (i, payment) {

            let date = formatDateASC(payment.due_date, '-');
            let price = payment.payment_price;
            let data = {
                "x": date,
                "y": price,
                "z": 14
            };

            let date_status = '';
            if (!payment.date_status){
                date_status = ' <div class="mb-2">\n' +
                    '               <span class="badge border border-danger text-danger">Gecikmede</span>\n' +
                    '           </div>\n';

                lateArray.push(data);

            }else{

                pendingArray.push(data);

            }

            month_box += '          <div class="list-group-item d-flex px-3">\n' +
                '                       <div class="me-3 pt-1">\n' +
                '                           <i class="far fa-question-circle text-white text-opacity-50 fa-fw fa-lg"></i>\n' +
                '                       </div>\n' +
                '                       <div class="flex-fill">\n' +
                '                           <div class="fw-600">'+ payment.sale.owner.short_code +'-'+ payment.sale.id +'</div>\n' +
                '                           <div class="fw-400"><b>Müşteri:</b> '+ payment.sale.customer.name +'</div>\n' +
                '                           <div class="text-white"><b>Ödeme Tutarı:</b> '+ changeCommasToDecimal(payment.payment_price) +'</div>\n' +
                '                           <div class="text-white"><b>Ödeme Tarihi:</b> '+ formatDateASC(payment.due_date, '-') +'</div>\n' +
                '                           <div class="small text-white text-opacity-50 mb-2">EUR: '+ payment.sale.eur_rate +' / USD: '+ payment.sale.usd_rate +'</div>\n' +
                '                           '+ date_status +
                '                       </div>\n' +
                '                   </div>';

        });

        month_box += '          </div>\n' +
            '                   <div class="card-arrow">\n' +
            '                       <div class="card-arrow-top-left"></div>\n' +
            '                       <div class="card-arrow-top-right"></div>\n' +
            '                       <div class="card-arrow-bottom-left"></div>\n' +
            '                       <div class="card-arrow-bottom-right"></div>\n' +
            '                   </div>\n' +
            '               </div>\n' +
            '         </div>';

        $('#cashflow-box').append(month_box);


    });

    var processedPendingArray = pendingArray.map(function(item) {
        var parts = item.x.split('-');
        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10) - 1;  // JavaScript months are 0-indexed
        var year = parseInt(parts[2], 10);
        return {
            x: new Date(year, month, day).getTime(),
            y: item.y,
            z: item.z
        };
    });
    var processedLateArray = lateArray.map(function(item) {
        var parts = item.x.split('-');
        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10) - 1;  // JavaScript months are 0-indexed
        var year = parseInt(parts[2], 10);
        return {
            x: new Date(year, month, day).getTime(),
            y: item.y,
            z: item.z
        };
    });

    console.log(processedPendingArray)
    console.log(processedLateArray)

    var options = {
        series: [{
            name: 'Bekleyen Ödemeler',
            data: processedPendingArray
        },{
            name: 'Geciken Ödemeler',
            data: processedLateArray
        }],
        chart: {
            height: 350,
            type: 'bubble'
        },
        title: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                fontFamily: FONT_FAMILY,
                color: COLOR_WHITE
            },
        },fill: {
            colors: ['#F44336', '#E91E63', '#9C27B0']
        },
        plotOptions: {
            bubble: {
                color: ['#90ee7e', '#d94848'],  // Set colors for each series
            }
        },
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        fill: {
            opacity: 1
        },
        xaxis: {
            tickAmount: 12,
            type: 'datetime',
            labels: {
                rotate: 0,
                color: '#ffffff',
                style: {
                    colors: '#fff',
                    fontSize: '12px',
                    fontFamily: FONT_FAMILY,
                    fontWeight: 400,
                    cssClass: 'apexcharts-xaxis-label',
                },
                formatter: function(val) {
                    // Format date to 'd-m-Y'
                    var date = new Date(val);
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    return day + '-' + (month < 10 ? '0' : '') + month + '-' + year;
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return changeCommasToDecimal(val)
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
        tooltip: {
            custom: function({ series, seriesIndex, dataPointIndex }) {
                var date = new Date(options.series[seriesIndex].data[dataPointIndex].x);
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                var label_x = day + '-' + (month < 10 ? '0' : '') + month + '-' + year;
                var label_y = options.series[seriesIndex].data[dataPointIndex].y;
                return (
                    '<div class="tooltip-custom">' +
                        '<div class="tooltip-custom-header">'+
                            options.series[seriesIndex].name +
                        '</div>'+
                        '<div class="tooltip-custom-content">'+
                            '<p> Tarih: ' +
                            label_x +
                            '</p>' +
                            '<p>Tutar: ' +
                            changeCommasToDecimal(label_y) + ' TRY' +
                            '</p>' +
                        '</div>'+
                    '</div>'
                );
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-cashflow"), options);
    chart.render();

}

function generateData(baseval, count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
        var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;
        var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
        series.push([baseval, x, y]);
        baseval += 86400000;
        i++;
    }
    return series;
}




async function getTotalSales(){

    let data = await serviceGetTotalSales();
    let sales = data.sales;

    $('#approved-box h4').append(changeCommasToDecimal(sales.approved.try_sale) + ' TRY');
    let text1 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.approved.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.approved.eur_sale);
    $('#approved-text').append(text1);

    $('#completed-box h4').append(changeCommasToDecimal(sales.completed.try_sale) + ' TRY');
    let text2 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.completed.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.completed.eur_sale);
    $('#completed-text').append(text2);

    $('#potential-box h4').append(changeCommasToDecimal(sales.continue.try_sale) + ' TRY');
    let text3 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.continue.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.continue.eur_sale);
    $('#potential-text').append(text3);

    $('#cancelled-box h4').append(changeCommasToDecimal(sales.cancelled.try_sale) + ' TRY');
    let text4 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.cancelled.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.cancelled.eur_sale);
    $('#cancelled-text').append(text4);



}

async function getLastMonthSales(){
    let data = await serviceGetLastMonthSales();
    let sales = data.sales;
    let continue_data = sales.continue.continue_serie_try.map(parseFloat);
    let approved_data = sales.approved.approved_serie_try.map(parseFloat);
    let completed_data = sales.completed.completed_serie_try.map(parseFloat);
    let cancelled_data = sales.cancelled.cancelled_serie_try.map(parseFloat);
    let day_count = sales.day_count;

    $('#monthly-approved-box h4').append(changeCommasToDecimal(sales.approved.try_sale) + ' TRY');
    let text1 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.approved.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.approved.eur_sale);
    $('#monthly-approved-text').append(text1);

    $('#monthly-completed-box h4').append(changeCommasToDecimal(sales.completed.try_sale) + ' TRY');
    let text2 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.completed.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.completed.eur_sale);
    $('#monthly-completed-text').append(text2);

    $('#monthly-continue-box h4').append(changeCommasToDecimal(sales.continue.try_sale) + ' TRY');
    let text3 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.continue.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.continue.eur_sale);
    $('#monthly-continue-text').append(text3);

    $('#monthly-cancelled-box h4').append(changeCommasToDecimal(sales.cancelled.try_sale) + ' TRY');
    let text4 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.cancelled.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(sales.cancelled.eur_sale);
    $('#monthly-cancelled-text').append(text4);

    var spark1 = {
        chart: {
            id: 'sparkline1',
            group: 'sparklines',
            type: 'area',
            height: 100,
            sparkline: {
                enabled: true
            },
        },
        stroke: {
            curve: 'straight',
            width: 1
        },
        fill: {
            opacity: 0.3,
        },
        series: [{
            name: 'Sales',
            data: approved_data
        }],
        labels: [...Array(day_count).keys()].map(n => `2018-09-0${n+1}`),
        yaxis: {
            min: 0
        },
        xaxis: {
            type: 'datetime',
        },
        colors: ['rgb(144, 238, 126)'],
    }

    var spark2 = {
        chart: {
            id: 'sparkline2',
            group: 'sparklines',
            type: 'area',
            height: 100,
            sparkline: {
                enabled: true
            },
        },
        stroke: {
            curve: 'straight',
            width: 1
        },
        fill: {
            opacity: 0.3,
        },
        series: [{
            name: 'Sales',
            data: completed_data
        }],
        labels: [...Array(day_count).keys()].map(n => `2018-09-0${n+1}`),
        yaxis: {
            min: 0
        },
        xaxis: {
            type: 'datetime',
        },
        colors: ['rgb(254, 176, 25)'],
    }

    var spark3 = {
        chart: {
            id: 'sparkline3',
            group: 'sparklines',
            type: 'area',
            height: 100,
            sparkline: {
                enabled: true
            },
        },
        stroke: {
            curve: 'straight',
            width: 1
        },
        fill: {
            opacity: 0.3,
        },
        series: [{
            name: 'Sales',
            data: continue_data
        }],
        labels: [...Array(day_count).keys()].map(n => `2018-09-0${n+1}`),
        yaxis: {
            min: 0
        },
        xaxis: {
            type: 'datetime',
        },
        colors: ['rgb(78, 205, 196)'],
    }

    var spark4 = {
        chart: {
            id: 'sparkline4',
            group: 'sparklines',
            type: 'area',
            height: 100,
            sparkline: {
                enabled: true
            },
        },
        stroke: {
            curve: 'straight',
            width: 1
        },
        fill: {
            opacity: 0.3,
        },
        series: [{
            name: 'Sales',
            data: cancelled_data
        }],
        labels: [...Array(day_count).keys()].map(n => `2018-09-0${n+1}`),
        yaxis: {
            min: 0
        },
        xaxis: {
            type: 'datetime',
        },
        colors: ['rgb(255, 69, 96)'],
    }

    new ApexCharts(document.querySelector("#spark1"), spark1).render();
    new ApexCharts(document.querySelector("#spark2"), spark2).render();
    new ApexCharts(document.querySelector("#spark3"), spark3).render();
    new ApexCharts(document.querySelector("#spark4"), spark4).render();
}

async function getApprovedMonthlySales(){

    let data = await serviceGetApprovedMonthlySales();
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

async function getCompletedMonthlySales(){

    let data = await serviceGetCompletedMonthlySales();
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
        colors: ['#feb019d9'],
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

async function getPotentialMonthlySales(){

    let data = await serviceGetPotentialSales();
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

async function getCancelledMonthlySales(){

    let data = await serviceGetCancelledPotentialSales();
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
        colors: ['#ff4560d9'],
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

async function getAdminsSales(){

    let data = await serviceGetMonthlyApprovedSalesLastTwelveMonthsByAdmins();
    let admins = data.admins;
    console.log(admins)
    admins.sort((a, b) => parseFloat(b.total_sales.try_total) - parseFloat(a.total_sales.try_total));


    $('#admins-table tbody tr').remove();
    $.each(admins, function (i, admin) {
        let item = '<tr>\n' +
            '           <td>'+ admin.id +'</td>\n' +
            '           <td>'+ admin.name +' '+ admin.surname +'</td>\n' +
            '           <td>'+ admin.total_sales.sale_count +'</td>\n' +
            '           <td>'+ changeCommasToDecimal(admin.total_sales.try_total) +' TRY</td>\n' +
            '           <td>'+ changeCommasToDecimal(admin.total_sales.usd_total) +' USD</td>\n' +
            '           <td>'+ changeCommasToDecimal(admin.total_sales.eur_total) +' EUR</td>\n' +
            '       </tr>';
        $('#admins-table tbody').append(item);
    });


}

async function initTopSaledProducts(){
    let data = await serviceGetTopSaledProducts();
    let products = data.products;

    $('#top-products-table tbody tr').remove();

    $.each(products, function (i, product) {

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ checkNull(product.product_detail.ref_code) +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   '+ product.product_detail.product_name +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td><small>'+ product.total_quantity +' Adet</small></td>\n' +
            '       </tr>';

        $('#top-products-table tbody').append(item);
    });
}
