(function($) {
    "use strict";

	 $(document).ready(function() {

         reLoadGrid();
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
        getTotalProfitRate();
        getLastMonthSales();
        getApprovedMonthlySales();
        getCompletedMonthlySales();
        getPotentialMonthlySales();
        getCancelledMonthlySales();
        getMonthlyProfitRates();
        getAdminsSales();
        initTopSaledProducts();
        getBestCustomers();
        getBestStaffs();
        getCustomerByNotSaleLongTimes();
        getCustomerByNotSale();

	});

})(window.jQuery);

let dash_currency;
let dash_owner;
function reLoadGrid(){
    $('.masonry-layout-1').masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        gutter: '.gutter-sizer',
        percentPosition: true
    });

    $('.masonry-layout-2').masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        gutter: '.gutter-sizer',
        percentPosition: true
    });
}
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

    let approved_sale = 0;
    let completed_sale = 0;
    let continue_sale = 0;
    let cancelled_sale = 0;
    if (dash_currency == 'TRY'){
        approved_sale = sales.approved.try_sale;
        completed_sale = sales.completed.try_sale;
        continue_sale = sales.continue.try_sale;
        cancelled_sale = sales.cancelled.try_sale;
    }else if (dash_currency == 'USD'){
        approved_sale = sales.approved.usd_sale;
        completed_sale = sales.completed.usd_sale;
        continue_sale = sales.continue.usd_sale;
        cancelled_sale = sales.cancelled.usd_sale;
    }else if (dash_currency == 'EUR'){
        approved_sale = sales.approved.eur_sale;
        completed_sale = sales.completed.eur_sale;
        continue_sale = sales.continue.eur_sale;
        cancelled_sale = sales.cancelled.eur_sale;
    }

    $('#approved-box .spinners').remove();
    $('#completed-box .spinners').remove();
    $('#potential-box .spinners').remove();
    $('#cancelled-box .spinners').remove();

    $('#approved-box h5').append(changeCommasToDecimal(approved_sale) + ' ' + dash_currency);
    $('#completed-box h5').append(changeCommasToDecimal(completed_sale) + ' ' + dash_currency);
    $('#potential-box h5').append(changeCommasToDecimal(continue_sale) + ' ' + dash_currency);
    $('#cancelled-box h5').append(changeCommasToDecimal(cancelled_sale) + ' ' + dash_currency);

}

async function getLastMonthSales(){
    let data = await serviceGetLastMonthSales(dash_owner);
    let sales = data.sales;
    let continue_data = sales.continue.continue_serie_try.map(parseFloat);
    let approved_data = sales.approved.approved_serie_try.map(parseFloat);
    let completed_data = sales.completed.completed_serie_try.map(parseFloat);
    let cancelled_data = sales.cancelled.cancelled_serie_try.map(parseFloat);
    let day_count = sales.day_count;

    let approved_sale = 0;
    let completed_sale = 0;
    let continue_sale = 0;
    let cancelled_sale = 0;
    if (dash_currency == 'TRY'){
        approved_sale = sales.approved.try_sale;
        completed_sale = sales.completed.try_sale;
        continue_sale = sales.continue.try_sale;
        cancelled_sale = sales.cancelled.try_sale;
    }else if (dash_currency == 'USD'){
        approved_sale = sales.approved.usd_sale;
        completed_sale = sales.completed.usd_sale;
        continue_sale = sales.continue.usd_sale;
        cancelled_sale = sales.cancelled.usd_sale;
    }else if (dash_currency == 'EUR'){
        approved_sale = sales.approved.eur_sale;
        completed_sale = sales.completed.eur_sale;
        continue_sale = sales.continue.eur_sale;
        cancelled_sale = sales.cancelled.eur_sale;
    }

    $('#monthly-approved-box .spinners').remove();
    $('#monthly-completed-box .spinners').remove();
    $('#monthly-continue-box .spinners').remove();
    $('#monthly-cancelled-box .spinners').remove();

    $('#monthly-approved-box h5').append(changeCommasToDecimal(approved_sale) + ' ' + dash_currency);
    $('#monthly-completed-box h5').append(changeCommasToDecimal(completed_sale) + ' ' + dash_currency);
    $('#monthly-continue-box h5').append(changeCommasToDecimal(continue_sale) + ' ' + dash_currency);
    $('#monthly-cancelled-box h5').append(changeCommasToDecimal(cancelled_sale) + ' ' + dash_currency);

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
            height: 300,
            type: 'bar'
        },
        title: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                fontFamily: FONT_FAMILY,
                color: COLOR_DARK
            },
        },
        legend: {
            fontFamily: FONT_FAMILY,
            labels: {
                colors: COLOR_DARK
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
                    colors: COLOR_DARK,
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
                    colors: COLOR_DARK,
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
    $('#chart-approved-monthly .spinners').remove();
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-approved-monthly'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

    reLoadGrid();

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
            height: 300,
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
                colors: COLOR_DARK
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
                    colors: COLOR_DARK,
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
                    colors: COLOR_DARK,
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
    $('#chart-completed-monthly .spinners').remove();
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-completed-monthly'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

    reLoadGrid();

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
            height: 300,
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
                colors: COLOR_DARK
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
                    colors: COLOR_DARK,
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
                    colors: COLOR_DARK,
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
    $('#chart-potential-sales .spinners').remove();
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-potential-sales'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

    reLoadGrid();

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
            height: 300,
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
                colors: COLOR_DARK
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
                    colors: COLOR_DARK,
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
                    colors: COLOR_DARK,
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
    $('#chart-cancelled-potential-sales .spinners').remove();
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-cancelled-potential-sales'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

    reLoadGrid();

}

async function getAdminsSales(){

    let data = await serviceGetMonthlyApprovedSalesLastTwelveMonthsByAdmins(dash_owner);
    let admins = data.admins;
    admins.sort((a, b) => parseFloat(b.total_sales.try_total) - parseFloat(a.total_sales.try_total));

    $('#admins-table').siblings('.spinners').remove();

    $('#admins-table tbody tr').remove();
    $.each(admins, function (i, admin) {

        let sale_price = 0;
        if (dash_currency == 'TRY'){
            sale_price = admin.total_sales.try_total;
        }else if (dash_currency == 'USD'){
            sale_price = admin.total_sales.usd_total;
        }else if (dash_currency == 'EUR'){
            sale_price = admin.total_sales.eur_total;
        }

        let item = '<tr>\n' +
            '           <td>'+ admin.id +'</td>\n' +
            '           <td>'+ admin.name +' '+ admin.surname +'</td>\n' +
            '           <td>'+ admin.total_sales.sale_count +'</td>\n' +
            '           <td>'+ changeCommasToDecimal(sale_price) +' '+ dash_currency +'</td>\n' +
            '       </tr>';
        $('#admins-table tbody').append(item);
    });

    reLoadGrid();

}

async function initTopSaledProducts(){
    let data = await serviceGetTopSaledProducts(dash_owner);
    let products = data.products;

    $('#top-products-table').siblings('.spinners').remove();

    $('#top-products-table tbody tr').remove();

    $.each(products, function (i, product) {

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ product.product_detail.product_name.substring(0, 50) +'...\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td><small>'+ product.total_quantity +' Adet</small></td>\n' +
            '       </tr>';

        $('#top-products-table tbody').append(item);
    });

    reLoadGrid();
}

async function getBestCustomers(){

    let data = await serviceGetBestCustomer();
    let companies = data.companies;

    $('#best-customers-table').siblings('.spinners').remove();

    $('#best-customers-table tbody tr').remove();
    $.each(companies, function (i, company) {
        let item = '<tr>\n' +
            '           <td>'+ (i+1) +'</td>\n' +
            '           <td>'+ company.company.name.substring(0, 30) +'...</td>\n' +
            '           <td>'+ company.company_rate +'</td>\n' +
            '       </tr>';
        $('#best-customers-table tbody').append(item);
    });

    reLoadGrid();

}

async function getBestStaffs(){

    let data = await serviceGetBestStaff();
    let staffs = data.staffs;

    $('#best-staffs-table').siblings('.spinners').remove();

    $('#best-staffs-table tbody tr').remove();
    $.each(staffs, function (i, staff) {
        let item = '<tr>\n' +
            '           <td>'+ (i+1) +'</td>\n' +
            '           <td>'+ staff.staff.name +' '+ staff.staff.surname +'</td>\n' +
            '           <td>'+ staff.staff_rate +'</td>\n' +
            '       </tr>';
        $('#best-staffs-table tbody').append(item);
    });

    reLoadGrid();
}

async function getTotalProfitRate(){

    let data = await serviceGetTotalProfitRate(dash_owner);
    console.log(data)
    let profit_rate = data.profit_rate;

    $('#total-profit-box .spinners').remove();

    $('#total-profit-box h5').append(profit_rate + '%');

}

async function getMonthlyProfitRates(){

    let data = await serviceGetMonthlyProfitRatesLastTwelveMonths(dash_owner);
    console.log(data)
    let profit_rates = data.profit_rates.reverse();

    let xAxisArray = [];
    let yAxisArray = [];

    $.each(profit_rates, function (i, rate) {
        xAxisArray.push(rate.month + "/" + rate.year);
        yAxisArray.push(rate.profit_rate);
    });

    var apexLineChartOptions = {
        chart: {
            height: 300,
            type: 'line',
            toolbar: { show: false }
        },
        title: {
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                fontFamily: FONT_FAMILY,
                color: COLOR_DARK
            },
        },
        legend: {
            show: true,
            position: 'top',
            offsetY: -10,
            horizontalAlign: 'right',
            floating: true
        },
        colors: [app.color.theme],
        dataLabels: {
            enabled: true,
            offsetY: 1,
            background: {
                enabled: true,
                padding: 4,
                borderRadius: 4,
                borderWidth: 0,
                opacity: 0.9,
                dropShadow: { enabled: false }
            },
            style: {
                fontSize: '11px',
                fontFamily: app.font.bodyFontFamily,
                fontWeight: 500
            }
        },
        stroke: { curve: 'smooth', width: 3 },
        grid: {
            row: {
                colors: [app.color.bodyBg, 'transparent'],
                opacity: 0.5
            }
        },
        series: [
            { name: 'Karlılık', data: yAxisArray }
        ],
        markers: { size: 4 },
        xaxis: { categories: xAxisArray },
        yaxis: { min: 0, max: 250 }
    };
    $('#chart-profit-rates-monthly .spinners').remove();
    var apexLineChart = new ApexCharts(
        document.querySelector('#chart-profit-rates-monthly'),
        apexLineChartOptions
    );
    apexLineChart.render();

    reLoadGrid();

}

async function getCustomerByNotSaleLongTimes(){

    let data = await serviceGetCustomerByNotSaleLongTimes();
    let companies = data.companies;

    $('#customer-not-sale-timely-table').siblings('.spinners').remove();

    $('#customer-not-sale-timely-table tbody tr').remove();
    $.each(companies, function (i, company) {
        let item = '<tr>\n' +
            '           <td>'+ company.id +'</td>\n' +
            '           <td>'+ company.name +'</td>\n' +
            '           <td>'+ formatDateASC(company.last_sale_date, '-') +'</td>\n' +
            '       </tr>';
        $('#customer-not-sale-timely-table tbody').append(item);
    });

    reLoadGrid();
}

async function getCustomerByNotSale(){

    let data = await serviceGetCustomerByNotSale();
    let companies = data.companies;

    $('#customer-not-sale-table').siblings('.spinners').remove();

    $('#customer-not-sale-table tbody tr').remove();
    $.each(companies, function (i, company) {
        let item = '<tr>\n' +
            '           <td>'+ company.id +'</td>\n' +
            '           <td>'+ company.name +'</td>\n' +
            '       </tr>';
        $('#customer-not-sale-table tbody').append(item);
    });

    reLoadGrid();
}
