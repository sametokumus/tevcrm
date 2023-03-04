(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#update_quote_freight").maskMoney({thousands:'.', decimal:','});
        $("#update_quote_vat_rate").maskMoney({thousands:'.', decimal:','});

		$('#update_quote_form').submit(function (e){
			e.preventDefault();
            updateQuote();
		});

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        let sale_id = getPathVariable('quote-print');
        await initContact(1, sale_id);
        await initSale(sale_id);
        await initQuote(sale_id);
        await getOwnersAddSelectId('owners');
        document.getElementById('owners').value = 1;
	});

})(window.jQuery);

function checkRole(){
	return true;
}

function printOffer(){
	window.print();
}

async function changeOwner(){
    let owner = document.getElementById('owners').value;
    let sale_id = getPathVariable('quote-print');
    await initContact(owner, sale_id);
    await initSale(sale_id);
}

async function initContact(contact_id, sale_id){

    let data = await serviceGetContactById(contact_id);
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

    // var Lang = new Lang();
    document.getElementById('buyer_name').innerHTML = '<b>'+ new Lang.get("strings.Customer", 'tr') +' :</b> '+ company.name;
    document.getElementById('buyer_address').innerHTML = '<b>Address :</b> '+ company.address;

    $('#sub_total td').text(changeCommasToDecimal(sale.sub_total));
    $('#freight td').text(changeCommasToDecimal(sale.freight));
    $('#vat td').text(changeCommasToDecimal(sale.vat));
    $('#grand_total td').text(changeCommasToDecimal(sale.grand_total));


    $('#sale-detail tbody > tr').remove();

    $.each(sale.sale_offers, function (i, product) {
        let item = '<tr>\n' +
            '           <td>' + (i+1) + '</td>\n' +
            '           <td>' + checkNull(product.product_ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.product_name) + '</td>\n' +
            '           <td>' + checkNull(product.offer_quantity) + ' (' + checkNull(product.measurement_name) + ')</td>\n' +
            '           <td>' + changeCommasToDecimal(product.offer_pcs_price) + '</td>\n' +
            '           <td>' + changeCommasToDecimal(product.offer_price) + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });

}

async function initQuote(sale_id){
    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;

    document.getElementById('payment_term').innerHTML = '<b>Payment Terms :</b> '+ checkNull(quote.payment_term);
    document.getElementById('lead_time').innerHTML = '<b>Lead Time :</b> '+ checkNull(quote.lead_time);
    document.getElementById('delivery_term').innerHTML = '<b>Delivery Terms :</b> '+ checkNull(quote.delivery_term);
    document.getElementById('country_of_destination').innerHTML = '<b>Country of Destination :</b> '+ checkNull(quote.country_of_destination);
    document.getElementById('note').innerHTML = checkNull(quote.note);
}

async function openUpdateQuoteModal(){
    $("#updateQuoteModal").modal('show');
    await initUpdateQuoteModal();
}

async function initUpdateQuoteModal(){
    let sale_id = getPathVariable('quote-print');
    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;
    console.log(quote)

    document.getElementById('update_quote_id').value = quote.id;
    document.getElementById('update_quote_payment_term').value = checkNull(quote.payment_term);
    document.getElementById('update_quote_lead_time').value = checkNull(quote.lead_time);
    document.getElementById('update_quote_delivery_term').value = checkNull(quote.delivery_term);
    document.getElementById('update_quote_country_of_destination').value = checkNull(quote.country_of_destination);
    document.getElementById('update_quote_freight').value = checkNull(quote.freight);
    document.getElementById('update_quote_note').value = checkNull(quote.note);
}

async function updateQuote(){
    let sale_id = getPathVariable('quote-print');
    let quote_id = document.getElementById('update_quote_id').value;
    let payment_term = document.getElementById('update_quote_payment_term').value;
    let lead_time = document.getElementById('update_quote_lead_time').value;
    let delivery_term = document.getElementById('update_quote_delivery_term').value;
    let country_of_destination = document.getElementById('update_quote_country_of_destination').value;
    let freight = document.getElementById('update_quote_freight').value;
    let note = document.getElementById('update_quote_note').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "quote_id": quote_id,
        "payment_term": payment_term,
        "lead_time": lead_time,
        "delivery_term": delivery_term,
        "country_of_destination": country_of_destination,
        "freight": changePriceToDecimal(freight),
        "note": note
    });

    console.log(formData);

    let returned = await servicePostUpdateQuote(formData);
    if (returned){
        $("#update_quote_form").trigger("reset");
        $('#updateQuoteModal').modal('hide');
        await initSale(sale_id);
        await initQuote(sale_id);
    }else{
        alert("Hata Oluştu");
    }
}
