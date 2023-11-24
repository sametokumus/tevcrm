(function($) {
    "use strict";

    $(document).ready(function() {

        $(":input").inputmask();
        $("#update_quote_freight").maskMoney({thousands:'.', decimal:','});
        $("#update_quote_advance_price").maskMoney({thousands:'.', decimal:','});
        $("#update_sale_shipping_price").maskMoney({thousands:'.', decimal:','});
        $("#update_currency_rate_usd").maskMoney({thousands:'.', decimal:','});
        $("#update_currency_rate_eur").maskMoney({thousands:'.', decimal:','});
        $("#update_currency_rate_gbp").maskMoney({thousands:'.', decimal:','});

        $('#update_currency_rate_form').submit(function (e){
            e.preventDefault();
            updateCurrencyRate();
        });

        $('#update_detail_form').submit(function (e){
            e.preventDefault();
            updateDetail();
        });

        $('#update_quote_form').submit(function (e){
            e.preventDefault();
            updateQuote();
        });

    });

    $(window).load(async function() {

        checkLogin();
        checkRole();

        let sale_id = getPathVariable('proforma-invoice-print');
        await initDetail(sale_id);
        await initBankInfoSelect();
        // await initContact(1, sale_id);
        await initSale(sale_id);
        await initQuote(sale_id);
        // await getOwnersAddSelectId('owners');
        // document.getElementById('owners').value = 1;
    });

})(window.jQuery);

let sale_price = 0;
let short_code;
let currency = "";

function checkRole(){
    return true;
}

function printOffer(){
    window.print();
}

async function generatePDF(){
    let lang = document.getElementById('lang').value;
    let owner_id = document.getElementById('owners').value;
    let bank_id = document.getElementById('select_bank_info').value;
    let sale_id = getPathVariable('proforma-invoice-print');
    let target = document.getElementById('pdf_currency').value;

    // Fetch the PDF data
    const pdfData = await serviceGetGenerateProformaInvoicePDF(lang, owner_id, sale_id, bank_id, target);

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
    let sale_id = getPathVariable('proforma-invoice-print');
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
    document.getElementById('pdf_currency').value = sale.currency;
    let company = sale.request.company;

    document.getElementById('update_currency_rate_currency').value = sale.currency;
    document.getElementById('update_currency_rate_request_id').value = sale.request_id;
    document.getElementById('update_currency_rate_usd').value = changeCommasToDecimal(sale.usd_rate);
    document.getElementById('update_currency_rate_eur').value = changeCommasToDecimal(sale.eur_rate);
    document.getElementById('update_currency_rate_gbp').value = changeCommasToDecimal(sale.gbp_rate);

    document.getElementById('buyer_name').innerHTML = '<b>'+ Lang.get("strings.Customer") +' :</b> '+ company.name;
    if (checkNull(company.registration_number) != '') {
        document.getElementById('buyer_registration_number').innerHTML = '<b>' + Lang.get("strings.Registration No") + ' :</b> ' + checkNull(company.registration_number);
    }else{
        $('#buyer_registration_number').addClass('d-none');
        $('.buyer-col br:first').remove();
    }
    document.getElementById('buyer_address').innerHTML = '<b>'+ Lang.get("strings.Address") +' :</b> '+ company.address;
    document.getElementById('buyer_phone').innerHTML = '<b>'+ Lang.get("strings.Phone") +' :</b> '+ company.phone;
    document.getElementById('buyer_email').innerHTML = '<b>'+ Lang.get("strings.Email") +' :</b> '+ company.email;

    $('#quote-print .logo-header .offer-id').text(short_code+'-PI-'+sale.id);

    $('#sale-detail tbody > tr').remove();

    currency = sale.currency;
    $.each(sale.sale_offers, function (i, product) {
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
            '           <td class="text-center">' + checkNull(product.request_quantity) + '</td>\n' +
            '           <td class="text-center text-capitalize">' + checkNull(measurement_name) + '</td>\n' +
            '           <td class="text-center">' + changeCommasToDecimal(product.offer_pcs_price) + ' '+ currency +'</td>\n' +
            '           <td class="text-center">' + checkNull(product.offer_price) + ' '+ currency +'</td>\n' +
            '           <td class="text-center">' + lead_time + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });
    document.getElementById('update_sale_shipping_price').value = checkNull(sale.shipping_price);

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
        sale_price = sale.grand_total;
        if ((sale.vat != null && sale.vat != '0.00') || sale.freight != null) {
            let item = '<tr>\n' +
                '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Grand Total") + '</td>\n' +
                '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.grand_total) + ' ' + currency + '</td>\n' +
                '       </tr>';
            $('#sale-detail tbody').append(item);
        }
    }

    if (sale.shipping_price != null) {
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Shipping") + '</td>\n' +
            '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.shipping_price) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

    if (sale.grand_total_with_shipping != null) {
        sale_price = sale.grand_total_with_shipping;
        if (sale.shipping_price != null) {
            let item = '<tr>\n' +
                '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Grand Total") + '</td>\n' +
                '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.grand_total_with_shipping) + ' ' + currency + '</td>\n' +
                '       </tr>';
            $('#sale-detail tbody').append(item);
        }
    }

}

async function initDetail(sale_id){
    let data = await serviceGetProformaInvoiceDetailById(sale_id);
    let detail = data.proforma_invoice_detail;

    if (detail != null) {
        // document.getElementById('payment_term').innerHTML = '<b>'+ Lang.get("strings.Payment Terms") +' :</b> ' + checkNull(detail.payment_term);
        document.getElementById('note').innerHTML = checkNull(detail.note);
    }
}

async function updateDetail(){
    let sale_id = getPathVariable('proforma-invoice-print');
    let note = $('#update_sale_note').summernote('code');
    let shipping_price = document.getElementById('update_sale_shipping_price').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "note": note
    });
    let returned1 = await servicePostUpdateProformaInvoiceDetail(formData);

    let formData2 = JSON.stringify({
        "sale_id": sale_id,
        "shipping_price": changePriceToDecimal(shipping_price)
    });
    let returned2 = await servicePostUpdateShippingPrice(formData2);


    if (returned1 && returned2){
        await initSale(sale_id);
        await initDetail(sale_id);
    }else{
        alert("Hata Oluştu");
    }
}



async function initBankInfoSelect(){
    let data = await serviceGetBankInfos();
    let bank_infos = data.bank_infos;

    $.each(bank_infos, function (i, info) {
        let item = '<option value="'+ info.id +'">'+ info.name +'</option>';
        $('#select_bank_info').append(item);
    });

}



async function initQuote(sale_id){

    await getPaymentTermsAddSelectId('update_quote_payment_term');
    await getDeliveryTermsAddSelectId('update_quote_delivery_term');

    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;
    console.log(quote)

    document.getElementById('update_quote_id').value = quote.id;
    document.getElementById('update_quote_payment_term').value = checkNull(quote.payment_term);
    document.getElementById('update_quote_advance_price').value = changeCommasToDecimal(quote.advance_price);
    document.getElementById('update_quote_lead_time').value = checkNull(quote.lead_time);
    document.getElementById('update_quote_delivery_term').value = checkNull(quote.delivery_term);
    document.getElementById('update_quote_country_of_destination').value = checkNull(quote.country_of_destination);
    document.getElementById('update_quote_freight').value = changeCommasToDecimal(quote.freight);
    document.getElementById('update_quote_expiry_date').value = formatDateASC(quote.expiry_date, "-");
    $('#update_quote_note').summernote('code', checkNull(quote.note));

    if (quote.pi_pdf == ''){
        $('#no-pdf').removeClass('d-none');
    }else{
        $('#has-pdf').removeClass('d-none');
        $('#showPdf').attr('href', quote.pi_pdf);
    }

}
async function updateQuote(){
    let sale_id = getPathVariable('proforma-invoice-print');
    let quote_id = document.getElementById('update_quote_id').value;
    let payment_term = document.getElementById('update_quote_payment_term').value;
    let advance_price = document.getElementById('update_quote_advance_price').value;
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
        "advance_price": changePriceToDecimal(advance_price),
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


async function getCurrencyLog(){
    let data = await serviceGetLastCurrencyLog();
    let log = data.currency_log;
    document.getElementById('update_currency_rate_usd').value = changeCommasToDecimal(log.usd);
    document.getElementById('update_currency_rate_eur').value = changeCommasToDecimal(log.eur);
    document.getElementById('update_currency_rate_gbp').value = changeCommasToDecimal(log.gbp);
}
async function updateCurrencyRate() {
    let request_id = document.getElementById('update_currency_rate_request_id').value;
    console.log(request_id)

    let formData = JSON.stringify({
        "currency": document.getElementById('update_currency_rate_currency').value,
        "usd_rate": changePriceToDecimal(document.getElementById('update_currency_rate_usd').value),
        "eur_rate": changePriceToDecimal(document.getElementById('update_currency_rate_eur').value),
        "gbp_rate": changePriceToDecimal(document.getElementById('update_currency_rate_gbp').value)
    });
    console.log(formData)

    let returned = await servicePostUpdateSaleCurrencyLogOnPI(formData, request_id);
    if (returned){
        showAlert('Kur bilgisi başarıyla güncellendi.')
    }else{
        alert("Kur bilgisi güncellerken hata oluştu.")
    }
}



async function checkAdvancePrice(){
    let payment_term_id = document.getElementById('update_quote_payment_term').value;
    let data = await serviceGetPaymentTermById(payment_term_id);
    let term = data.payment_term;

    console.log(sale_price)
    if (term.advance != null && term.advance != 0){
        if (sale_price != 0) {
            document.getElementById('update_quote_advance_price').value = changeCommasToDecimal(parseFloat(sale_price / 100 * term.advance).toFixed(2));
        }
    }else{
        document.getElementById('update_quote_advance_price').value = '0,00';
    }


}
