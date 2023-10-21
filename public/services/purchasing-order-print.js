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

		$('#add_note_form').submit(function (e){
			e.preventDefault();
            addNote();
		});

		$('#update_note_form').submit(function (e){
			e.preventDefault();
            updateNote();
		});

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        let sale_id = getPathVariable('purchasing-order-print');
        await initOfferSelect(sale_id);
        await initBankInfoSelect();
        // await initContact(1, sale_id);
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
    let sale_id = getPathVariable('purchasing-order-print');
    let offer_id = document.getElementById('select_offer').value;

    // Fetch the PDF data
    const pdfData = await serviceGetGenerateOrderConfirmationPDF(lang, owner_id, sale_id, offer_id);

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
    let sale_id = getPathVariable('purchasing-order-print');
    // await initOfferSelect(sale_id);
    // await initContact(owner, sale_id);
    // await initBankInfoSelect();
}

async function changeOffer(){

    document.getElementById('supplier_name').innerHTML = "";
    document.getElementById('supplier_address').innerHTML = "";
    $('#sub_total td').text("");
    $('#vat td').text("");
    $('#grand_total td').text("");
    $('#offer-detail tbody > tr').remove();
    $('#note *').remove();
    $('#bank-details *').remove();

    let offer_id = document.getElementById('select_offer').value;
    if(offer_id == 0){
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
    await initContact(sale.owner_id, sale_id);
    await getOwnersAddSelectId('owners');
    document.getElementById('owners').value = sale.owner_id;

    console.log(sale)

    $.each(sale.sale_offers, function (i, offer) {
        let item = '<option value="'+ offer.offer_id +'">'+ offer.supplier_name +'</option>';

        let options = document.querySelectorAll('select#select_offer option');
        let control = false;
        options.forEach(option => {
            if (option.value == offer.offer_id){control = true;}
        });
        if (!control) {
            $('#select_offer').append(item);
        }
    });

}

async function initContact(contact_id, sale_id){

    let data = await serviceGetContactById(contact_id);
    let contact = data.contact;
    short_code = contact.short_code;
    let width = '150px';
    if (contact_id == 3){
        width = '250px';
    }

    $('#purchasing-order-print #logo img').remove();
    $('#purchasing-order-print #logo').append('<img style="width: '+width+'" src="'+ contact.logo +'">');

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
    $('#purchasing-order-print .logo-header .date').text(Lang.get("strings.Date") +': '+ today);
    // $('#purchasing-order-print .logo-header .offer-id').append(sale_id);
    $('.company-signature .name').text(contact.authorized_name);
    // document.getElementById('signature').src = contact.signature;
    $('.company-signature #signature img').remove();
    $('.company-signature #signature').append('<img src="'+ contact.signature +'" style="height: 70px; width: auto;" >');
    // document.getElementById('signature').style.backgroundImage = "url('"+contact.signature+"')";

    $('#purchasing-order-print .contact-col address').text('');

    $('#purchasing-order-print .contact-col address').append('<strong>'+ contact.name +'</strong><br>');
    if (contact.registration_no != '') {
        $('#purchasing-order-print .contact-col address').append('<b>' + Lang.get("strings.Registration No") + ' :</b> ' + contact.registration_no + '&nbsp;&nbsp;&nbsp;');
    }
    let lang = Lang.getLocale();
    if (contact.registration_office != '' && lang != 'en') {
        $('#purchasing-order-print .contact-col address').append('<b>' + Lang.get("strings.Registration Office") + ' :</b> ' + contact.registration_office);
    }
    $('#purchasing-order-print .contact-col address').append('<br>');
    $('#purchasing-order-print .contact-col address').append('<b>'+ Lang.get("strings.Address") +'</b><br>'+ contact.address +'<br>');
    $('#purchasing-order-print .contact-col address').append('<b>'+ Lang.get("strings.Phone") +':</b> '+ contact.phone +'<br>');
    $('#purchasing-order-print .contact-col address').append('<b>'+ Lang.get("strings.Email") +':</b> '+ contact.email +'');
}

async function initOffer(offer_id){
    let data = await serviceGetSaleOffersByOfferId(offer_id);
    let offer = data.offer;
    let company = offer.company;

    // $('#purchasing-order-print .supplier-col address').append('<strong>'+ company.name +'</strong><br>'+ company.address +'<br>Phone: '+ company.phone +'<br>Email: '+ company.email +'');
    document.getElementById('supplier_name').innerHTML = checkNull(company.name);
    document.getElementById('supplier_address').innerHTML = '<b>'+ Lang.get("strings.Address") +' :</b> '+ checkNull(company.address);
    // document.getElementById('payment_term').innerHTML = '<b>Payment Terms :</b> '+ checkNull(quote.payment_term);


    $('#purchasing-order-print .logo-header .offer-id').text(short_code+'-PO-'+offer.global_id);

    $('#offer-detail tbody > tr').remove();
    let currency = "";
    $.each(offer.products, function (i, product) {
        currency = product.currency;
        let lead_time = checkNull(product.lead_time);
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
            '           <td class="text-capitalize">' + checkNull(product.ref_code) + '</td>\n' +
            '           <td class="text-capitalize">' + checkNull(product.product_name) + '</td>\n' +
            '           <td class="text-center">' + checkNull(product.quantity) + '</td>\n' +
            '           <td class="text-center text-capitalize">' + checkNull(measurement_name) + '</td>\n' +
            '           <td class="text-center">' + changeCommasToDecimal(product.pcs_price) + ' '+ product.currency +'</td>\n' +
            '           <td class="text-center">' + changeCommasToDecimal(product.total_price) + ' '+ product.currency +'</td>\n' +
            '           <td class="text-center">' + lead_time + '</td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    });

    if (offer.sub_total != null) {
        let text = Lang.get("strings.Sub Total");
        if (offer.vat == null || offer.vat == '0.00'){
            text = Lang.get("strings.Grand Total");
        }
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + text + '</td>\n' +
            '           <td colspan="2" class="text-center">' + changeCommasToDecimal(offer.sub_total) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    }

    if (offer.vat != null && offer.vat != '0.00') {
        let item = '<tr>\n' +
            '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Vat") + '</td>\n' +
            '           <td colspan="2" class="text-center">' + changeCommasToDecimal(offer.vat) + ' '+ currency +'</td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    }

    if (offer.grand_total != null) {
        if (offer.vat != null && offer.vat != '0.00') {
            let item = '<tr>\n' +
                '           <td colspan="6" class="fw-800 text-right text-uppercase">' + Lang.get("strings.Grand Total") + '</td>\n' +
                '           <td colspan="2" class="text-center">' + changeCommasToDecimal(offer.grand_total) + ' ' + currency + '</td>\n' +
                '       </tr>';
            $('#offer-detail tbody').append(item);
        }
    }


    $('#addNoteBtn').addClass('d-none');
    $('#updateNoteBtn').addClass('d-none');
    let data2 = await serviceGetPurchasingOrderDetailById(offer_id);
    let purchasing_order_detail = data2.purchasing_order_detail;
    if (purchasing_order_detail == null){
    }else{
        $('#add_purchasing_order_note').summernote('code', purchasing_order_detail.note);
    }

    if (offer.file_url == null){
        $('#no-pdf').removeClass('d-none');
    }else{
        $('#has-pdf').removeClass('d-none');
        $('#showPdf').attr('href', offer.file_url);
    }

}

async function addNote(){
    let sale_id = getPathVariable('purchasing-order-print');
    let offer_id = document.getElementById('select_offer').value;
    let note = $('#add_purchasing_order_note').summernote('code');
    let formData = JSON.stringify({
        "sale_id": sale_id,
        "offer_id": offer_id,
        "note": note
    });
    let returned = await servicePostAddPurchasingOrderDetail(formData);
    if (returned){
        $("#add_note_form").trigger("reset");
        $("#addNoteModal").modal('hide');
        initOffer(offer_id);
    }
}

async function openUpdateNoteModal(){
    $("#updateNoteModal").modal('show');
    await initUpdateNoteModal();
}
async function initUpdateNoteModal(){
    $("#updateNoteModal").modal('show');
    let offer_id = document.getElementById('select_offer').value;
    let data = await serviceGetPurchasingOrderDetailById(offer_id);
    let purchasing_order_detail = data.purchasing_order_detail;
    $('#update_purchasing_order_note').summernote('code', purchasing_order_detail.note);
}
async function updateNote(){
    let sale_id = getPathVariable('purchasing-order-print');
    let offer_id = document.getElementById('select_offer').value;
    let note = $('#update_purchasing_order_note').summernote('code');
    let formData = JSON.stringify({
        "sale_id": sale_id,
        "offer_id": offer_id,
        "note": note
    });
    let returned = await servicePostUpdatePurchasingOrderDetail(formData);
    if (returned){
        $("#update_note_form").trigger("reset");
        $('#note *').remove();
        $("#updateNoteModal").modal('hide');
        initOffer(offer_id);
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




