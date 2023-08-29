(function($) {
    "use strict";

    $(document).ready(function() {

        $(":input").inputmask();
        $("#update_sale_shipping_price").maskMoney({thousands:'.', decimal:','});

        $('#update_note_form').submit(function (e){
            e.preventDefault();
            updateNote();
        });

    });

    $(window).load(async function() {

        checkLogin();
        checkRole();

        let packing_list_id = getPathVariable('packing-list-print');
        await initSale(packing_list_id);
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
    let sale_id = getPathVariable('proforma-invoice-print');
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
    let data = await serviceGetPackingListProductsById(packing_list_id);
    console.log(data);
    let sale = data.sale;
    await initContact(sale.owner_id, sale.sale_id);
    await getOwnersAddSelectId('owners');
    document.getElementById('owners').value = sale.owner_id;
    let company = sale.request.company;

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

    $('#quote-print .logo-header .offer-id').text(short_code+'-PL-'+sale.id);

    $('#sale-detail tbody > tr').remove();

    $.each(sale.sale_offers, function (i, product) {
        currency = product.offer_currency;
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
            '           <td class="text-center">' + (i+1) + '</td>\n' +
            '           <td class="text-capitalize">' + checkNull(product.product_ref_code) + '</td>\n' +
            '           <td class="text-capitalize">' + checkNull(product.product_name) + '</td>\n' +
            '           <td class="text-center">' + checkNull(product.packing_count) + '</td>\n' +
            '           <td class="text-center text-capitalize">' + checkNull(measurement_name) + '</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });


    if (sale.packing_note == null){
    }else{
        $('#note').append(sale.packing_note);
        $('#update_packing_note').summernote('code', sale.packing_note);
    }

}

async function openUpdateNoteModal(){
    $("#updateNoteModal").modal('show');
}
async function updateNote(){
    let packing_list_id = getPathVariable('packing-list-print');
    let note = $('#update_packing_note').summernote('code');
    let formData = JSON.stringify({
        "packing_list_id": packing_list_id,
        "note": note
    });
    let returned = await servicePostUpdatePackingNote(formData);
    if (returned){
        $("#update_note_form").trigger("reset");
        $('#note *').remove();
        $("#updateNoteModal").modal('hide');
        initSale(packing_list_id);
    }
}
