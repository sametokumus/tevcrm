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
        await initContact(sale_id);
        await initBankInfoSelect();
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
    let currency = "";
    $.each(offer.products, function (i, product) {
        currency = product.currency;
        let item = '<tr>\n' +
            '           <td>' + checkNull(product.ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.product_name) + '</td>\n' +
            '           <td>' + checkNull(product.quantity) + '</td>\n' +
            '           <td>' + checkNull(product.pcs_price) + ' '+ product.currency +'</td>\n' +
            '           <td>' + checkNull(product.total_price) + ' '+ product.currency +'</td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    });

    $('#sub_total td').text(checkNull(offer.sub_total) + ' ' + currency);
    $('#vat td').text(checkNull(offer.vat) + ' ' + currency);
    $('#grand_total td').text(checkNull(offer.grand_total) + ' ' + currency);

    $('#addNoteBtn').addClass('d-none');
    $('#updateNoteBtn').addClass('d-none');
    let data2 = await serviceGetPurchasingOrderDetailById(offer_id);
    let purchasing_order_detail = data2.purchasing_order_detail;
    if (purchasing_order_detail == null){
        $('#addNoteBtn').removeClass('d-none');
    }else{
        $('#updateNoteBtn').removeClass('d-none');
        $('#note').append(purchasing_order_detail.note);
    }

}

async function openAddNoteModal(){
    $("#addNoteModal").modal('show');
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
    console.log(formData)
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
    console.log(formData)
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




