(function($) {
    "use strict";

	$(document).ready(function() {
        $('#add_document_type_form').submit(function (e){
            e.preventDefault();
            addDocumentType();
        });
        $('#update_document_type_form').submit(function (e){
            e.preventDefault();
            updateDocumentType();
        });
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
                text: 'Yeni Döküman Türü',
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

async function openAddDocumentTypeModal(){
    $('#addDocumentTypeModal').modal('show');
}

async function addDocumentType(){
    let name = document.getElementById('add_document_type_name').value;
    let formData = JSON.stringify({
        "name": name
    });
    let returned = await servicePostAddDocumentType(formData);
    if(returned){
        $("#add_document_type_form").trigger("reset");
        $('#addDocumentTypeModal').modal('hide');
        initDocumentTypes();
    }
}

async function openUpdateDocumentTypeModal(type_id){
    $('#updateDocumentTypeModal').modal('show');
    await initUpdateDocumentTypeModal(type_id);
}

async function initUpdateDocumentTypeModal(type_id){
    let data = await serviceGetDocumentTypeById(type_id);
    let document_type = data.document_type;
    document.getElementById('update_document_type_id').value = document_type.id;
    document.getElementById('update_document_type_name').value = document_type.name;
}

async function updateDocumentType(){
    let id = document.getElementById('update_document_type_id').value;
    let name = document.getElementById('add_document_type_name').value;
    let formData = JSON.stringify({
        "name": name
    });
    let returned = await servicePostUpdateDocumentType(id, formData);
    if(returned){
        $("#update_document_type_form").trigger("reset");
        $('#updateDocumentTypeModal').modal('hide');
        initDocumentTypes();
    }
}

async function deleteDocumentType(type_id){
    let returned = await serviceGetDeleteDocumentType(type_id);
    if(returned){
        initDocumentTypes();
    }
}
