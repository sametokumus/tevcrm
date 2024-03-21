(function($) {
    "use strict";

	$(document).ready(function() {
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initTests();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initTests(){
	let data = await serviceGetTests();
    $("#test-datatable").dataTable().fnDestroy();
    $('#test-datatable tbody > tr').remove();

    $.each(data.tests, function (i, test) {

        let item = '<tr>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(test.category_name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(test.name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(test.sample_count) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(test.sample_description) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(test.total_day) +' Gün</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ changeCommasToDecimal(test.price) +' ₺</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="update-test/'+ test.id +'" id="bDel" type="button" class="btn  btn-sm btn-theme">\n' +
            '                          <span class="bi bi-pencil-square"></span>\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteTest(\''+ test.id +'\')">\n' +
            '                          <span class="bi bi-trash3"></span>\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '                  </td>\n' +
            '              </tr>';
        $('#test-datatable tbody').append(item);
    });

    $('#test-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Yeni Test',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    window.location = '/add-test';
                }
            }
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function deleteTest(test_id){
    let returned = await serviceGetDeleteTest(test_id);
    if(returned){
        initTests();
    }
}
