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
        getCashFlowPayments();

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

    $('#total-box h4').append(changeCommasToDecimal(stats.total.try_sale) + ' TRY');
    let text1 = '<i class="fa fa-dollar-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.total.usd_sale) +'<br/>\n' +
        '       <i class="fa fa-euro-sign fa-fw me-1"></i> '+ changeCommasToDecimal(stats.total.eur_sale);
    $('#total-box .other-currencies').append(text1);

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
    let min_date = new Date();
    let max_date = new Date();

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

            let dueDate = new Date(payment.due_date);
            if (dueDate < min_date) {
                min_date = dueDate;
            }
            if (dueDate > max_date) {
                max_date = dueDate;
            }

            let date = formatDateASC(payment.due_date, '-');
            let price = payment.payment_price;
            if (payment.currency == 'USD'){
                price = parseFloat(price * payment.sale.usd_rate).toFixed(2);
            }
            if (payment.currency == 'EUR'){
                price = parseFloat(price * payment.sale.eur_rate).toFixed(2);
            }
            let tax = payment.payment_tax;
            if (tax == null){ tax = 0; }
            if (payment.currency == 'USD'){
                tax = parseFloat(tax * payment.sale.usd_rate).toFixed(2);
            }
            if (payment.currency == 'EUR'){
                tax = parseFloat(tax * payment.sale.eur_rate).toFixed(2);
            }
            let total = parseFloat(price) + parseFloat(tax);
            let data = {
                "x": date,
                "y": price,
                "z": 14,
                "title": payment.sale.owner.short_code + "-" + payment.sale.id,
                "tax": tax,
                "total": total
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
                '                           <div class="text-white"><b>Tutar:</b> '+ changeCommasToDecimal(payment.payment_price) +' '+ payment.currency +'</div>\n' +
                '                           <div class="text-white"><b>KDV:</b> '+ changeCommasToDecimal(payment.payment_tax) +' '+ payment.currency +'</div>\n' +
                '                           <div class="text-white"><b>Toplam Tutar:</b> '+ changeCommasToDecimal(payment.payment_total) +' '+ payment.currency +'</div>\n' +
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
            z: item.z,
            title: item.title,
            tax: item.tax,
            total: item.total
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
            z: item.z,
            title: item.title,
            tax: item.tax,
            total: item.total
        };
    });

    console.log(processedPendingArray)
    console.log(processedLateArray)

    min_date.setDate(min_date.getDate() - 3);
    min_date = min_date.toISOString().split('T')[0];
    max_date.setDate(max_date.getDate() + 3);
    max_date = max_date.toISOString().split('T')[0];

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
        },
        colors: ['rgba(254, 176, 25, 0.85)', 'rgba(255, 69, 96, 0.85)'],
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
            },
            min: new Date(min_date).getTime(),
            max: new Date(max_date).getTime()
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
                var label_title = options.series[seriesIndex].data[dataPointIndex].title;
                var label_tax = options.series[seriesIndex].data[dataPointIndex].tax;
                var label_total = options.series[seriesIndex].data[dataPointIndex].total;
                return (
                    '<div class="tooltip-custom">' +
                        '<div class="tooltip-custom-header">'+
                            options.series[seriesIndex].name +
                        '</div>'+
                        '<div class="tooltip-custom-content">'+
                            '<p> Tarih: ' +
                            label_x +
                            '</p>' +
                            '<p> Sipariş No: ' +
                            label_title +
                            '</p>' +
                            '<p>Tutar: ' +
                            changeCommasToDecimal(label_y) + ' TRY' +
                            '</p>' +
                            '<p>KDV: ' +
                            changeCommasToDecimal(label_tax) + ' TRY' +
                            '</p>' +
                            '<p>Toplam Tutar: ' +
                            changeCommasToDecimal(label_total) + ' TRY' +
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

async function getCashFlowPayments(){

    let data = await serviceGetCashFlowPayments();
    let payments = data.payments;

    $("#payments-datatable").dataTable().fnDestroy();
    $('#payments-datatable tbody > tr').remove();

    $.each(data.payments, function (i, payment) {

        let row_status_class = "";
        let status_class = "";
        let status_name = "";
        let order = 0;

        if (payment.payment_status_id == 2){
            row_status_class = "";
            status_class = "border-theme text-theme";
            status_name = "Ödeme Yapıldı";
            order = 3;
        }else if (payment.payment_status_id == 1){
            if (payment.date_status){
                row_status_class = "";
                status_class = "border-warning text-warning";
                status_name = "Ödeme Bekleniyor";
                order = 2;
            }else{
                row_status_class = "bg-danger";
                status_class = "border-danger text-danger";
                status_name = "Ödeme Gecikmede";
                order = 1;
            }
        }

        let status = '<span class="badge border '+ status_class +' px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"><i class="fa fa-circle fs-9px fa-fw me-5px"></i> '+ status_name +'</span>';

        let price = changeCommasToDecimal(payment.payment_price);
        let tax = changeCommasToDecimal(payment.payment_tax);
        let total_price = changeCommasToDecimal(payment.payment_total);

        let item = '<tr class="'+ row_status_class +'">\n' +
            '              <td class="bg-dark">'+ (i+1)+'</td>\n' +
            '              <td class="bg-dark">'+ payment.sale.owner.short_code +'-'+ payment.sale.id +'</td>\n' +
            '              <td class="bg-dark">'+ payment.sale.customer.name +'</td>\n' +
            '              <td class="bg-dark">'+ status +'</td>\n' +
            '              <td class="bg-dark">'+ payment.date_message +'</td>\n' +
            '              <td>'+ payment.payment_type_name +'</td>\n' +
            '              <td>'+ payment.payment_method_name +'</td>\n' +
            '              <td>'+ formatDateASC(payment.due_date, "-") +'</td>\n' +
            '              <td>'+ price +'</td>\n' +
            '              <td>'+ tax +'</td>\n' +
            '              <td>'+ total_price +'</td>\n' +
            '              <td>'+ checkNull(payment.currency) +'</td>\n' +
            '              <td class="d-none">'+ order +'</td>\n' +
            '          </tr>';
        $('#payments-datatable tbody').append(item);
    });

    $('#payments-datatable').DataTable({
        responsive: false,
        columnDefs: [
            {
                targets: 2,
                className: 'ellipsis',
                render: function(data, type, row, meta) {
                    return type === 'display' && data.length > 30 ?
                        data.substr(0, 30) + '...' :
                        data;
                }
            },
            {
                type: 'date',
                targets: 7
            }
        ],
        dom: 'Bfrtip',
        paging: false,
        buttons: [
        ],
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: [[10, 'asc']],
        fixedColumns: {
            left: 5
        }
    });

}




