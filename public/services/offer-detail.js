(function($) {
    "use strict";

	$(document).ready(function() {
        $('#update_status_form').submit(function (e){
            e.preventDefault();
            updateStatus();
        });

        $('#add_document_form').submit(function (e){
            e.preventDefault();
            addDocument();
        });

	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initOfferDetail();
        initOfferHistory();
        initDocuments();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initOfferDetail(){
    let offer_id = getPathVariable('offer-detail');
    let data = await serviceGetOfferById(offer_id);
    let offer = data.offer;
    let offer_details = data.offer_details;
    let accounting = data.accounting;

    $('#customer-name').html(offer.customer.name);
    $('#global-id').html(offer.global_id);
    $('#employee').html(offer.employee.name);
    $('#manager').html(offer.manager.name + ' ' + offer.manager.surname);
    $('#offer-date').html(formatDateAndTimeDESC(offer.created_at, "-"));

    let price = accounting.test_total;
    if (accounting.grand_total != null){
        price = accounting.grand_total;
    }
    $('#offer-price').html(changeCommasToDecimal(price) + ' ₺');
    $('#offer-test-count').html(offer_details.length);

    let status_class = "badge badge-sm bg-info";
    if (offer.status.action == "send-customer"){
        status_class = "badge badge-sm bg-info";
    }else if (offer.status.action == "accept-reject"){
        status_class = "badge badge-sm bg-indigo";
    }else if (offer.status.action == "customer-approved"){
        status_class = "badge badge-sm bg-green";
    }else if (offer.status.action == "detail"){
        status_class = "badge badge-sm bg-teal";
    }else if (offer.status.action == "laboratory"){
        status_class = "badge badge-sm bg-blue";
    }else if (offer.status.action == "cancelled"){
        status_class = "badge badge-sm bg-danger";
    }
    let status = '<span class="'+ status_class +' cursor-pointer" onclick="openStatusModal('+ offer.id +', '+ offer.status_id +')"><i class="fa fa-circle fs-9px fa-fw me-5px"></i> '+ offer.status.name +'</span>';
    $('#update-status-col').append(status);
}

async function initOfferHistory(){
    let offer_id = getPathVariable('offer-detail');
    let data = await serviceGetOfferStatusHistory(offer_id);
    let actions = data.actions;

    $('#status-history-table tbody tr').remove();

    $.each(actions, function (i, action) {
        let last_time = formatDateAndTimeDESC(action.last_status.created_at, "/");

        previous_status_name = action.previous_status.status_name;
        if (action.previous_status == 0){
            previous_status_name = '-';
        }

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-secondary me-2"></i>\n' +
            '                   '+ action.last_status.user_name +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td><small>'+ last_time +'</small></td>\n' +
            '           <td class="text-right">\n' +
            '               <span class="badge bg-secondary" style="min-height: 18px">'+ previous_status_name +'</span>\n' +
            '           </td>\n' +
            '           <td>\n' +
            '               <i class="bi bi-arrow-90deg-right"></i>\n' +
            '               <span class="badge bg-green text-white" style="min-height: 18px">'+ action.last_status.status_name +'</span>\n' +
            '           </td>\n' +
            '       </tr>';

        $('#status-history-table tbody').append(item);
    });
}

async function initDocuments(){
    let offer_id = getPathVariable('offer-detail');
    let data = await serviceGetDocuments(offer_id);
    $("#document-datatable").dataTable().fnDestroy();
    $('#document-datatable tbody > tr').remove();

    $.each(data.documents, function (i, document) {
        let item = '<tr>\n' +
            '              <td>'+ document.type_name +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="'+ api_url + document.file_url +'" target="_blank" id="bDel" type="button" class="btn  btn-sm btn-theme">\n' +
            '                          <span class="bi bi-file-pdf"> </span> \n' +
            '                      </a>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteDocument(\''+ document.id +'\')">\n' +
            '                          <span class="bi bi-trash3"> </span> \n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#document-datatable tbody').append(item);
    });

    $('#document-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Döküman Ekle',
                className: 'btn btn-theme',
                action: function ( e, dt, node, config ) {
                    openAddDocumentModal();
                }
            }
        ],
        pageLength : -1,
        language: {
            url: "services/Turkish.json"
        }
    });
}

async function openAddDocumentModal(){
    $('#addDocumentModal').modal('show');
    getDocumentTypesAddSelectId('add_document_type_id');
}
async function addDocument(){

    let offer_id = getPathVariable('offer-detail');
    let formData = new FormData();
    formData.append('document_type_id', document.getElementById('add_document_type_id').value);
    formData.append('file', document.getElementById('add_document_file').files[0]);
    console.log(formData);

    await servicePostAddDocument(formData, offer_id);
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
async function deleteDocument(document_id){
    let returned = await serviceGetDeleteDocument(document_id);
    if(returned){
        initDocuments();
    }
}


function openStatusModal(offer_id, status_id){
    $('#updateStatusModal').modal('show');
    initStatusModal(offer_id, status_id);
}
async function initStatusModal(offer_id, status_id){
    let data = await serviceGetChangeableStatuses();
    let statuses = data.statuses;
    $('#update_offer_status option').remove();
    $.each(statuses, function (i, status){
        let forced = '';
        if (status.forced == 1){forced = '(*)';}
        let selected = '';
        if(status.id == status_id){selected = 'selected';}
        $('#update_offer_status').append('<option value="'+ status.id +'" '+ selected +'>'+ status.name +' '+ forced +'</option>');
    });
    document.getElementById('update_offer_id').value = offer_id;
}
async function updateStatus(){
    let status_id = document.getElementById('update_offer_status').value;
    let offer_id = document.getElementById('update_offer_id').value;
    let formData = JSON.stringify({
        "offer_id": offer_id,
        "status_id": status_id
    });
    let data = await servicePostUpdateOfferStatus(formData);
    if(data.status == "success"){
        location.reload();
    }
}
