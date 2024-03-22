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
    console.log(test)
    let total_price = document.getElementById('offer_price').value;
    total_price = parseFloat(total_price) + parseFloat(test.price);
    document.getElementById('offer_price').value = total_price;
    $('#view-offer-price').html(changeCommasToDecimal(parseFloat(total_price).toFixed(2)) + ' ₺');

    let item = '<div class="card border-0 mb-4 test-item">\n' +
        '                  <div class="card-body">\n' +
        '                      <div class="row">\n' +
        '                          <div class="col-12 mb-4 mb-lg-0">\n' +
        '                              <div class="row align-items-center">\n' +
        '                                  <div class="col">\n' +
        '                                      <h6 class="fw-medium">'+ test.name +'</h6>\n' +
        '                                  </div>\n' +
        '                                  <div class="col-auto">\n' +
        '                                      <h6 class="fw-medium">'+ changeCommasToDecimal(test.price) +' ₺</h6>\n' +
        '                                  </div>\n' +
        '                                  <div class="col-auto">\n' +
        '                                      <button type="button" onclick="removeTestItem(element, '+test.price+')" class="btn btn-sm btn-theme offer_remove_test_btn">Tekliften Çıkar</button>\n' +
        '                                  </div>\n' +
        '                              </div>\n' +
        '                          </div>\n' +
        '                      </div>\n' +
        '                  </div>\n' +
        '              </div>';

    $('#test-block').append(item);
}
async function removeTestItem(element, price){
    let total_price = document.getElementById('offer_price').value;
    total_price = parseFloat(total_price) - parseFloat(price);
    document.getElementById('offer_price').value = total_price;
    $('#view-offer-price').html(changeCommasToDecimal(parseFloat(total_price).toFixed(2)) + ' ₺');

    console.log(element)
}
