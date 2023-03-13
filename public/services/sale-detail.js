(function($) {
    "use strict";

	 $(document).ready(function() {


	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        let sale_id = getPathVariable('sale-detail');
        // await initSaleHistory();
        // await initTopRequestedProducts();
        await initSaleStats(sale_id);

	});

})(window.jQuery);

function checkRole(){
    return true;
}

async function initSaleHistory(){
    let data = await serviceGetSaleHistoryActions();
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

    $('#customer-name').append('<a href="#">'+sale.request.company.name+'</a>');

    // $('#total-request').text(stats.total_request);
    // $('#total-sale').text(stats.total_sale);
    // $('#active-sale').text(stats.active_sale);
    // $('#total-product').text(stats.total_product);


}
