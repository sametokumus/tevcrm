(function($) {
    "use strict";

	 $(document).ready(function() {



	});

	$(window).load(async function() {

		checkLogin();

        await getDashboardOwnersAddSelectId('dash_owner');

        dash_owner = localStorage.getItem('dash_owner');
        if (dash_owner == null){
            dash_owner = '0';
            localStorage.setItem('dash_owner', '0');
            document.getElementById('dash_owner').value = '0';
        }else{
            document.getElementById('dash_owner').value = dash_owner;
        }

        dash_currency = localStorage.getItem('dash_currency');
        if (dash_currency == null){
            dash_currency = 'TRY';
            localStorage.setItem('dash_currency', 'TRY');
            document.getElementById('dash_currency').value = 'TRY';
        }else{
            document.getElementById('dash_currency').value = dash_currency;
        }

        getTotalSales();
        getLastMonthSales();
        getApprovedMonthlySales();
        getCompletedMonthlySales();
        getPotentialMonthlySales();
        getCancelledMonthlySales();
        getAdminsSales();
        initTopSaledProducts();

	});

})(window.jQuery);
let dash_currency;
let dash_owner;
function changeDashCurrency(){
    dash_currency = document.getElementById('dash_currency').value;
    localStorage.setItem('dash_currency', dash_currency);
    location.reload();
}
function changeDashOwner(){
    dash_owner = document.getElementById('dash_owner').value;
    localStorage.setItem('dash_owner', dash_owner);
    location.reload();
}

async function getTotalSales(){

    let data = await serviceGetTotalSales(dash_owner);
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
    let data = await serviceGetLastMonthSales(dash_owner);
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

    let data = await serviceGetApprovedMonthlySales(dash_owner);
    console.log(data)
    let sales = data.sales.reverse();
    console.log(sales)

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
    console.log(xAxisArray)
    console.log(yAxisArray)

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

    let data = await serviceGetCompletedMonthlySales(dash_owner);
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

    let data = await serviceGetPotentialSales(dash_owner);
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

    let data = await serviceGetCancelledPotentialSales(dash_owner);
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

    let data = await serviceGetMonthlyApprovedSalesLastTwelveMonthsByAdmins(dash_owner);
    let admins = data.admins;
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
    let data = await serviceGetTopSaledProducts(dash_owner);
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
