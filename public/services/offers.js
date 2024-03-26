(function($) {
    "use strict";

	$(document).ready(function() {
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initOffers();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initOffers(){
	let data = await serviceGetOffers();
    $("#offer-datatable").dataTable().fnDestroy();
    $('#offer-datatable tbody > tr').remove();

    $.each(data.offers, function (i, offer) {

        let employee = '';
        if (offer.employee != null){
            employee = offer.employee.name;
        }

        let manager = '';
        if (offer.manager != null){
            manager = offer.manager.name + ' ' + offer.manager.surname;
        }

        let price = offer.accounting.test_total;
        if (offer.accounting.sub_total != null){
            price = offer.accounting.sub_total;
        }
        if(offer.accounting.grand_total != null){
            price = offer.accounting.grand_total;
        }
        console.log(price)
        let item = '<tr>\n' +
            '                  <td class="bg-light-green bg-opacity-50">\n' +
            '                      <p class="mb-0">'+ offer.global_id +'</p>\n' +
            '                  </td>\n' +
            '                  <td class="bg-light-green bg-opacity-50">\n' +
            '                      <p class="mb-0">'+ checkNull(offer.customer.name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(employee) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(manager) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ changePriceToDecimal(price) +' ₺</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ formatDateAndTimeDESC2(offer.created_at, "-") +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(offer.status.name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="update-offer/'+ offer.id +'" id="bDel" type="button" class="btn  btn-sm btn-theme">\n' +
            '                          <span class="bi bi-pencil-square"></span>\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteTest(\''+ offer.id +'\')">\n' +
            '                          <span class="bi bi-trash3"></span>\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '                  </td>\n' +
            '              </tr>';
        $('#offer-datatable tbody').append(item);
    });

    $('#offer-datatable').DataTable({
        responsive: false,
        columnDefs: [
            {
                targets: 1,
                className: 'ellipsis',
                render: function(data, type, row, meta) {
                    return type === 'display' && data.length > 30 ?
                        data.substr(0, 30) + '...' :
                        data;
                }
            },
            {
                type: 'date',
                targets: 5,
                render: function(data, type, row) {
                    return moment(data, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                }
            }
        ],
        dom: 'Bfrtip',
        paging: false,
        buttons: [
            ],
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: [
            [0, 'desc']
        ],
        fixedColumns: {
            left: 2
        }
    });

}

async function deleteTest(test_id){
    let returned = await serviceGetDeleteTest(test_id);
    if(returned){
        initTests();
    }
}
