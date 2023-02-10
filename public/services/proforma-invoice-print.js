(function($) {
    "use strict";

    $(document).ready(function() {

        $(":input").inputmask();
        $("#update_sale_shipping_price").maskMoney({thousands:''});

        $('#update_detail_form').submit(function (e){
            e.preventDefault();
            updateDetail();
        });

    });

    $(window).load(async function() {

        checkLogin();
        checkRole();

        let sale_id = getPathVariable('proforma-invoice-print');
        await initContact(sale_id);
        await initSale(sale_id);
        await initDetail(sale_id);
        await initBankInfoSelect();
    });

})(window.jQuery);

let currency = "";

function checkRole(){
    return true;
}

function printOffer(){
    window.print();
}

async function initContact(sale_id){

    let data = await serviceGetContactById(1);
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

    document.getElementById('buyer_name').innerHTML = '<b>Buyer :</b> '+ company.name;
    document.getElementById('buyer_address').innerHTML = '<b>Address :</b> '+ company.address;


    $('#sale-detail tbody > tr').remove();

    $.each(sale.sale_offers, function (i, product) {
        currency = product.currency;
        let item = '<tr>\n' +
            '           <td>' + checkNull(product.product_ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.product_name) + '</td>\n' +
            '           <td>' + checkNull(product.offer_quantity) + '</td>\n' +
            '           <td>' + checkNull(product.offer_pcs_price) + ' '+ product.currency +'</td>\n' +
            '           <td>' + checkNull(product.offer_price) + ' '+ product.currency +'</td>\n' +
            '       </tr>';
        $('#sale-detail tbody').append(item);
    });


    $('#sub_total td').text(checkNull(sale.sub_total) + ' ' + currency);
    $('#freight td').text(checkNull(sale.freight));
    $('#vat td').text(checkNull(sale.vat) + ' ' + currency);
    $('#grand_total td').text(checkNull(sale.grand_total) + ' ' + currency);
    $('#shipping td').text(checkNull(sale.shipping_price) + ' ' + currency);
    $('#grand_total_with_shipping td').text(checkNull(sale.grand_total_with_shipping) + ' ' + currency);


    document.getElementById('update_sale_shipping_price').value = checkNull(sale.shipping_price);

}

async function initDetail(sale_id){
    let data = await serviceGetProformaInvoiceDetailById(sale_id);
    let detail = data.proforma_invoice_detail;

    if (detail != null) {
        document.getElementById('payment_term').innerHTML = '<b>Payment Terms :</b> ' + checkNull(detail.sale.shipping);
        document.getElementById('note').innerHTML = checkNull(detail.note);
    }
}

async function openUpdateDetailModal(){
    $("#updateDetailModal").modal('show');
    await initUpdateDetailModal();
}

async function initUpdateDetailModal(){
    let sale_id = getPathVariable('proforma-invoice-print');
    let data = await serviceGetProformaInvoiceDetailById(sale_id);
    let detail = data.proforma_invoice_detail;
    console.log(detail)

    if (detail != null) {
        document.getElementById('update_sale_payment_term').value = checkNull(detail.payment_term);
        $('#update_sale_note').summernote('code', checkNull(detail.note));
    }
}

async function updateDetail(){
    let sale_id = getPathVariable('proforma-invoice-print');
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
        "shipping_price": shipping_price
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
