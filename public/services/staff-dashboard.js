(function($) {
    "use strict";

    $(document).ready(function() {
        $(":input").inputmask();
        $("#add_target_target").maskMoney({thousands:'.', decimal:','});
        $("#update_target_target").maskMoney({thousands:'.', decimal:','});

        $('#add_staff_target_form').submit(function (e){
            e.preventDefault();
            addStaffTarget();
        });
        $('#update_staff_target_form').submit(function (e){
            e.preventDefault();
            updateStaffTarget();
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

    $('#content #staffs *').remove();

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
            '                                <div class="nav-link">\n' +
            '                                    <button class="nav-btn btn btn-theme btn-sm d-block w-150px mb-1" onclick="openAddStaffTargetModal('+ staff.staff.id +');">Hedef Ekle</button>\n' +
            '                                    <button class="nav-btn btn btn-theme btn-sm d-block w-150px" onclick="openAddPointModal('+ staffstaff.staff.id +');">Yönetici Puanı Ekle</button>\n' +
            '                                </div>\n' +
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


function addTargetChangeType(){
    let type_id = document.getElementById('add_target_type_id').value;
    if (type_id == 3){
        document.getElementById('add_target_currency').value = '%';
        $('#add_target_currency').attr('disabled', 'disabled');
    }else{
        $('#add_target_currency').removeAttr('disabled');
    }
}

function updateTargetChangeType(){
    let type_id = document.getElementById('update_target_type_id').value;
    if (type_id == 3){
        document.getElementById('update_target_currency').value = '%';
        $('#update_target_currency').attr('disabled', 'disabled');
    }else{
        $('#update_target_currency').removeAttr('disabled');
    }
}

async function openAddStaffTargetModal(staff_id){
    await getStaffTargetTypesAddSelectId('add_target_type_id');
    document.getElementById('add_target_staff_id').value = staff_id;
    $("#addStaffTargetModal").modal('show');
}
async function addStaffTarget(){
    let admin_id = document.getElementById('add_target_staff_id').value;
    let type_id = document.getElementById('add_target_type_id').value;
    let target = changePriceToDecimal(document.getElementById('add_target_target').value);
    let currency = document.getElementById('add_target_currency').value;
    let month = document.getElementById('add_target_month').value;
    let year = document.getElementById('add_target_year').value;


    let formData = JSON.stringify({
        "admin_id": admin_id,
        "type_id": type_id,
        "target": target,
        "currency": currency,
        "month": month,
        "year": year
    });

    console.log(formData);

    let returned = await servicePostAddStaffTarget(formData);
    if (returned){
        $("#add_staff_target_form").trigger("reset");
        initStaffs();
    }else{
        alert("Hata Oluştu");
    }
}


async function openUpdateStaffTargetModal(target_id){
    await getStaffTargetTypesAddSelectId('update_target_type_id');
    $("#updateStaffTargetModal").modal('show');
    initUpdateStaffTargetModal(target_id)
}
async function initUpdateStaffTargetModal(target_id){
    document.getElementById('update_staff_target_form').reset();
    let data = await serviceGetStaffTargetById(target_id);
    let target = data.target;
    document.getElementById('update_target_id').value = target.id;
    document.getElementById('update_target_type_id').value = target.type_id;
    document.getElementById('update_target_target').value = changeCommasToDecimal(target.target);
    document.getElementById('update_target_currency').value = target.currency;
    document.getElementById('update_target_month').value = target.month;
    document.getElementById('update_target_year').value = target.year;
}
async function updateStaffTarget(){
    let id = document.getElementById('update_target_id').value;
    let type_id = document.getElementById('update_target_type_id').value;
    let target = changePriceToDecimal(document.getElementById('update_target_target').value);
    let currency = document.getElementById('update_target_currency').value;
    let month = document.getElementById('update_target_month').value;
    let year = document.getElementById('update_target_year').value;


    let formData = JSON.stringify({
        "id": id,
        "type_id": type_id,
        "target": target,
        "currency": currency,
        "month": month,
        "year": year
    });
    let returned = await servicePostUpdateStaffTarget(formData);
    if (returned){
        $("#update_staff_target_form").trigger("reset");
        $("#updateStaffTargetModal").modal('hide');
        initStaffTargets();
    }else{
        alert('Güncelleme yapılırken bir hata oluştu. Lütfen tekrar deneyiniz!');
    }
}

async function deleteStaffTarget(target_id){
    let returned = await serviceGetDeleteStaffTarget(target_id);
    if(returned){
        initStaffTargets();
    }
}
