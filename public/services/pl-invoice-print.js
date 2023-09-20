(function($) {
    "use strict";

    $(document).ready(function() {

        $(":input").inputmask();
        $("#update_sale_shipping_price").maskMoney({thousands:'.', decimal:','});

        $('#update_detail_form').submit(function (e){
            e.preventDefault();
            updateDetail();
        });

    });

    $(window).load(async function() {

        checkLogin();
        checkRole();

        let packing_list_id = getPathVariable('pl-invoice-print');
        // await initContact(1, sale_id);
        let sale_id = await initSale(packing_list_id);
        await initDetail(sale_id);
        await initBankInfoSelect();
        // await getOwnersAddSelectId('owners');
        // document.getElementById('owners').value = 1;
    });

})(window.jQuery);
let short_code;
let currency = "";

function checkRole(){
    return true;
}

function printOffer(){
    window.print();
}

async function changeOwner(){
    let owner = document.getElementById('owners').value;
    let sale_id = getPathVariable('pl-invoice-print');
    await initContact(owner, sale_id);
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

async function initSale(packing_list_id){
    let data = await serviceGetPackingListSaleById(packing_list_id);
    console.log(data);
    let sale = data.sale;
    let sale_id = sale.sale_id;
    await initContact(sale.owner_id, sale_id);
    await getOwnersAddSelectId('owners');
    document.getElementById('owners').value = sale.owner_id;
    let company = sale.request.company;

    document.getElementById('buyer_name').innerHTML = '<b>'+ Lang.get("strings.Customer") +' :</b> '+ company.name;
    if (checkNull(company.registration_number) != '') {
        document.getElementById('buyer_registration_number').innerHTML = '<b>'+ Lang.get("strings.Registration No") +' :</b> '+ checkNull(company.registration_number);
    }else{
        $('#buyer_registration_number').addClass('d-none');
        $('.buyer-col br:first').remove();
    }
    document.getElementById('buyer_address').innerHTML = '<b>'+ Lang.get("strings.Address") +' :</b> '+ company.address;
    document.getElementById('buyer_phone').innerHTML = '<b>'+ Lang.get("strings.Phone") +' :</b> '+ company.phone;
    document.getElementById('buyer_email').innerHTML = '<b>'+ Lang.get("strings.Email") +' :</b> '+ company.email;

    $('#quote-print .logo-header .offer-id').text(short_code+'-CI-'+sale.id);

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
            '           <td class="text-center">' + checkNull(product.list_quantity) + '</td>\n' +
            '           <td class="text-center text-capitalize">' + checkNull(measurement_name) + '</td>\n' +
            '           <td class="text-center">' + changeCommasToDecimal(product.offer_pcs_price) + ' '+ currency +'</td>\n' +
            '           <td class="text-center">' + checkNull(product.offer_price) + ' '+ currency +'</td>\n' +
            // '           <td class="text-center">' + lead_time + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });
    document.getElementById('update_sale_shipping_price').value = checkNull(sale.shipping_price);


    if (sale.list_total != null) {
        let key = Lang.get("strings.Grand Total");
        if (sale.list_grand_total != '0,00' && sale.list_tax != '0,00') {
            key = Lang.get("strings.Sub Total");
        }
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + key + '</td>\n' +
            '           <td colspan="1" class="text-center">' + changeCommasToDecimal(sale.list_total) + ' ' + currency + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }
    if (sale.list_grand_total != '0,00' && sale.list_tax != '0,00') {
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Vat") + '</td>\n' +
            '           <td colspan="1" class="text-center">' + changeCommasToDecimal(sale.list_tax) + ' ' + currency + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
        let item2 = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Grand Total") + '</td>\n' +
            '           <td colspan="1" class="text-center">' + changeCommasToDecimal(sale.list_grand_total) + ' ' + currency + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item2);
    }

    return sale_id;

}

async function initDetail(sale_id){
    let data = await serviceGetProformaInvoiceDetailById(sale_id);
    let detail = data.proforma_invoice_detail;

    if (detail != null) {
        document.getElementById('payment_term').innerHTML = '<b>'+ Lang.get("strings.Payment Terms") +' :</b> ' + checkNull(detail.payment_term);
        document.getElementById('note').innerHTML = checkNull(detail.note);
    }
}

async function openUpdateDetailModal(){
    $("#updateDetailModal").modal('show');
    await initUpdateDetailModal();
}

async function initUpdateDetailModal(){
    let sale_id = getPathVariable('pl-invoice-print');
    let data = await serviceGetProformaInvoiceDetailById(sale_id);
    let detail = data.proforma_invoice_detail;
    console.log(detail)

    if (detail != null) {
        document.getElementById('update_sale_payment_term').value = checkNull(detail.payment_term);
        $('#update_sale_note').summernote('code', checkNull(detail.note));
    }
}

async function updateDetail(){
    let sale_id = getPathVariable('pl-invoice-print');
    let payment_term = document.getElementById('update_sale_payment_term').value;
    let note = $('#update_sale_note').summernote('code');
    let shipping_price = document.getElementById('update_sale_shipping_price').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "payment_term": payment_term,
        "note": note
    });
    let returned1 = await servicePostUpdateProformaInvoiceDetail(formData);

    let formData2 = JSON.stringify({
        "sale_id": sale_id,
        "shipping_price": changePriceToDecimal(shipping_price)
    });
    let returned2 = await servicePostUpdateShippingPrice(formData2);


    if (returned1 && returned2){
        $("#update_detail_form").trigger("reset");
        $('#updateDetailModal').modal('hide');
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
async function openAddBankInfoModal(){
    $("#addBankInfoModal").modal('show');
}
async function changeBankInfo(){
    $('#bank-details *').remove();

    let bank_id = document.getElementById('select_bank_info').value;
    if(bank_id == 0){
        return false;
    }else{
        let data = await serviceGetBankInfoById(bank_id);
        let info = data.bank_info;
        $('#bank-details').append(info.detail);
    }

    $("#addBankInfoModal").modal('hide');
}
