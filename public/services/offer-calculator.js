(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();

        $('#offer_category').on('change', function (e){
            e.preventDefault();
            let category_id = document.getElementById('offer_category').value;
            console.log(category_id)
            if (category_id == 'Kategori Seçiniz'){
                $('#offer_test option').remove();
            }else{
                getTestsByCategoryAddSelectId(category_id, 'offer_test');
            }
        });

        $('#offer_test_btn').on('click', function (e){
            let test_id = document.getElementById('offer_test').value;
            addTestToOffer(test_id);
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        getCategoriesAddSelectId('offer_category');

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function addTestToOffer(test_id){
    let data = await serviceGetTestById(test_id);
    let test = data.test;
    let total_price = document.getElementById('offer_price').value;
    total_price = parseFloat(total_price) + parseFloat(test.price);
    document.getElementById('offer_price').value = total_price;
    $('#view-offer-price').html(changeCommasToDecimal(parseFloat(total_price).toFixed(2)) + ' ₺');

    let item = '<tr class="test-item">\n' +
        '                  <td>'+ checkNull(test.name) +'</td>\n' +
        '                  <td>'+ checkNull(test.sample_count) +'</td>\n' +
        '                  <td>'+ checkNull(test.sample_description) +'</td>\n' +
        '                  <td>'+ checkNull(test.total_day) +' Gün</td>\n' +
        '                  <td>'+ changeCommasToDecimal(test.price) +' ₺</td>\n' +
        '                  <td><button type="button" onclick="removeTestItem(this, '+test.price+')" class="btn btn-sm btn-theme offer_remove_test_btn w-100">Tekliften Çıkar</button></td>\n' +
        '              </tr>';

    $('#tests-table tbody').append(item);
}
async function removeTestItem(element, price){
    let total_price = document.getElementById('offer_price').value;
    total_price = parseFloat(total_price) - parseFloat(price);
    document.getElementById('offer_price').value = total_price;
    $('#view-offer-price').html(changeCommasToDecimal(parseFloat(total_price).toFixed(2)) + ' ₺');

    $(element).closest('.test-item').remove();
}
