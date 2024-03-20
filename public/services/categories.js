(function($) {
    "use strict";

	$(document).ready(function() {
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initCategories();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initCategories(){
	let data = await serviceGetCategories();
    $("#category-datatable").dataTable().fnDestroy();
    $('#category-datatable tbody > tr').remove();

    $.each(data.categories, function (i, category) {

        let item = '<tr>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(category.name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="update-category/'+ category.id +'" id="bDel" type="button" class="btn  btn-sm btn-theme">\n' +
            '                          <span class="bi bi-pencil-square"></span>\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteCategory(\''+ category.id +'\')">\n' +
            '                          <span class="bi bi-trash3"></span>\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '                  </td>\n' +
            '              </tr>';
        $('#category-datatable tbody').append(item);
    });

    $('#category-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Yeni Kategori',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    window.location = '/add-category';
                }
            }
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function deleteCategory(category_id){
    let returned = await serviceGetDeleteCategory(category_id);
    if(returned){
        initCategories();
    }
}
