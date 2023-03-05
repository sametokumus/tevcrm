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

        let sale_id = getPathVariable('order-confirmation-print');
        await initContact(1, sale_id);
        await initSale(sale_id);
        await getOwnersAddSelectId('owners');
        document.getElementById('owners').value = 1;
    });

})(window.jQuery);
let short_code;

function checkRole(){
    return true;
}

function printOffer(){
    window.print();
}

async function changeOwner(){
    let owner = document.getElementById('owners').value;
    let sale_id = getPathVariable('order-confirmation-print');
    await initContact(owner, sale_id);
    await initSale(sale_id);
}

async function initContact(contact_id, sale_id){

    let data = await serviceGetContactById(contact_id);
    let contact = data.contact;
    short_code = contact.short_code;

    $('#order-confirmation-print #logo img').remove();
    $('#order-confirmation-print #logo').append('<img src="'+ contact.logo +'">');

    $('#print-footer img').remove();
    $('#print-footer').append('<img src="'+ contact.footer +'" alt="" class="w-100">">');

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    $('#order-confirmation-print .logo-header .date').text(Lang.get("strings.Date") +': '+ today);
    // $('#order-confirmation-print .logo-header .offer-id').append(sale_id);

    $('#order-confirmation-print .contact-col address').text('');
    $('#order-confirmation-print .contact-col address').append('<strong>'+ contact.name +'</strong><br><b>'+ Lang.get("strings.Registration No") +' :</b> '+ contact.registration_no +'<br><b>'+ Lang.get("strings.Address") +'</b><br>'+ contact.address +'<br><b>'+ Lang.get("strings.Phone") +':</b> '+ contact.phone +'<br><b>'+ Lang.get("strings.Email") +':</b> '+ contact.email +'');

}

async function initSale(sale_id){
    let data = await serviceGetSaleById(sale_id);
    console.log(data);
    let sale = data.sale;
    let company = sale.request.company;
    document.getElementById('buyer_name').innerHTML = '<b>'+ Lang.get("strings.Customer") +' :</b> '+ company.name;
    document.getElementById('buyer_registration_number').innerHTML = '<b>'+ Lang.get("strings.Registration No") +' :</b> '+ checkNull(company.registration_number);
    document.getElementById('buyer_address').innerHTML = '<b>'+ Lang.get("strings.Address") +' :</b> '+ company.address;
    document.getElementById('buyer_phone').innerHTML = '<b>'+ Lang.get("strings.Phone") +' :</b> '+ company.phone;
    document.getElementById('buyer_email').innerHTML = '<b>'+ Lang.get("strings.Email") +' :</b> '+ company.email;

    $('#order-confirmation-print .logo-header .offer-id').text(short_code+'-OC-'+sale.id);

    $('#sale-detail tbody > tr').remove();

    let currency = '';
    $.each(sale.sale_offers, function (i, product) {
        currency = product.currency;
        let item = '<tr>\n' +
            '           <td>' + (i+1) + '</td>\n' +
            '           <td>' + checkNull(product.product_ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.product_name) + '</td>\n' +
            '           <td>' + checkNull(product.offer_quantity) + ' (' + checkNull(product.measurement_name) + ')</td>\n' +
            '           <td>' + changeCommasToDecimal(product.offer_pcs_price) + ' '+ currency +'</td>\n' +
            '           <td>' + changeCommasToDecimal(product.offer_price) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });

    if (sale.sub_total != null) {
        let item = '<tr>\n' +
            '           <td colspan="5" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Sub Total") + '</td>\n' +
            '           <td>' + changeCommasToDecimal(sale.sub_total) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

    if (sale.freight != null) {
        let item = '<tr>\n' +
            '           <td colspan="5" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Freight") + '</td>\n' +
            '           <td>' + changeCommasToDecimal(sale.freight) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

    if (sale.vat != null) {
        let item = '<tr>\n' +
            '           <td colspan="5" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Vat") + '</td>\n' +
            '           <td>' + changeCommasToDecimal(sale.vat) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

    if (sale.grand_total != null) {
        let item = '<tr>\n' +
            '           <td colspan="5" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Grand Total") + '</td>\n' +
            '           <td>' + changeCommasToDecimal(sale.grand_total) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    }

}
