(function($) {
    "use strict";

	$(document).ready(function() {
        $('#add_document_form').submit(function (e){
            e.preventDefault();
            addDocument();
        });
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
        getDocumentTypesAddSelectId('add_document_type');
        initDocuments();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initDocuments(){
    let sale_id = getPathVariable('mobile-documents');
	let data = await serviceGetMobileDocuments(sale_id);
    $("#document-datatable").dataTable().fnDestroy();
    $('#document-datatable tbody > tr').remove();

    $.each(data.documents, function (i, document) {
        let typeItem = '<tr>\n' +
            '              <td>'+ document.id +'</td>\n' +
            '              <td>'+ document.type_name +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="'+ api_url + document.file_url +'" target="_blank" id="bDel" type="button" class="btn  btn-sm btn-warning">\n' +
            '                          <span class="fe fe-search"> </span> Görüntüle\n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteDocument(\''+ document.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#document-datatable tbody').append(typeItem);
    });

    $('#document-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });

}

async function addDocumentCallback(xhttp){
    let jsonData = await xhttp.responseText;
    const obj = JSON.parse(jsonData);
    showAlert(obj.message);
    console.log(obj)
    $("#add_document_form").trigger("reset");
    $("#addDocumentModal").modal('hide');
    initDocuments();
}
async function addDocument(){
    let sale_id = getPathVariable('mobile-documents');

    let formData = new FormData();
    formData.append('document_type_id', document.getElementById('add_document_type').value);
    formData.append('file', document.getElementById('add_document_file').files[0]);
    console.log(formData);

    await servicePostAddMobileDocument(formData, sale_id);
}

async function deleteDocument(document_id){
    let returned = await serviceGetDeleteMobileDocument(document_id);
    if(returned){
        initDocuments();
    }
}
