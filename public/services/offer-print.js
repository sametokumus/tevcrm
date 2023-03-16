(function($) {
    "use strict";

	$(document).ready(function() {

        $('#update_detail_form').submit(function (e){
            e.preventDefault();
            updateDetail();
        });

	});

	$(window).load(async function() {
		checkLogin();
		checkRole();

        let offer_id = getPathVariable('offer-print');
        await initOffer(offer_id);
        await initDetail(offer_id);
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
    let offer_id = getPathVariable('offer-print');
    await initContact(owner, offer_id);
    await initOffer(offer_id);
}

async function initContact(contact_id, offer_id){



    let data = await serviceGetContactById(contact_id);
    let contact = data.contact;
    console.log(contact)
    short_code = contact.short_code;

    $('#offer-print #logo img').remove();
    $('#offer-print #logo').append('<img src="'+ contact.logo +'">');

    if (contact_id == 1){
        $('#print-footer').addClass('lenis-footer');
    }
    $('#print-footer img').remove();
    $('#print-footer').append('<img src="'+ contact.footer +'" alt="" class="w-100">');

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;


    $('#offer-print .logo-header .date').text(Lang.get("strings.Date") +': '+ today);
    $('#offer-print .contact-col address').text('');
    $('#offer-print .contact-col address').append('<strong>'+ contact.name +'</strong><br>'+ contact.address +'<br>'+ contact.phone +'<br>'+ contact.email +'');

}

async function initOffer(offer_id){

    let data = await serviceGetOfferById(offer_id);
    let offer = data.offer;
    let company = offer.company;
    console.log(offer);

    await initContact(3, offer_id);
    await getOwnersAddSelectId('owners');
    document.getElementById('owners').value = 3;

    $('#offer-print .supplier-col address').text('');
    $('#offer-print .supplier-col address').append('<strong>'+ company.name +'</strong><br>'+ company.address +'<br>'+ company.phone +'<br>'+ company.email +'');
    $('#offer-print .logo-header .offer-id').text(short_code+'-RFQ-'+offer.global_id);

    $('#offer-detail tbody > tr').remove();

    $.each(offer.products, function (i, product) {
        let item = '<tr>\n' +
            '           <td class="text-center">' + (i+1) + '</td>\n' +
            '           <td class="text-capitalize">' + checkNull(product.ref_code) + '</td>\n' +
            '           <td class="text-capitalize">' + checkNull(product.product_name) + '</td>\n' +
            '           <td class="text-center">' + checkNull(product.quantity) + '</td>\n' +
            '           <td class="text-center text-capitalize">' + checkNull(product.measurement_name) + '</td>\n' +
            '           <td class="text-center"></td>\n' +
            '           <td class="text-center"></td>\n' +
            '           <td class="text-center"></td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    });

}

async function initDetail(offer_id){
    let data = await serviceGetRfqDetailById(offer_id);
    let detail = data.rfq_detail;

    if (detail != null) {
        document.getElementById('note').innerHTML = checkNull(detail.note);
    }
}

async function openUpdateDetailModal(){
    $("#updateDetailModal").modal('show');
    await initUpdateDetailModal();
}

async function initUpdateDetailModal(){
    let offer_id = getPathVariable('offer-print');
    let data = await serviceGetRfqDetailById(offer_id);
    let detail = data.rfq_detail;
    console.log(detail)

    if (detail != null) {
        $('#update_offer_note').summernote('code', checkNull(detail.note));
    }
}

async function updateDetail(){
    let offer_id = getPathVariable('offer-print');
    let note = $('#update_offer_note').summernote('code');

    let formData = JSON.stringify({
        "offer_id": offer_id,
        "note": note
    });
    let returned = await servicePostUpdateRfqDetail(formData);

    if (returned){
        $("#update_detail_form").trigger("reset");
        $('#updateDetailModal').modal('hide');
        await initDetail(offer_id);
    }else{
        alert("Hata Oluştu");
    }
}
