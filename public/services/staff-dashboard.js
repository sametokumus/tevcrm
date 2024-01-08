(function($) {
    "use strict";

    $(document).ready(function() {

        $('#update-profile-button').click(function (){
            openUpdateProfileModal();
        });

        $('#update_account_form').submit(function (e){
            e.preventDefault();
            updateProfile();
        });

    });

    $(window).load(async function() {

        checkLogin();

        initStats();
        initStaffs();

    });

})(window.jQuery);

async function initStaffs(){
    let data = await serviceGetAllStaffStatistics();
    let staffs = data.staffs;
    console.log(staffs)

    $.each(data.staffs, function (i, staff) {

        let profile_photo = '/img/user/null-profile-picture.png';
        if (staff.staff.profile_photo != null && staff.staff.profile_photo != ''){
            profile_photo = staff.staff.profile_photo;
        }

        let item = '';
        item += '<div class="card mb-3">\n' +
            '        <div class="card-body p-0">\n' +
            '\n' +
            '            <div class="profile">\n' +
            '\n' +
            '                <div class="profile-container">\n' +
            '\n' +
            '                    <div class="profile-sidebar">\n' +
            '                        <div class="desktop-sticky-top">\n' +
            '                            <div class="profile-img">\n' +
            '                                <img src="'+ profile_photo +'" alt="">\n' +
            '                            </div>\n' +
            '                            <h4>'+ staff.staff.name +' '+ staff.staff.surname +'</h4>\n' +
            '                            <div class="mb-1"><i class="fa fa-phone fa-fw text-inverse text-opacity-50"></i> '+ staff.staff.phone_number +'</div>\n' +
            '                            <div class="mb-0"><i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i> '+ staff.staff.email +'</div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '\n' +
            '\n' +
            '                    <div class="profile-content">\n' +
            '                        <ul class="profile-tab nav nav-tabs nav-tabs-v2" role="tablist">\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <div class="nav-field">Toplam Müşteri</div>\n' +
            '                                    <div class="nav-value" id="stat-1">'+ staff.total_company_count +'</div>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <div class="nav-field">Eklenen Müşteri</div>\n' +
            '                                    <div class="nav-value" id="stat-2">'+ staff.add_this_month_company +'</div>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <div class="nav-field">Yapılan Görüşme</div>\n' +
            '                                    <div class="nav-value" id="stat-3">'+ staff.activity_this_month +'</div>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <div class="nav-field">Toplam Teklif</div>\n' +
            '                                    <div class="nav-value" id="stat-4">'+ staff.request_this_month +'</div>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <div class="nav-field">Toplam Sipariş</div>\n' +
            '                                    <div class="nav-value" id="stat-5">'+ staff.sale_this_month +'</div>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <div class="nav-field">Sıralama ve Puan</div>\n' +
            '                                    <div class="nav-value" id="stat-6">'+ (i+1) +'. ('+ staff.staff_rate +')</div>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <div class="nav-field">Yönetici Puanı</div>\n' +
            '                                    <div class="nav-value" id="stat-6">'+ staff.manager_point +'</div>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                            <li class="nav-item">\n' +
            '                                <a href="#" class="nav-link" style="pointer-events:none;">\n' +
            '                                    <button class="nav-btn btn btn-theme">Hedef Ekle</button>\n' +
            '                                    <button class="nav-btn btn btn-theme">Yönetici Puanı</button>\n' +
            '                                </a>\n' +
            '                            </li>\n' +
            '                        </ul>\n' +
            '                        <div class="profile-content-container">\n' +
            '                            <div class="row gx-4">\n' +
            '                                <div class="col-xl-12">\n' +
            '                                    <div class="p-0">\n' +
            '                                        <div class="row">\n' +
            '                                            <div class="col-md-12">\n' +
            '                                                <div class="card mb-3">\n' +
            '                                                    <div class="card-body">\n' +
            '                                                        <div class="d-flex fw-bold small mb-3">\n' +
            '                                                            <span class="flex-grow-1">SATIŞ HEDEFLERİ</span>\n' +
            '                                                            <a href="#" data-toggle="card-expand"\n' +
            '                                                               class="text-white text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>\n' +
            '                                                        </div>\n' +
            '                                                        <table id="targets-datatable-'+ i +'" class="table table-bordered nowrap key-buttons border-bottom">\n' +
            '                                                            <thead>\n' +
            '                                                            <tr>\n' +
            '                                                                <th class="border-bottom-0">Tür</th>\n' +
            '                                                                <th class="border-bottom-0">Hedef</th>\n' +
            '                                                                <th class="border-bottom-0">Ay</th>\n' +
            '                                                                <th class="border-bottom-0">Yıl</th>\n' +
            '                                                                <th class="border-bottom-0">Durum</th>\n' +
            '                                                            </tr>\n' +
            '                                                            </thead>\n' +
            '                                                            <tbody>\n';
        $.each(staff.targets, function (i, target) {
            item += '                                                       <tr>\n' +
                '                                                                <td>'+ target.type_name +'</td>\n' +
                '                                                                <td>'+ changeCommasToDecimal(target.target) +' '+ target.currency +'</td>\n' +
                '                                                                <td>'+ target.month_name +'</td>\n' +
                '                                                                <td>'+ target.year +'</td>\n' +
                '                                                                <td>\n' +
                '                                                                    <div class="progress">\n' +
                '                                                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: '+ parseInt(target.status.rate) +'%"><span>'+ target.status.rate +'%</span></div>\n' +
                '                                                                    </div>\n' +
                '                                                                </td>\n' +
                '                                                            </tr>\n';
        });

            item += '                                                    </tbody>\n' +
            '                                                        </table>\n' +
            '\n' +
            '\n' +
            '\n' +
            '                                                    </div>\n' +
            '\n' +
            '\n' +
            '                                                    <div class="card-arrow">\n' +
            '                                                        <div class="card-arrow-top-left"></div>\n' +
            '                                                        <div class="card-arrow-top-right"></div>\n' +
            '                                                        <div class="card-arrow-bottom-left"></div>\n' +
            '                                                        <div class="card-arrow-bottom-right"></div>\n' +
            '                                                    </div>\n' +
            '\n' +
            '                                                </div>\n' +
            '\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '\n' +
            '                </div>\n' +
            '\n' +
            '            </div>\n' +
            '\n' +
            '        </div>\n' +
            '        <div class="card-arrow">\n' +
            '            <div class="card-arrow-top-left"></div>\n' +
            '            <div class="card-arrow-top-right"></div>\n' +
            '            <div class="card-arrow-bottom-left"></div>\n' +
            '            <div class="card-arrow-bottom-right"></div>\n' +
            '        </div>\n' +
            '    </div>';
        item += '';

        $('#content #staffs').append(item);

        $('#targets-datatable-' + i).DataTable({
            responsive: false,
            columnDefs: [],
            dom: 'Brtip',
            paging: false,
            buttons: [],
            scrollX: true,
            language: {
                url: "services/Turkish.json"
            },
            order: false,
        });

    });

}

async function initStats(){
    let data = await serviceGetAllStaffStatisticsMonthly();
    let this_year_target = data.this_year_target;
    let this_month_target = data.this_month_target;
    let monthly_targets = data.monthly_targets;
    console.log(this_year_target)

    $('#yearly-result').html('HEDEF: '+ this_year_target.target +' TRY <br>SATIŞ: '+ this_year_target.year_total_price +' TRY');
    $('#monthly-result').html('HEDEF: '+ this_month_target.target +' TRY <br>SATIŞ: '+ this_month_target.month_total_price +' TRY');

    //yearly chart
    let yearlyLabelsArray = [];
    let yearlySeriesArray = [];

    $.each(this_year_target.staffs, function (i, staff) {
        if (staff.staff_sales != "0.00") {
            yearlyLabelsArray.push(staff.name + ' ' + staff.surname);
            yearlySeriesArray.push(parseFloat(staff.staff_sales));
        }
    });

    let yearlyChartOptions = {
        chart: {
            height: 365,
            type: 'pie',
        },
        dataLabels: {
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                opacity: 1
            }
        },
        stroke: { show: false },
        colors: [ 'rgba('+ app.color.pinkRgb +', .75)',  'rgba('+ app.color.warningRgb +', .75)',  'rgba('+app.color.themeRgb +', .75)', 'rgba('+ app.color.bodyColorRgb + ', .5)',  'rgba('+app.color.indigoRgb +', .75)'],
        labels: yearlyLabelsArray,
        series: yearlySeriesArray,
        title: { text: '' },
        tooltip: {
            y: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2)) + ' TRY'
                }
            }
        }
    };
    let yearlyChart = new ApexCharts(
        document.querySelector('#chart-yearly-result'),
        yearlyChartOptions
    );
    yearlyChart.render();

    //monthly chart
    let monthlyLabelsArray = [];
    let monthlySeriesArray = [];

    $.each(this_month_target.staffs, function (i, staff) {
        if (staff.staff_sales != "0.00") {
            monthlyLabelsArray.push(staff.name + ' ' + staff.surname);
            monthlySeriesArray.push(parseFloat(staff.staff_sales));
        }
    });

    let monthlyChartOptions = {
        chart: {
            height: 365,
            type: 'pie',
        },
        dataLabels: {
            dropShadow: {
                enabled: false,
                top: 1,
                left: 1,
                blur: 1,
                opacity: 1
            }
        },
        stroke: { show: false },
        colors: [ 'rgba('+ app.color.pinkRgb +', .75)',  'rgba('+ app.color.warningRgb +', .75)',  'rgba('+app.color.themeRgb +', .75)', 'rgba('+ app.color.bodyColorRgb + ', .5)',  'rgba('+app.color.indigoRgb +', .75)'],
        labels: monthlyLabelsArray,
        series: monthlySeriesArray,
        title: { text: '' },
        tooltip: {
            y: {
                formatter: function (val) {
                    return changeCommasToDecimal(val.toFixed(2)) + ' TRY'
                }
            }
        }
    };
    let monthlyChart = new ApexCharts(
        document.querySelector('#chart-monthly-result'),
        monthlyChartOptions
    );
    monthlyChart.render();


    //all months chart
    let xAxisArray = [];
    let yAxisArrayTarget = [];
    let yAxisArraySale = [];

    $.each(monthly_targets, function (i, data) {
        xAxisArray.push(data.month);
        yAxisArrayTarget.push(data.target);
        yAxisArraySale.push(data.month_total_price);
    });

    let apexColumnChartOptions = {
        chart: {
            height: 200,
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
                columnWidth: '80%',
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
        colors: ['#ff4560d9', '#90ee7e'],
        series: [
            {
                name: "Hedef",
                data: yAxisArrayTarget
            },
            {
                name: "Satış",
                data: yAxisArraySale
            }
        ],
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
                    return changeCommasToDecimal(val.toFixed(2)) + ' TRY'
                }
            }
        }
    };
    var apexColumnChart = new ApexCharts(
        document.querySelector('#chart-yearly-targets'),
        apexColumnChartOptions
    );
    apexColumnChart.render();

}

async function initAdmin(user_id){
    let data = await serviceGetAdminById(user_id);
    let admin = data.admin;
    console.log(admin)
    $('#staff-name').text(admin.name + ' ' + admin.surname);
    $('#staff-email').html('<i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i>' + admin.email);
    $('#staff-phone').html('<i class="fa fa-phone fa-fw text-inverse text-opacity-50"></i>' + admin.phone_number);

    let profile_photo = '/img/user/null-profile-picture.png';
    if (admin.profile_photo != null && admin.profile_photo != ''){
        profile_photo = admin.profile_photo;
    }
    $('#staff-image').attr('src', profile_photo);
}

async function initStaffTargets(user_id){

    let data = await serviceGetStaffTargetsByStaffId(user_id);

    console.log(data)
    $("#targets-datatable").dataTable().fnDestroy();
    $('#targets-datatable tbody > tr').remove();

    $.each(data.targets, function (i, target) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateStaffTargetModal(\''+ target.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteStaffTarget(\''+ target.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th>'+ target.id +'</th>\n' +
            // '           <td>'+ target.admin.name +' '+ target.admin.surname +'</td>\n' +
            '           <td>'+ target.type_name +'</td>\n' +
            '           <td>'+ changeCommasToDecimal(target.target) +' '+ target.currency +'</td>\n' +
            '           <td>'+ target.month_name +'</td>\n' +
            '           <td>'+ target.year +'</td>\n' +
            // '           <td>'+ target.status.rate +'%</td>\n' +
            '           <td>\n' +
            '               <div class="progress">\n' +
            '                   <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: '+ parseInt(target.status.rate) +'%">'+ target.status.rate +'%</div>\n' +
            '               </div>\n' +
            '           </td>\n' +
            '       </tr>';
        $('#targets-datatable tbody').append(item);
    });

    $('#targets-datatable').DataTable({
        responsive: false,
        columnDefs: [],
        dom: 'Bfrtip',
        paging: false,
        buttons: [],
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: false,
    });
}

async function initStaffStats(user_id){

    let data = await serviceGetStaffStatistics(user_id);

    console.log(data)

    $('#stat-1').append(data.total_company_count);
    $('#stat-2').append(data.add_this_month_company);
    $('#stat-3').append(data.activity_this_month);
    $('#stat-4').append(data.request_this_month);
    $('#stat-5').append(data.sale_this_month);

    let data2 = await serviceGetStaffSituation(user_id);

    console.log(data2)
    $('#stat-6').append(data2.position + '. (' + data2.staff.staff_rate + ')');

}
