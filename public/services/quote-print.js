(function($) {
    "use strict";

	$(document).ready(function() {

		$('#add_offer_form').submit(function (e){
			e.preventDefault();
            addOffer();
		});

        $('#add_offer_request_product_button').click(function (e){
            e.preventDefault();
            let refcode = document.getElementById('add_offer_request_product_refcode').value;
            let product_name = document.getElementById('add_offer_request_product_name').value;
            let quantity = document.getElementById('add_offer_request_product_quantity').value;
            if (refcode == '' || product_name == '' || quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            addProductToTable(refcode, product_name, quantity);
        });
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        let sale_id = getPathVariable('quote-print');
        await initContact(sale_id);
        await initSale(sale_id);
        await initQuote(sale_id);
	});

})(window.jQuery);

function checkRole(){
	return true;
}

function printOffer(){
	window.print();
}

async function initContact(sale_id){

    let data = await serviceGetContactById(1);
    let contact = data.contact;

    $('#quote-print #logo').append('<img src="'+ contact.logo +'">');

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    $('#quote-print .logo-header .date').append('Date: '+ today);
    $('#quote-print .logo-header .offer-id').append(sale_id);

    $('#quote-print .contact-col address').append('<strong>'+ contact.name +'</strong><br><b>Registration No:</b> '+ contact.registration_no +'<br><b>Address</b><br>'+ contact.address +'<br><b>Phone:</b> '+ contact.phone +'<br><b>Email:</b> '+ contact.email +'');

}

async function initSale(sale_id){
    let data = await serviceGetSaleById(sale_id);
    console.log(data);
    let sale = data.sale;
    let company = sale.request.company;

    $('#quote-print .buyer-col address #buyer_name').append('<b>Buyer :</b> '+ company.name);
    $('#quote-print .buyer-col address #buyer_address').append('<b>Address :</b> '+ company.address);

    $('#sale-detail tbody > tr').remove();

    $.each(sale.sale_offers, function (i, product) {
        let item = '<tr>\n' +
            '           <td>' + checkNull(product.product_ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.product_name) + '</td>\n' +
            '           <td>' + checkNull(product.offer_quantity) + '</td>\n' +
            '           <td>' + checkNull(product.pcs_price) + '</td>\n' +
            '           <td>' + checkNull(product.total_price) + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });

}

async function initQuote(sale_id){
    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;

    $('#quote-print .buyer-col address #payment_term').append('<b>Payment Terms :</b> '+ quote.payment_term);
    $('#quote-print .buyer-col address #lead_time').append('<b>Lead Time :</b> '+ quote.lead_time);
    $('#quote-print .buyer-col address #delivery_term').append('<b>Delivery Terms :</b> '+ quote.delivery_term);
    $('#quote-print .buyer-col address #country_of_destination').append('<b>Country of Destination :</b> '+ quote.country_of_destination);

}
