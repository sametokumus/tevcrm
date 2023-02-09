(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#update_quote_freight").maskMoney({thousands:''});
        $("#update_quote_vat_rate").maskMoney({thousands:''});

		$('#update_quote_form').submit(function (e){
			e.preventDefault();
            updateQuote();
		});

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        let sale_id = getPathVariable('purchasing-order-print');
        await initOfferSelect(sale_id);
        await initContact(sale_id);
        // await initSale(sale_id);
        // await initQuote(sale_id);
	});

})(window.jQuery);

function checkRole(){
	return true;
}

function printOffer(){
	window.print();
}

async function changeOffer(){
    let offer_id = document.getElementById('select_offer').value;
    if(offer_id == 0){
        document.getElementById('supplier_name').innerHTML = "";
        document.getElementById('supplier_address').innerHTML = "";
        $('#sub_total td').text("");
        $('#vat td').text("");
        $('#grand_total td').text("");
        $('#offer-detail tbody > tr').remove();


        $('#print-buttons').addClass('d-none');
        return false;
    }else{
        await initOffer(offer_id);
        $('#print-buttons').removeClass('d-none');
    }
}

async function initOfferSelect(sale_id){
    let data = await serviceGetSaleById(sale_id);
    let sale = data.sale;

    $.each(sale.sale_offers, function (i, offer) {
        let item = '<option value="'+ offer.offer_id +'">'+ offer.supplier_name +' - '+ offer.offer_id +'</option>';
        $('#select_offer').append(item);
    });

}

async function initContact(sale_id){

    let data = await serviceGetContactById(1);
    let contact = data.contact;

    $('#purchasing-order-print #logo').append('<img src="'+ contact.logo +'">');

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    $('#purchasing-order-print .logo-header .date').append('Date: '+ today);
    $('#purchasing-order-print .logo-header .offer-id').append(sale_id);

    $('#purchasing-order-print .contact-col address').append('<strong>'+ contact.name +'</strong><br><b>Registration No:</b> '+ contact.registration_no +'<br><b>Address</b><br>'+ contact.address +'<br><b>Phone:</b> '+ contact.phone +'<br><b>Email:</b> '+ contact.email +'');

}

async function initOffer(offer_id){
    let data = await serviceGetOfferById(offer_id);
    let offer = data.offer;
    let company = offer.company;
    console.log(offer);

    // $('#purchasing-order-print .supplier-col address').append('<strong>'+ company.name +'</strong><br>'+ company.address +'<br>Phone: '+ company.phone +'<br>Email: '+ company.email +'');
    document.getElementById('supplier_name').innerHTML = checkNull(company.name);
    document.getElementById('supplier_address').innerHTML = '<b>Address :</b> '+ checkNull(company.address);
    // document.getElementById('payment_term').innerHTML = '<b>Payment Terms :</b> '+ checkNull(quote.payment_term);

    $('#offer-detail tbody > tr').remove();

    $.each(offer.products, function (i, product) {
        let item = '<tr>\n' +
            '           <td>' + checkNull(product.ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.product_name) + '</td>\n' +
            '           <td>' + checkNull(product.quantity) + '</td>\n' +
            '           <td>' + checkNull(product.pcs_price) + '</td>\n' +
            '           <td>' + checkNull(product.total_price) + '</td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    });

    $('#sub_total td').text(checkNull(offer.sub_total));
    $('#vat td').text(checkNull(offer.vat));
    $('#grand_total td').text(checkNull(offer.grand_total));

}






async function initSale(sale_id){
    let data = await serviceGetSaleById(sale_id);
    console.log(data);
    let sale = data.sale;
    let company = sale.request.company;

    document.getElementById('buyer_name').innerHTML = '<b>Buyer :</b> '+ company.name;
    document.getElementById('buyer_address').innerHTML = '<b>Address :</b> '+ company.address;

    $('#sub_total td').text(checkNull(sale.sub_total));
    $('#freight td').text(checkNull(sale.freight));
    $('#vat td').text(checkNull(sale.vat) + ' (' + checkNull(sale.vat_rate) + '%)');
    $('#grand_total td').text(checkNull(sale.grand_total));


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

    document.getElementById('payment_term').innerHTML = '<b>Payment Terms :</b> '+ checkNull(quote.payment_term);
    document.getElementById('lead_time').innerHTML = '<b>Lead Time :</b> '+ checkNull(quote.lead_time);
    document.getElementById('delivery_term').innerHTML = '<b>Delivery Terms :</b> '+ checkNull(quote.delivery_term);
    document.getElementById('country_of_destination').innerHTML = '<b>Country of Destination :</b> '+ checkNull(quote.country_of_destination);

    document.getElementById('note').innerHTML = checkNull(quote.note);
    // $('#note').innerHTML(checkNull(quote.note));

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
    document.getElementById('update_quote_vat_rate').value = checkNull(quote.vat_rate);
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
    let vat_rate = document.getElementById('update_quote_vat_rate').value;
    let note = document.getElementById('update_quote_note').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "quote_id": quote_id,
        "payment_term": payment_term,
        "lead_time": lead_time,
        "delivery_term": delivery_term,
        "country_of_destination": country_of_destination,
        "freight": freight,
        "vat_rate": vat_rate,
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
