(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#offer_test_total").maskMoney({thousands:'.', decimal:','});
        $("#offer_discount").maskMoney({thousands:'.', decimal:','});
        $("#offer_vat_rate").maskMoney({thousands:'', decimal:'.'});

        $('#offer_summary_form').submit(function (e){
            e.preventDefault();
            updateOfferSummary();
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initOfferSummary();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function updateOfferSummary(){

    let formData = JSON.stringify({
        "customer": document.getElementById('offer_customer').value,
        "employee": document.getElementById('offer_employee').value,
        "manager": document.getElementById('offer_manager').value,
        "lab_manager": document.getElementById('offer_lab_manager').value,
        "description": document.getElementById('offer_description').value
    });
    console.log(formData);

    let data = await servicePostAddOffer(formData);
    if (data.status == "success"){
        window.location = "add-offer-tests/" + data.object.offer_id;
    }
}
async function initOfferSummary(){

    let offer_id = getPathVariable('add-offer-accountings');

    let data = await serviceGetOfferSummaryById(offer_id);
    let summary = data.summary;

    $('#summary_test_total').html(changeCommasToDecimal(summary.test_total));
    $('#summary_discount').html(checkNull(changeCommasToDecimal(summary.discount)));
    $('#summary_sub_total').html(checkNull(changeCommasToDecimal(summary.sub_total)));
    $('#summary_vat').html(checkNull(changeCommasToDecimal(summary.vat)));
    $('#summary_grand_total').html(checkNull(changeCommasToDecimal(summary.grand_total)));

    document.getElementById('offer_test_total').value = changePriceToDecimal(summary.test_total);
}

