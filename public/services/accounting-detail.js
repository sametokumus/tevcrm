(function($) {
    "use strict";

	 $(document).ready(function() {


	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        let sale_id = getPathVariable('accounting-detail');
        await initSaleStats(sale_id);
        // await initPayments(sale_id);

    });

})(window.jQuery);

function checkRole(){
    return true;
}

async function initSaleStats(sale_id){
    let data = await serviceGetSaleDetailInfo(sale_id);
    let sale = data.sale;
    console.log(sale)
    let total = sale.grand_total;
    if (sale.grand_total_with_shipping != null){
        total = sale.grand_total_with_shipping;
    }
    let remaining_price = '-';

    $('#customer-name').append('<a href="/company-detail/'+sale.request.company.id+'" class="text-decoration-none text-white">'+sale.request.company.name+'</a>');
    $('#customer-employee').append('Müşteri Yetkilisi: '+sale.request.company_employee.name);
    $('#owner-employee').append('Firma Yetkilisi: '+sale.request.authorized_personnel.name+' '+sale.request.authorized_personnel.surname);

    $('#total-price').text(changeCommasToDecimal(total) + ' ' + sale.currency);
    $('#remaining-price').text(changeCommasToDecimal(remaining_price) + ' ' + sale.currency);


    $('#sale-date').append(formatDateAndTimeDESC(sale.created_at, '/'));

    // $('#total-sale').text(stats.total_sale);
    // $('#active-sale').text(stats.active_sale);
    // $('#total-product').text(stats.total_product);


}

async function initPayments(sale_id){
    let data = await serviceGetPayments(sale_id);
    let sale = data.sale;
    console.log(sale)
    let total = sale.grand_total;
    if (sale.grand_total_with_shipping != null){
        total = sale.grand_total_with_shipping;
    }
    let remaining_price = '-';

    $('#customer-name').append('<a href="/company-detail/'+sale.request.company.id+'" class="text-decoration-none text-white">'+sale.request.company.name+'</a>');
    $('#customer-employee').append('Müşteri Yetkilisi: '+sale.request.company_employee.name);
    $('#owner-employee').append('Firma Yetkilisi: '+sale.request.authorized_personnel.name+' '+sale.request.authorized_personnel.surname);

    $('#total-price').text(changeCommasToDecimal(total) + ' ' + sale.currency);
    $('#remaining-price').text(changeCommasToDecimal(remaining_price) + ' ' + sale.currency);


    $('#sale-date').append(formatDateAndTimeDESC(sale.created_at, '/'));

    // $('#total-sale').text(stats.total_sale);
    // $('#active-sale').text(stats.active_sale);
    // $('#total-product').text(stats.total_product);


}
