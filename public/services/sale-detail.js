(function($) {
    "use strict";

	 $(document).ready(function() {


	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        let sale_id = getPathVariable('sale-detail');
        await initSaleStats(sale_id);
        await initSaleHistory();
        // await initTopRequestedProducts();

    });

})(window.jQuery);

function checkRole(){
    return true;
}

async function initSaleHistory(){
    let data = await serviceGetSaleStatusHistory();
    let actions = data.actions;

    $('#sales-history-table tbody tr').remove();

    $.each(actions, function (i, action) {
        let last_time = formatDateAndTimeDESC(action.sale.created_at, "/");
        if (action.sale.updated_at != null){
            last_time = formatDateAndTimeDESC(action.sale.updated_at, "/");
        }

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ action.sale.customer_name +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td><small>'+ last_time +'</small></td>\n' +
            '           <td>\n' +
            '               <span class="badge bg-white bg-opacity-25 rounded-0 pt-5px" style="min-height: 18px">'+ action.previous_status.status_name +'</span>\n' +
            '               <i class="bi bi-arrow-90deg-right"></i>\n' +
            '               <span class="badge bg-theme text-theme-900 rounded-0 pt-5px" style="min-height: 18px">'+ action.last_status.status_name +'</span>\n' +
            '           </td>\n' +
            '           <td>\n' +
            '               <a href="/sale-detail/'+ action.sale_id +'" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>\n' +
            '           </td>\n' +
            '       </tr>';

        $('#sales-history-table tbody').append(item);
    });
}

async function initTopRequestedProducts(){
    let data = await serviceGetTopRequestedProducts();
    let products = data.products;

    $('#top-products-table tbody tr').remove();

    $.each(products, function (i, product) {

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ product.product_detail.ref_code +'\n' +
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

async function initSaleStats(sale_id){
    let data = await serviceGetSaleDetailInfo(sale_id);
    let sale = data.sale;
    console.log(sale)
    let total = '-';
    if (sale.grand_total_with_shipping != null){
        total = changeCommasToDecimal(sale.grand_total_with_shipping);
    }

    $('#customer-name').append('<a href="#" class="text-decoration-none text-white">'+sale.request.company.name+'</a>');
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
