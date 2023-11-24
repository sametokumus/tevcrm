(function($) {
    "use strict";

    $(document).ready(function() {

        $(":input").inputmask();
        $("#update_quote_freight").maskMoney({thousands:'.', decimal:','});
        $("#update_quote_advance_price").maskMoney({thousands:'.', decimal:','});
        $("#update_quote_vat_rate").maskMoney({thousands:'.', decimal:','});

        $('#update_quote_form').submit(function (e){
            e.preventDefault();
            updateQuote();
        });

        $('#add_note_form').submit(function (e){
            e.preventDefault();
            addNote();
        });

    });

    $(window).load(async function() {
        checkLogin();
        checkRole();

        let sale_id = getPathVariable('order-confirmation-print');
        getOwnersAddSelectId('owners');
        initBankInfoSelect();
        initOrderConfirmationNote(sale_id);
        await initContact(1, sale_id);
        await initSale(sale_id);
        initQuote(sale_id);
    });

})(window.jQuery);

let sale_price = 0;
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
    let bank_id = document.getElementById('select_bank_info').value;
    let sale_id = getPathVariable('order-confirmation-print');

    // Fetch the PDF data
    const pdfData = await serviceGetGenerateOrderConfirmationPDF(lang, owner_id, sale_id, bank_id);

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
    let sale_id = getPathVariable('order-confirmation-print');
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

    $('#order-confirmation-print #logo img').remove();
    $('#order-confirmation-print #logo').append('<img style="width: '+width+'" src="'+ contact.logo +'">');

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
    $('#order-confirmation-print .logo-header .date').text(Lang.get("strings.Date") +': '+ today);
    // $('#order-confirmation-print .logo-header .offer-id').append(sale_id);
    $('.company-signature .name').text(contact.authorized_name);
    $('.company-signature #signature img').remove();
    $('.company-signature #signature').append('<img src="'+ contact.signature +'" style="height: 70px; width: auto;" >');
    // document.getElementById('signature').style.backgroundImage = "url('"+contact.signature+"')";

    $('#order-confirmation-print .contact-col address').text('');

    $('#order-confirmation-print .contact-col address').append('<strong>'+ contact.name +'</strong><br>');
    if (contact.registration_no != '') {
        $('#order-confirmation-print .contact-col address').append('<b>' + Lang.get("strings.Registration No") + ' :</b> ' + contact.registration_no + '&nbsp;&nbsp;&nbsp;');
    }
    let lang = Lang.getLocale();
    if (contact.registration_office != '' && lang != 'en') {
        $('#order-confirmation-print .contact-col address').append('<b>' + Lang.get("strings.Registration Office") + ' :</b> ' + contact.registration_office);
    }
    $('#order-confirmation-print .contact-col address').append('<br>');
    $('#order-confirmation-print .contact-col address').append('<b>'+ Lang.get("strings.Address") +'</b><br>'+ contact.address +'<br>');
    $('#order-confirmation-print .contact-col address').append('<b>'+ Lang.get("strings.Phone") +':</b> '+ contact.phone +'<br>');
    $('#order-confirmation-print .contact-col address').append('<b>'+ Lang.get("strings.Email") +':</b> '+ contact.email +'');
}

async function initSale(sale_id){
    let data = await serviceGetSaleById(sale_id);
    console.log(data);
    let sale = data.sale;
    await initContact(sale.owner_id, sale_id);

    document.getElementById('owners').value = sale.owner_id;

    await getPaymentTermsAddSelectId('update_quote_payment_term');
    await getDeliveryTermsAddSelectId('update_quote_delivery_term');

    let company = sale.request.company;
    document.getElementById('buyer_name').innerHTML = '<b>'+ Lang.get("strings.Customer") +' :</b> '+ company.name;
    document.getElementById('buyer_address').innerHTML = '<b>'+ Lang.get("strings.Address") +' :</b> '+ company.address;
    document.getElementById('payment_term').innerHTML = '<b>'+ Lang.get("strings.Payment Terms") +' :</b> '+ checkNull(company.payment_term);
    document.getElementById('update_quote_payment_term').value = checkNull(company.payment_term);


    $('#order-confirmation-print .logo-header .offer-id').text(short_code+'-OC-'+sale.id);

    $('#sale-detail tbody > tr').remove();

    let currency = sale.currency;
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
        sale_price = sale.grand_total;
        if ((sale.vat != null && sale.vat != '0.00') || sale.freight != null) {
            let item = '<tr>\n' +
                '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Grand Total") + '</td>\n' +
                '           <td colspan="2" class="text-center">' + changeCommasToDecimal(sale.grand_total) + ' ' + currency + '</td>\n' +
                '       </tr>';
            $('#sale-detail tbody').append(item);
        }
    }


    $('#addNoteBtn').addClass('d-none');
    $('#updateNoteBtn').addClass('d-none');
    let data2 = await serviceGetOrderConfirmationDetailById(sale_id);
    console.log(data2)
    let order_confirmation_detail = data2.order_confirmation_detail;
    console.log(order_confirmation_detail)
    if (order_confirmation_detail == null){
        $('#addNoteBtn').removeClass('d-none');
    }else{
        $('#updateNoteBtn').removeClass('d-none');
        $('#note').append(order_confirmation_detail.note);
    }

}


async function initOrderConfirmationNote(sale_id){
    let data = await serviceGetOrderConfirmationDetailById(sale_id);
    let order_confirmation_detail = data.order_confirmation_detail;
    $('#add_order_confirmation_note').summernote('code', order_confirmation_detail.note);
}
async function addNote(){
    let sale_id = getPathVariable('order-confirmation-print');
    let note = $('#add_order_confirmation_note').summernote('code');
    let formData = JSON.stringify({
        "sale_id": sale_id,
        "note": note
    });
    console.log(formData)
    let returned = await servicePostAddOrderConfirmationDetail(formData);
    // if (returned){
    //     $("#add_note_form").trigger("reset");
    //     $("#addNoteModal").modal('hide');
    //     initSale(sale_id);
    // }
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

    if (quote.oc_pdf == ''){
        $('#no-pdf').removeClass('d-none');
    }else{
        $('#has-pdf').removeClass('d-none');
        $('#showPdf').attr('href', quote.oc_pdf);
    }


}
async function updateQuote(){
    let sale_id = getPathVariable('order-confirmation-print');
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
