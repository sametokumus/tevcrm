(function($) {
    "use strict";

	 $(document).ready(function() {

         $(":input").inputmask();
         $("#add_expense_price").maskMoney({thousands:'.', decimal:','});

         $('#add_document_form').submit(function (e){
             e.preventDefault();
             addMobileDocument();
         });

         $('#add_expense_form').submit(function (e){
             e.preventDefault();
             addExpense();
         });

	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        let sale_id = getPathVariable('sale-detail');
        initSaleStats(sale_id);
        initSaleSummary(sale_id);
        initDocuments(sale_id);
        initExpenses(sale_id);

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

async function generatePDF(){
    let sale_id = getPathVariable('sale-detail');

    // Fetch the PDF data
    const pdfData = await serviceGetGenerateSaleSummaryPDF(sale_id);

    // Create a link element to download the PDF
    const link = document.createElement('a');
    link.href = `${pdfData.object.file_url}`;
    link.target = '_blank';
    link.download = `${pdfData.object.file_name}`;
    link.textContent = 'Download PDF';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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
            '           <td class="text-right">\n' +
            '               <span class="badge bg-theme text-theme-900 bg-opacity-50 rounded-0 pt-5px" style="min-height: 18px">'+ previous_status_name +'</span>\n' +
            '           </td>\n' +
            '           <td>\n' +
            '               <i class="bi bi-arrow-90deg-right"></i>\n' +
            '               <span class="badge bg-theme text-white rounded-0 pt-5px" style="min-height: 18px">'+ action.last_status.status_name +'</span>\n' +
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


    $('#sale-code').text(sale.owner.short_code+'-'+sale.id);
    sale_global_id = sale.id;
    let total = '-';
    if (sale.grand_total != null){
        total = changeCommasToDecimal(sale.grand_total) + ' ' + sale.currency;
    }
    if (sale.grand_total_with_shipping != null){
        total = changeCommasToDecimal(sale.grand_total_with_shipping) + ' ' + sale.currency;
    }

    $('#customer-name').append('<a href="/company-detail/'+sale.request.company.id+'" class="text-decoration-none text-dark">'+sale.request.company.name+'</a>');
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

    $('#product-count').append(sale.product_count);
    $('#product-total-count').append('Toplam Ürün Adedi: '+sale.total_product_count);

    $('#sale-date').append(formatDateAndTimeDESC(sale.created_at, '/'));

    // $('#total-sale').text(stats.total_sale);
    // $('#active-sale').text(stats.active_sale);
    // $('#total-product').text(stats.total_product);


}

async function initSaleSummary(sale_id){
    let data = await serviceGetSaleSummary(sale_id);
    let sale = data.sale;
    console.log(sale)

    let admin_id = await localStorage.getItem('userId');
    let control = await serviceGetCheckAdminRolePermission(admin_id, 20);


    if (control.permission) {
        $('#profit-rate-message').html('<span class="text-theme"><b>Karlılık: %' + sale.profit_rate + '</b></span>');
        let header_btn = '<button class="btn btn-theme btn-sm" type="button" onclick="generatePDF();"><span>Sipariş Özeti</span></button>';
        $('#header-btn').append(header_btn);

        let currency = sale.currency;
        let total_price = sale.grand_total;
        if (sale.grand_total_with_shipping != null){
            total_price = sale.grand_total_with_shipping;
        }

        let item1 = '<tr>\n' +
            '            <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   <b>SATIŞ TUTARI</b>\n' +
            '               </span>\n' +
            '            </td>\n' +
            '            <td>'+ changeCommasToDecimal(total_price) +' '+ currency +'</td>\n' +
            '        </tr>';

        $('#sale-summary-table tbody').append(item1);

        let item2 = '<tr>\n' +
            '            <td colspan="2">\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   <b>TEDARİK GİDERLERİ</b>\n' +
            '               </span>\n' +
            '            </td>\n' +
            '        </tr>';

        $('#sale-summary-table tbody').append(item2);



        let data = await serviceGetSaleSuppliers(sale_id);
        let offers = data.offers;

        $.each(offers, function (i, offer) {
            let offer_item = '<tr>\n' +
                '                 <td>\n' +
                '                    <span class="d-flex align-items-center px-5">\n' +
                '                        <b>'+ offer.supplier.name +' ('+ sale.owner.short_code + '-PO-'+ sale.id +')</b>\n' +
                '                    </span>\n' +
                '                 </td>\n' +
                '                 <td>'+ changeCommasToDecimal(offer.converted_price) +' '+ currency +'</td>\n' +
                '             </tr>';

            $('#sale-summary-table tbody').append(offer_item);
        });


        let item3 = '<tr>\n' +
            '            <td colspan="2">\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   <b>EK GİDERLER</b>\n' +
            '               </span>\n' +
            '            </td>\n' +
            '        </tr>';

        $('#sale-summary-table tbody').append(item3);

        $.each(sale.expenses, function (i, expense) {
            let offer_item = '<tr>\n' +
                '                 <td>\n' +
                '                    <span class="d-flex align-items-center px-5">\n' +
                '                        <b>'+ expense.category_name +'</b>\n' +
                '                    </span>\n' +
                '                 </td>\n' +
                '                 <td>'+ changeCommasToDecimal(expense.converted_price) +' '+ currency +'</td>\n' +
                '             </tr>';

            $('#sale-summary-table tbody').append(offer_item);
        });

        let item4 = '<tr>\n' +
            '            <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   <b>TOPLAM GİDER</b>\n' +
            '               </span>\n' +
            '            </td>\n' +
            '            <td>'+ changeCommasToDecimal(sale.total_expense) +' '+ currency +'</td>\n' +
            '        </tr>';

        $('#sale-summary-table tbody').append(item4);

        let item5 = '<tr>\n' +
            '            <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   <b>KAR</b>\n' +
            '               </span>\n' +
            '            </td>\n' +
            '            <td>'+ changeCommasToDecimal(parseFloat(parseFloat(total_price) - parseFloat(sale.total_expense)).toFixed(2)) +' '+ currency +'</td>\n' +
            '        </tr>';

        $('#sale-summary-table tbody').append(item5);

        let item6 = '<tr>\n' +
            '            <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   <b>KAR ORANI</b>\n' +
            '               </span>\n' +
            '            </td>\n' +
            '            <td>%'+ sale.profit_rate +'</td>\n' +
            '        </tr>';

        $('#sale-summary-table tbody').append(item6);



    }

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
                '               <a href="/company-detail/' + offer.supplier_id + '" class="text-decoration-none text-dark"><i class="bi bi-search"></i></a>\n' +
                '               <a href="/purchasing-order-print/' + sale_id + '" class="text-decoration-none text-dark"><i class="bi bi-file-pdf-fill"></i></a>\n' +
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

            let item = '<div class="col-xl-3 col-lg-6">\n';
        if (document.file_url != null) {
            item += '<a href="' + document.file_url + '" target="_blank" class="btn btn-theme d-block text-decoration-none py-2 mb-3">\n' +
                '        <div class="flex-fill">\n' +
                '            <h5 class="mb-0 text-white"><i class="fa fa-file-pdf"></i> ' + document.name + '</h5>\n' +
                '        </div>\n' +
                '    </a>\n';
        }else{
            item += '<a href="#" target="_blank" class="btn btn-default d-block text-decoration-none py-2 mb-3 pointer-event-none">\n' +
                '        <div class="flex-fill">\n' +
                '            <h5 class="mb-0 text-white"><i class="fa fa-file-pdf"></i> ' + document.name + '</h5>\n' +
                '        </div>\n' +
                '    </a>\n';
        }
            item += '</div>';
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
                colors: ['#000000bf', '#000000bf']
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
                colors: ['#000000bf', '#000000bf'],
                opacity: 0.1
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-selling-process"), options);
    chart.render();


}

async function initExpenses(sale_id){
    let data = await serviceGetSaleExpenseById(sale_id);
    $("#expenses-datatable").dataTable().fnDestroy();
    $('#expenses-datatable tbody > tr').remove();

    $.each(data.expenses, function (i, expense) {
        let item = '<tr>\n' +
            '              <td>'+ expense.id +'</td>\n' +
            '              <td>'+ expense.category_name +'</td>\n' +
            '              <td>'+ changeCommasToDecimal(expense.price) +'</td>\n' +
            '              <td>'+ expense.currency +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteSaleExpense(\''+ expense.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#expenses-datatable tbody').append(item);
    });

    $('#expenses-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Gider Ekle ve Güncelle',
                className: 'btn btn-theme',
                action: function ( e, dt, node, config ) {
                    openAddExpenseModal();
                }
            }
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function deleteSaleExpense(expense_id){
    let returned = await serviceGetDeleteSaleExpense(expense_id);
    if(returned){
        let sale_id = getPathVariable('sale-detail');
        initExpenses(sale_id);
    }
}

async function openAddExpenseModal(){
    await getExpenseCategoriesAddSelectId('add_expense_category');
    $('#addExpenseModal').modal('show');
    initExpenseToModal();
}

async function initExpenseToModal(){
    let sale_id = getPathVariable('sale-detail');
    let category_id = document.getElementById('add_expense_category').value;

    let data = await serviceGetSaleExpenseByCategoryId(sale_id, category_id);
    console.log(data)

    if (data.expenses != null){
        document.getElementById('add_expense_price').value = changeCommasToDecimal(data.expenses.price);
        document.getElementById('add_expense_currency').value = data.expenses.currency;
    }else{
        document.getElementById('add_expense_price').value = "";
        document.getElementById('add_expense_currency').value = "TRY";
    }
}

async function addExpense() {
    let sale_id = getPathVariable('sale-detail');
    let category_id = document.getElementById('add_expense_category').value;
    let price = document.getElementById('add_expense_price').value;
    let currency = document.getElementById('add_expense_currency').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "category_id": category_id,
        "price": changePriceToDecimal(price),
        "currency": currency
    });
    console.log(formData)

    let returned = await servicePostAddSaleExpense(formData);
    if (returned){
        // $('#addExpenseModal').modal('hide');
        initExpenses(sale_id);
    }
}
