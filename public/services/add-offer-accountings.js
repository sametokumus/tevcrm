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

    let radios = document.getElementsByName('discountRadio');
    let discount_type;
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            discount_type = radios[i].value;
            break;
        }
    }

    let formData = JSON.stringify({
        "discount_type": discount_type,
        "discount": changePriceToDecimal(document.getElementById('offer_discount').value),
        "vat_rate": document.getElementById('offer_vat_rate').value
    });
    console.log(formData);

    let returned = await servicePostUpdateOfferSummary(formData, offer_id);
    if (returned){
        initOfferSummary();
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

    document.getElementById('summary_test_total').value = changeCommasToDecimal(summary.test_total);
    document.getElementById('summary_discount').value = changeCommasToDecimal(summary.discount);
    document.getElementById('summary_sub_total').value = changeCommasToDecimal(summary.sub_total);
    document.getElementById('summary_vat').value = summary.vat;
    document.getElementById('summary_grand_total').value = changeCommasToDecimal(summary.grand_total);
}

