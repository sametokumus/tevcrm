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
		// // await initPage();
        // await initOfferRequest();
        // await initOffers();

        await initContact(1);

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function printOffer(){
	window.print();
}

async function initContact(id){
    let offer_id = getPathVariable('offer-print');

    let data = await serviceGetContactById(id);
    let contact = data.contact;

    $('#offer-print #logo').append('<img src="'+ contact.logo +'">');

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    $('#offer-print .logo-header .date').append('Tarih: '+ today);
    $('#offer-print .logo-header .offer-id').append(offer_id);

    $('#offer-print .contact-col address').append('<strong>'+ contact.name +'</strong><br>'+ contact.address +'<br>Phone: '+ contact.phone +'<br>Email: '+ contact.email +'');
    await initOffer(offer_id);
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
