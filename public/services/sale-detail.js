(function($) {
    "use strict";

	 $(document).ready(function() {

         $('#add_document_form').submit(function (e){
             e.preventDefault();
             addMobileDocument();
         });

	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        let sale_id = getPathVariable('sale-detail');
        initSaleStats(sale_id);
        initDocuments(sale_id);

        initSaleHistory(sale_id);
        initSaleSuppliers(sale_id);
        initSellingProcess(sale_id);

        getDocumentTypesAddSelectId('add_document_type');
        initMobileDocuments();

    });

})(window.jQuery);

function checkRole(){
    return true;
}

async function initSaleHistory(sale_id){
    let data = await serviceGetSaleStatusHistory(sale_id);
    let actions = data.actions;

    $('#status-history-table tbody tr').remove();

    $.each(actions, function (i, action) {
        let last_time = formatDateAndTimeDESC(action.last_status.created_at, "/");

        previous_status_name = action.previous_status.status_name;
        if (action.previous_status == 0){
            previous_status_name = '-';
        }

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ action.last_status.user_name +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td><small>'+ last_time +'</small></td>\n' +
            '           <td>\n' +
            '               <span class="badge bg-white bg-opacity-25 rounded-0 pt-5px" style="min-height: 18px">'+ previous_status_name +'</span>\n' +
            '               <i class="bi bi-arrow-90deg-right"></i>\n' +
            '               <span class="badge bg-theme text-theme-900 rounded-0 pt-5px" style="min-height: 18px">'+ action.last_status.status_name +'</span>\n' +
            '           </td>\n' +
            '       </tr>';

        $('#status-history-table tbody').append(item);
    });
}

let sale_global_id = 0;

async function initSaleStats(sale_id){
    let data = await serviceGetSaleDetailInfo(sale_id);
    let sale = data.sale;
    console.log(sale)

    let admin_id = await localStorage.getItem('userId');
    let control = await serviceGetCheckAdminRolePermission(admin_id, 20);



    $('#sale-code').text(sale.owner.short_code+'-'+sale.id);
    sale_global_id = sale.id;
    let total = '-';
    if (sale.grand_total != null){
        total = changeCommasToDecimal(sale.grand_total) + ' ' + sale.currency;
    }
    if (sale.grand_total_with_shipping != null){
        total = changeCommasToDecimal(sale.grand_total_with_shipping) + ' ' + sale.currency;
    }

    $('#customer-name').append('<a href="/company-detail/'+sale.request.company.id+'" class="text-decoration-none text-white">'+sale.request.company.name+'</a>');
    if (sale.request.company_employee != null) {
        $('#customer-employee').text('Müşteri Yetkilisi: ' + sale.request.company_employee.name);
    }
    $('#owner-employee').append('Firma Yetkilisi: '+sale.request.authorized_personnel.name+' '+sale.request.authorized_personnel.surname);

    $('#total-price').text(total);

    if (sale.remaining_message) {
        let remaining_message = '';
        if (sale.payed_price == '0.00') {
            remaining_message = '<span class="text-danger"><b>Ödeme Bekleniyor.</b></span>';
        } else if (sale.remaining_price == '0.00') {
            remaining_message = '<span class="text-theme"><b>Ödeme Tamamlandı.</b></span>';
        } else {
            remaining_message = '<span class="text-warning"><b>Kısmi Ödeme Yapıldı.</b></span>';
        }
        $('#remaining-message').html(remaining_message);
    }
    if (control.permission) {
        $('#profit-rate-message').html('<span class="text-theme"><b>Karlılık: %' + sale.profit_rate + '</b></span>');
    }
    $('#product-count').append(sale.product_count);
    $('#product-total-count').append('Toplam Ürün Adedi: '+sale.total_product_count);

    $('#sale-date').append(formatDateAndTimeDESC(sale.created_at, '/'));

    // $('#total-sale').text(stats.total_sale);
    // $('#active-sale').text(stats.active_sale);
    // $('#total-product').text(stats.total_product);


}

async function initSaleSuppliers(sale_id){
    let admin_id = await localStorage.getItem('userId');
    let control = await serviceGetCheckAdminRolePermission(admin_id, 6);

    if (control.permission) {
        let data = await serviceGetSaleSuppliers(sale_id);
        let offers = data.offers;
        $('#suppliers-table tbody tr').remove();

        $.each(offers, function (i, offer) {

            let item = '<tr>\n' +
                '           <td>\n' +
                '               <span class="d-flex align-items-center">\n' +
                '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
                '                   ' + offer.supplier.name + '\n' +
                '               </span>\n' +
                '           </td>\n' +
                '           <td><small>' + offer.product_count + ' Ürün</small></td>\n' +
                '           <td><small>' + changeCommasToDecimal(offer.total_price) + ' ' + offer.currency + '+KDV</small></td>\n' +
                '           <td>\n' +
                '               <a href="/company-detail/' + offer.supplier_id + '" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>\n' +
                '               <a href="/purchasing-order-print/' + sale_id + '" class="text-decoration-none text-white"><i class="bi bi-file-pdf-fill"></i></a>\n' +
                '           </td>\n' +
                '       </tr>';

            $('#suppliers-table tbody').append(item);
        });
    }else{
        $('#suppliers-table tbody tr').remove();
        let item = '<tr>\n' +
            '           <td colspan="7">Görüntüleme yetkiniz bulunmamaktadır.</td>\n' +
            '       </tr>';

        $('#suppliers-table tbody').append(item);
    }
}

async function initDocuments(sale_id){
    let data = await serviceGetDocuments(sale_id);

    $.each(data.documents, function (i, document) {

            let item = '<div class="col-xl-3 col-lg-6">\n' +
                '            <div class="card mb-3">\n' +
                '                <div class="card-body d-flex align-items-center text-white m-5px bg-white bg-opacity-15">\n';
        if (document.file_url != null) {
            item += '            <a href="' + document.file_url + '" target="_blank" class="text-white text-decoration-none">' +
                '                    <div class="flex-fill">\n' +
                '                        <h5 class="mb-0"><i class="fa fa-file-pdf"></i> ' + document.name + '</h5>\n' +
                '                    </div>\n' +
                '                </a>\n';
        }else{
            item += '                <div class="flex-fill">\n' +
                '                        <h5 class="mb-0 text-danger"><i class="fa fa-file-pdf"></i> ' + document.name + '</h5>\n' +
                '                    </div>\n';
        }
            item += '            </div>\n' +
                '                <div class="card-arrow">\n' +
                '                    <div class="card-arrow-top-left"></div>\n' +
                '                    <div class="card-arrow-top-right"></div>\n' +
                '                    <div class="card-arrow-bottom-left"></div>\n' +
                '                    <div class="card-arrow-bottom-right"></div>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>';
            $('#documents').append(item);

    });


}

async function initMobileDocuments(){
    let data = await serviceGetMobileDocuments(sale_global_id);
    $("#document-datatable").dataTable().fnDestroy();
    $('#document-datatable tbody > tr').remove();

    $.each(data.documents, function (i, document) {
        let typeItem = '<tr>\n' +
            '              <td>'+ document.id +'</td>\n' +
            '              <td>'+ document.type_name +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="'+ api_url + document.file_url +'" target="_blank" id="bDel" type="button" class="btn  btn-sm btn-warning">\n' +
            '                          <span class="fe fe-search"> </span> Görüntüle\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteMobileDocument(\''+ document.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#document-datatable tbody').append(typeItem);
    });

    $('#document-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            'excel',
            'pdf',
            {
                text: 'Döküman Ekle',
                className: 'btn btn-theme',
                action: function ( e, dt, node, config ) {
                    openAddMobileDocumentModal();
                }
            }
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function openAddMobileDocumentModal(){
    $('#addDocumentModal').modal('show');
}

async function addMobileDocumentCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#add_document_form").trigger("reset");
    $("#addDocumentModal").modal('hide');
    let sale_id = getPathVariable('sale-detail');
    initMobileDocuments();
}
async function addMobileDocument(){

    let formData = new FormData();
    formData.append('document_type_id', document.getElementById('add_document_type').value);
    formData.append('file', document.getElementById('add_document_file').files[0]);
    console.log(formData);

    await servicePostAddMobileDocument(formData, sale_global_id);
}

async function deleteMobileDocument(document_id){
    let returned = await serviceGetDeleteMobileDocument(document_id);
    if(returned){
        let sale_id = getPathVariable('sale-detail');
        initMobileDocuments(sale_id);
    }
}

async function initSellingProcess(sale_id){

    let data = await serviceGetSellingProcess(sale_id);
    let process = data.process;
    console.log(process)

    const processData = [];

    if (process.offer_date != null) {
        let date1 = formatDateDESC(process.request_date, '-');
        let date2 = formatDateDESC(process.offer_date, '-');
        if (process.offer_day == 0){
            let date = new Date(formatDateDESC(process.offer_date, '-'));
            date.setDate(date.getDate() + 1);
            date2 = date.toISOString().split('T')[0];
        }
        let bar1 =
            {
                x: 'Teklif',
                y: [
                    new Date(date1).getTime(),
                    new Date(date2).getTime()
                ],
                fillColor: '#4ecdc4',
            }
        ;
        processData.push(bar1);
    }

    if (process.confirmed_date != null) {
        let date1 = formatDateDESC(process.offer_date, '-');
        let date2 = formatDateDESC(process.confirmed_date, '-');
        if (process.confirmed_day == 0){
            let date = new Date(formatDateDESC(process.confirmed_date, '-'));
            date.setDate(date.getDate() + 1);
            date2 = date.toISOString().split('T')[0];
        }
        let bar2 =
            {
                x: 'Onay',
                y: [
                    new Date(date1).getTime(),
                    new Date(date2).getTime()
                ],
                fillColor: '#90ee7e'
            }
        ;
        processData.push(bar2);
    }

    if (process.completed_date != null) {
        let date1 = formatDateDESC(process.confirmed_date, '-');
        let date2 = formatDateDESC(process.completed_date, '-');
        if (process.completed_day == 0){
            let date = new Date(formatDateDESC(process.completed_date, '-'));
            date.setDate(date.getDate() + 1);
            date2 = date.toISOString().split('T')[0];
        }
        let label3 = 'Satış (Geçen Süre)';
        if (process.is_completed == 1){
            label3 = 'Satış';
        }
        let bar3 =
            {
                x: label3,
                y: [
                    new Date(date1).getTime(),
                    new Date(date2).getTime()
                ],
                fillColor: '#dc9b1c'
            }
        ;
        processData.push(bar3);
    }

    if (process.confirmed_date != null) {
        let date1 = formatDateDESC(process.confirmed_date, '-');
        let date2 = new Date(formatDateDESC(process.confirmed_date, '-'));
        date2.setDate(date2.getDate() + process.lead_time);
        date2 = date2.toISOString().split('T')[0];
        let bar4 =
            {
                x: 'Satış (Teklif Süresi)',
                y: [
                    new Date(date1).getTime(),
                    new Date(date2).getTime()
                ],
                fillColor: '#f9e80d'
            }
        ;
        processData.push(bar4);

        let date3 = formatDateDESC(process.completed_date, '-');
        var a = moment(new Date(date1).getTime())
        var b = moment(new Date(date2).getTime())
        var c = moment(new Date(date3).getTime())
        var diff1 = b.diff(a, 'days')
        var diff2 = c.diff(a, 'days')
        let delivery_message = '';
        if (process.is_completed == 1){
            if (diff1 == diff2){
                delivery_message = '<span class="text-theme"><b>Sipariş gününde teslim edildi.</b></span>';
            }else if (diff1 > diff2){
                delivery_message = '<span class="text-theme"><b>Siparişin son teslim tarihinden '+ (diff1 - diff2) +' gün önce teslim edildi.</b></span>';
            }else{
                delivery_message = '<span class="text-warning"><b>Siparişin son teslim tarihinden '+ (diff2 - diff1) +' gün geç teslim edildi.</b></span>';
            }
        }else{
            if (diff1 == diff2){
                delivery_message = '<span class="text-danger"><b>Bugün siparişin son teslim günü.</b></span>';
            }else if (diff1 > diff2){
                delivery_message = '<span class="text-danger"><b>Siparişin son teslim tarihine '+ (diff1 - diff2) +' gün kaldı.</b></span>';
            }else{
                delivery_message = '<span class="text-danger"><b>Siparişin son teslim tarihinden '+ (diff2 - diff1) +' gün geçti.</b></span>';
            }
        }
        $('#delivery-message').html(delivery_message);

    }


    var options = {
        series: [
            {
                data: processData
            }
        ],
        chart: {
            height: 200,
            type: 'rangeBar'
        },
        plotOptions: {
            bar: {
                horizontal: true,
                distributed: true,
                dataLabels: {
                    hideOverflowingLabels: false
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                var label = opts.w.globals.labels[opts.dataPointIndex]
                var a = moment(val[0])
                var b = moment(val[1])
                var diff = b.diff(a, 'days')
                return label + ': ' + diff + (diff > 1 ? ' gün' : ' gün')
            },
            style: {
                colors: ['#f3f4f5', '#fff']
            }
        },
        xaxis: {
            type: 'datetime'
        },
        yaxis: {
            show: false
        },
        grid: {
            row: {
                colors: ['#f3f4f5', '#fff'],
                opacity: 0.1
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-selling-process"), options);
    chart.render();


}
