(function($) {
    "use strict";

	$(document).ready(function() {
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initDocumentTypes();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initDocumentTypes(){
	let data = await serviceGetDocumentTypes();
    $("#types-datatable").dataTable().fnDestroy();
    $('#types-datatable tbody > tr').remove();

    $.each(data.document_types, function (i, document_type) {

        let item = '<tr>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(document_type.name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openUpdateDocumentTypeModal(\''+ document_type.id +'\')">\n' +
            '                          <span class="bi bi-trash3"></span>\n' +
            '                      </button>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteDocumentType(\''+ document_type.id +'\')">\n' +
            '                          <span class="bi bi-trash3"></span>\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '                  </td>\n' +
            '              </tr>';
        $('#types-datatable tbody').append(item);

    });

    $('#types-datatable').DataTable({
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
                    openAddDocumentTypeModal();
                }
            }
        ],
        ordering: false,
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function deleteDocumentType(type_id){
    let returned = await serviceGetDeleteDocumentType(type_id);
    if(returned){
        initDocumentTypes();
    }
}
