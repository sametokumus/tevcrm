(function($) {
    "use strict";

	 $(document).ready(function() {


	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        let sale_id = getPathVariable('sale-detail');
        await initSaleStats(sale_id);
        await initSaleHistory(sale_id);
        await initSaleSuppliers(sale_id);

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
        let last_time = formatDateAndTimeDESC(action.sale.created_at, "/");
        if (action.sale.updated_at != null){
            last_time = formatDateAndTimeDESC(action.sale.updated_at, "/");
        }
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
    if (sale.grand_total_with_shipping != null){
        total = changeCommasToDecimal(sale.grand_total_with_shipping);
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
                '           <td><small>' + offer.supplier.website + '</small></td>\n' +
                '           <td><small>' + offer.supplier.email + '</small></td>\n' +
                '           <td><small>' + offer.supplier.phone + '</small></td>\n' +
                '           <td><small>' + offer.product_count + ' Ürün</small></td>\n' +
                '           <td><small>' + offer.total_price + ' ' + offer.currency + '+KDV</small></td>\n' +
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
