(function($) {
    "use strict";

	$(document).ready(function() {

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        let offer_id = getPathVariable('offer-print');
        await initContact(offer_id);
        await initOffer(offer_id);
	});

})(window.jQuery);

function checkRole(){
	return true;
}

function printOffer(){
	window.print();
}

async function initContact(offer_id){

    let data = await serviceGetContactById(1);
    let contact = data.contact;

    $('#offer-print #logo').append('<img src="'+ contact.logo +'">');

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    $('#offer-print .logo-header .date').append('Date: '+ today);
    $('#offer-print .logo-header .offer-id').append(offer_id);

    $('#offer-print .contact-col address').append('<strong>'+ contact.name +'</strong><br>'+ contact.address +'<br>Phone: '+ contact.phone +'<br>Email: '+ contact.email +'');

}

async function initOffer(offer_id){
    let data = await serviceGetOfferById(offer_id);
    let offer = data.offer;
    let company = offer.company;
    console.log(offer);

    $('#offer-print .supplier-col address').append('<strong>'+ company.name +'</strong><br>'+ company.address +'<br>Phone: '+ company.phone +'<br>Email: '+ company.email +'');

    $('#offer-detail tbody > tr').remove();

    $.each(offer.products, function (i, product) {
        let item = '<tr>\n' +
            '           <td>' + checkNull(product.quantity) + '</td>\n' +
            '           <td>' + checkNull(product.ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.product_name) + '</td>\n' +
            '           <td></td>\n' +
            '           <td></td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    });

}
