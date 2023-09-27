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
        // let lng = $('meta[name="current-locale"]').attr('content');
        // Lang.setLocale(lng);

		checkLogin();
		checkRole();

        let sale_id = getPathVariable('quote-print');

        await getOwnersAddSelectId('owners');
        await getPaymentTermsAddSelectId('update_quote_payment_term');
        await getDeliveryTermsAddSelectId('update_quote_delivery_term');
        await initSale(sale_id);
        await initQuote(sale_id);
	});

})(window.jQuery);
let short_code;

function checkRole(){
	return true;
}

function printOffer(){
	window.print();
}

async function generatePDF(){
    let lang = document.getElementById('lang').value;
    let owner_id = document.getElementById('owners').value;
    let sale_id = getPathVariable('quote-print');

    // Fetch the PDF data
    const pdfData = await serviceGetGenerateQuatotionPDF(lang, owner_id, sale_id);

    // Create a link element to download the PDF
    const link = document.createElement('a');
    link.href = `${pdfData.object.file_url}`;
    link.target = '_blank';
    link.download = `${pdfData.object.file_name}`;
    link.textContent = 'Download PDF';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

async function changeOwner(){
    let owner = document.getElementById('owners').value;
    let sale_id = getPathVariable('quote-print');
    // await initContact(owner, sale_id);
}

async function initContact(contact_id, sale_id){

    let data = await serviceGetContactById(contact_id);
    let contact = data.contact;
    short_code = contact.short_code;
    let width = '150px';
    if (contact_id == 3){
        width = '250px';
    }

    $('#quote-print #logo img').remove();
    $('#quote-print #logo').append('<img style="width: '+width+'" src="'+ contact.logo +'">');

    if (contact_id == 1){
        $('#print-footer').addClass('lenis-footer');
        $('.footer-spacer').addClass('lenis-spacer');
    }
    if (contact_id == 3){
        $('#print-footer').addClass('semy-footer');
        $('.footer-spacer').addClass('semy-spacer');
    }
    $('#print-footer img').remove();
    $('#print-footer').append('<img src="'+ contact.footer +'" alt="" class="w-100">');

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    $('#quote-print .logo-header .date').text(Lang.get("strings.Date") +': '+ today);
    // $('#quote-print .logo-header .offer-id').append(sale_id);

    $('#quote-print .contact-col address').text('');

    $('#quote-print .contact-col address').append('<strong>'+ contact.name +'</strong><br>');
    if (contact.registration_no != '') {
        $('#quote-print .contact-col address').append('<b>' + Lang.get("strings.Registration No") + ' :</b> ' + contact.registration_no + '&nbsp;&nbsp;&nbsp;');
    }
    let lang = Lang.getLocale();
    if (contact.registration_office != '' && lang != 'en') {
        $('#quote-print .contact-col address').append('<b>' + Lang.get("strings.Registration Office") + ' :</b> ' + contact.registration_office);
    }
    $('#quote-print .contact-col address').append('<br>');
    $('#quote-print .contact-col address').append('<b>'+ Lang.get("strings.Address") +'</b><br>'+ contact.address +'<br>');
    $('#quote-print .contact-col address').append('<b>'+ Lang.get("strings.Phone") +':</b> '+ contact.phone +'<br>');
    $('#quote-print .contact-col address').append('<b>'+ Lang.get("strings.Email") +':</b> '+ contact.email +'');

}

async function initSale(sale_id){
    let data = await serviceGetSaleById(sale_id);
    console.log(data);
    let sale = data.sale;

    await initContact(sale.owner_id, sale_id);
    await getOwnersAddSelectId('owners');
    document.getElementById('owners').value = sale.owner_id;

    await getPaymentTermsAddSelectId('update_quote_payment_term');
    await getDeliveryTermsAddSelectId('update_quote_delivery_term');

    let company = sale.request.company;
    document.getElementById('buyer_name').innerHTML = '<b>'+ Lang.get("strings.Customer") +' :</b> '+ company.name;
    document.getElementById('buyer_address').innerHTML = '<b>'+ Lang.get("strings.Address") +' :</b> '+ company.address;
    document.getElementById('payment_term').innerHTML = '<b>'+ Lang.get("strings.Payment Terms") +' :</b> '+ checkNull(company.payment_term);
    document.getElementById('update_quote_payment_term').value = checkNull(company.payment_term);

    if (checkNull(sale.request.company_request_code) != '') {
        document.getElementById('company_request_code').innerHTML = '<b>' + Lang.get("strings.Request Code") + ' :</b> ' + sale.request.company_request_code;
    }else{
        $('#company_request_code').addClass('d-none');
    }

    $('#quote-print .logo-header .offer-id').text(short_code+'-OFR-'+sale.id);

    $('#sale-detail tbody > tr').remove();

    let currency = sale.currency;
    $.each(sale.sale_offers, function (i, product) {
        console.log(product)
        let lead_time = checkNull(product.offer_lead_time);
        if (lead_time != ''){
            if (lead_time == 1){
                lead_time = Lang.get("strings.Stock");
            }else if (parseInt(lead_time)%7 == 0){
                lead_time = (parseInt(lead_time)/7) + ' ' + Lang.get("strings.Week");
            }else{
                lead_time = lead_time + ' ' + Lang.get("strings.Day");
            }
        }

        let measurement_name = '';
        if (Lang.getLocale() == 'tr'){
            measurement_name = product.measurement_name_tr;
        }else{
            measurement_name = product.measurement_name_en;
        }
        let item = '<tr>\n' +
            '           <td class="text-center">' + product.sequence + '</td>\n' +
            '           <td class="text-capitalize">' + checkNull(product.product_ref_code) + '</td>\n' +
            '           <td class="text-capitalize">' + checkNull(product.product_name) + '</td>\n' +
            '           <td class="text-center">' + checkNull(product.offer_quantity) + '</td>\n' +
            '           <td class="text-center text-capitalize">' + checkNull(measurement_name) + '</td>\n' +
            '           <td class="text-center text-nowrap">' + changeCommasToDecimal(product.offer_pcs_price) + ' '+ currency +'</td>\n' +
            '           <td class="text-center text-nowrap">' + changeCommasToDecimal(product.offer_price) + ' '+ currency +'</td>\n' +
            '           <td class="text-center">' + lead_time + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });

    if (sale.sub_total != null) {
        let text = Lang.get("strings.Sub Total");
        if ((sale.vat == null || sale.vat == '0.00') && sale.freight == null){
            text = Lang.get("strings.Grand Total");
        }
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + text + '</td>\n' +
            '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.sub_total) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

    if (sale.freight != null) {
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Freight") + '</td>\n' +
            '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.freight) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

    if (sale.vat != null && sale.vat != '0.00') {
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Vat") + '</td>\n' +
            '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.vat) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

    if (sale.grand_total != null) {
        if ((sale.vat != null && sale.vat != '0.00') || sale.freight != null) {
            let item = '<tr>\n' +
                '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Grand Total") + '</td>\n' +
                '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.grand_total) + ' ' + currency + '</td>\n' +
                '       </tr>';
            $('#sale-detail tbody').append(item);
        }
    }

}

async function initQuote(sale_id){
    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;
    console.log(quote)

    document.getElementById('update_quote_id').value = quote.id;
    document.getElementById('update_quote_payment_term').value = checkNull(quote.payment_term);
    document.getElementById('update_quote_lead_time').value = checkNull(quote.lead_time);
    document.getElementById('update_quote_delivery_term').value = checkNull(quote.delivery_term);
    document.getElementById('update_quote_country_of_destination').value = checkNull(quote.country_of_destination);
    document.getElementById('update_quote_freight').value = changeCommasToDecimal(quote.freight);
    document.getElementById('update_quote_expiry_date').value = formatDateASC(quote.expiry_date, "-");
    $('#update_quote_note').summernote('code', checkNull(quote.note));

    if (quote.quatotion_pdf == ''){
        $('#has-pdf').remove();
    }else{
        $('#no-pdf').remove();
        $('#showPdf').attr('href', quote.quatotion_pdf);
    }

    // if (checkNull(quote.payment_term) != '') {
    //     document.getElementById('payment_term').innerHTML = '<b>' + Lang.get("strings.Payment Terms") + ' :</b> ' + quote.payment_term;
    //     document.getElementById('update_quote_payment_term').value = quote.payment_term;
    // }
    // if (checkNull(quote.lead_time) != '') {
    //     document.getElementById('lead_time').innerHTML = '<b>' + Lang.get("strings.Insurance") + ' :</b> ' + checkNull(quote.lead_time);
    // }else{
    //     $('#lead_time').addClass('d-none');
    // }
    // if (checkNull(quote.delivery_term) != '') {
    //     document.getElementById('delivery_term').innerHTML = '<b>'+ Lang.get("strings.Delivery Terms") +' :</b> '+ checkNull(quote.delivery_term);
    // }else{
    //     $('#delivery_term').addClass('d-none');
    // }
    // if (checkNull(quote.country_of_destination) != '') {
    //     document.getElementById('country_of_destination').innerHTML = '<b>'+ Lang.get("strings.Country of Destination") +' :</b> '+ checkNull(quote.country_of_destination);
    // }else{
    //     $('#country_of_destination').addClass('d-none');
    // }
    // document.getElementById('note').innerHTML = checkNull(quote.note);
}

async function openUpdateQuoteModal(){
    $("#updateQuoteModal").modal('show');
    await initUpdateQuoteModal();
}

async function initUpdateQuoteModal(){
    let sale_id = getPathVariable('quote-print');
    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;

    document.getElementById('update_quote_id').value = quote.id;
    document.getElementById('update_quote_payment_term').value = checkNull(quote.payment_term);
    document.getElementById('update_quote_lead_time').value = checkNull(quote.lead_time);
    document.getElementById('update_quote_delivery_term').value = checkNull(quote.delivery_term);
    document.getElementById('update_quote_country_of_destination').value = checkNull(quote.country_of_destination);
    document.getElementById('update_quote_freight').value = changeCommasToDecimal(quote.freight);
    $('#update_quote_note').summernote('code', checkNull(quote.note));
}

async function updateQuote(){
    let sale_id = getPathVariable('quote-print');
    let quote_id = document.getElementById('update_quote_id').value;
    let payment_term = document.getElementById('update_quote_payment_term').value;
    let lead_time = document.getElementById('update_quote_lead_time').value;
    let delivery_term = document.getElementById('update_quote_delivery_term').value;
    let country_of_destination = document.getElementById('update_quote_country_of_destination').value;
    let freight = document.getElementById('update_quote_freight').value;
    let expiry_date = document.getElementById('update_quote_expiry_date').value;
    let note = document.getElementById('update_quote_note').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "quote_id": quote_id,
        "payment_term": payment_term,
        "lead_time": lead_time,
        "delivery_term": delivery_term,
        "country_of_destination": country_of_destination,
        "freight": changePriceToDecimal(freight),
        "expiry_date": formatDateDESC2(expiry_date, "-", "-"),
        "note": note
    });


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
