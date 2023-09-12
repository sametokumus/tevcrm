(function($) {
    "use strict";

	 $(document).ready(function() {

         $('#add_document_form').submit(function (e){
             e.preventDefault();
             addDocument();
         });

	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        let sale_id = getPathVariable('sale-detail');
        initSaleStats(sale_id);
        initSaleHistory(sale_id);
        initSaleSuppliers(sale_id);
        initSellingProcess(sale_id);

        getDocumentTypesAddSelectId('add_document_type');
        initDocuments();

    });

})(window.jQuery);

function checkRole(){
    return true;
}

async function initSaleHistory(sale_id){
    let data = await serviceGetSaleStatusHistory(sale_id);
    console.log(data)
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

async function initSaleStats(sale_id){
    let data = await serviceGetSaleDetailInfo(sale_id);
    let sale = data.sale;
    console.log(sale)
    let total = '-';
    if (sale.grand_total != null){
        total = changeCommasToDecimal(sale.grand_total) + ' ' + sale.currency;
    }
    if (sale.grand_total_with_shipping != null){
        total = changeCommasToDecimal(sale.grand_total_with_shipping) + ' ' + sale.currency;
    }

    $('#customer-name').append('<a href="/company-detail/'+sale.request.company.id+'" class="text-decoration-none text-white">'+sale.request.company.name+'</a>');
    $('#customer-employee').append('Müşteri Yetkilisi: '+sale.request.company_employee.name);
    $('#owner-employee').append('Firma Yetkilisi: '+sale.request.authorized_personnel.name+' '+sale.request.authorized_personnel.surname);

    $('#total-price').text(total);

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
        console.log(offers)
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
                '           </td>\n' +
                '       </tr>';

            $('#suppliers-table tbody').append(item);
        });
    }else{
        $('#suppliers-table tbody tr').remove();
        let item = '<tr>\n' +
            '           <td colspan="7">Görüntüleme yetkini bulunmamaktadır.</td>\n' +
            '       </tr>';

        $('#suppliers-table tbody').append(item);
    }
}

async function initDocuments(){
    let sale_id = getPathVariable('mobile-documents');
    let data = await serviceGetMobileDocuments(sale_id);
    console.log(data)
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
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteDocument(\''+ document.id +'\')">\n' +
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
                    openAddDocumentModal();
                }
            }
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function openAddDocumentModal(){
    $('#addDocumentModal').modal('show');
}

async function addDocumentCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#add_document_form").trigger("reset");
    $("#addDocumentModal").modal('hide');
    initDocuments();
}

async function addDocument(){
    let sale_id = getPathVariable('mobile-documents');

    let formData = new FormData();
    formData.append('document_type_id', document.getElementById('add_document_type').value);
    formData.append('file', document.getElementById('add_document_file').files[0]);
    console.log(formData);

    await servicePostAddMobileDocument(formData, sale_id);
}

async function deleteDocument(document_id){
    let returned = await serviceGetDeleteMobileDocument(document_id);
    if(returned){
        initDocuments();
    }
}

async function initSellingProcess(){

    let data = await serviceGetSellingProcess();
    console.log(data)
    // let sales = data.sales.reverse();
    //
    // let xAxisArray = [];
    // let yAxisArray = [];
    //
    // $.each(sales, function (i, sale) {
    //     xAxisArray.push(sale.month + "/" + sale.year);
    //
    //     if (dash_currency == 'TRY'){
    //         yAxisArray.push(sale.try_sale);
    //     }else if (dash_currency == 'USD'){
    //         yAxisArray.push(sale.usd_sale);
    //     }else if (dash_currency == 'EUR'){
    //         yAxisArray.push(sale.eur_sale);
    //     }
    // });
    //
    // let apexColumnChartOptions = {
    //     chart: {
    //         height: 350,
    //         type: 'bar'
    //     },
    //     title: {
    //         style: {
    //             fontSize: '14px',
    //             fontWeight: 'bold',
    //             fontFamily: FONT_FAMILY,
    //             color: COLOR_WHITE
    //         },
    //     },
    //     legend: {
    //         fontFamily: FONT_FAMILY,
    //         labels: {
    //             colors: '#fff'
    //         }
    //     },
    //     plotOptions: {
    //         bar: {
    //             horizontal: false,
    //             columnWidth: '20%',
    //             endingShape: 'rounded'
    //         },
    //     },
    //     dataLabels: {
    //         enabled: false
    //     },
    //     stroke: {
    //         show: true,
    //         width: 2,
    //         colors: ['transparent']
    //     },
    //     colors: ['#90ee7e'],
    //     series: [{
    //         name: dash_currency,
    //         data: yAxisArray
    //     }],
    //     xaxis: {
    //         categories: xAxisArray,
    //         labels: {
    //             style: {
    //                 colors: '#fff',
    //                 fontSize: '12px',
    //                 fontFamily: FONT_FAMILY,
    //                 fontWeight: 400,
    //                 cssClass: 'apexcharts-xaxis-label',
    //             }
    //         }
    //     },
    //     yaxis: {
    //         title: {
    //             text: 'Kazanç',
    //             style: {
    //                 color: hexToRgba(COLOR_WHITE, .5),
    //                 fontSize: '12px',
    //                 fontFamily: FONT_FAMILY,
    //                 fontWeight: 400
    //             }
    //         },
    //         labels: {
    //             formatter: function (val) {
    //                 return changeCommasToDecimal(val.toFixed(2))
    //             },
    //             style: {
    //                 colors: '#fff',
    //                 fontSize: '12px',
    //                 fontFamily: FONT_FAMILY,
    //                 fontWeight: 400,
    //                 cssClass: 'apexcharts-xaxis-label',
    //             }
    //         }
    //     },
    //     fill: {
    //         opacity: 1
    //     },
    //     tooltip: {
    //         y: {
    //             formatter: function (val) {
    //                 return changeCommasToDecimal(val.toFixed(2))
    //             }
    //         }
    //     }
    // };
    // var apexColumnChart = new ApexCharts(
    //     document.querySelector('#chart-approved-monthly'),
    //     apexColumnChartOptions
    // );
    // apexColumnChart.render();

}
