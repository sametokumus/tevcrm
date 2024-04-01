(function($) {
    "use strict";

	$(document).ready(function() {
        $('#add_lab_form').submit(function (e){
            e.preventDefault();
            addLab();
        });
        $('#update_lab_form').submit(function (e){
            e.preventDefault();
            updateLab();
        });
	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initLabs();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initLabs(){
	let data = await serviceGetLabs();
    $("#labs-datatable").dataTable().fnDestroy();
    $('#labs-datatable tbody > tr').remove();

    $.each(data.labs, function (i, lab) {

        let item = '<tr>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(lab.name) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(lab.lab_code) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      <p class="mb-0">'+ checkNull(lab.last_no) +'</p>\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-theme" onclick="openUpdateLabModal(\''+ lab.id +'\')">\n' +
            '                          <span class="bi bi-pencil-square"></span>\n' +
            '                      </button>\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteLab(\''+ lab.id +'\')">\n' +
            '                          <span class="bi bi-trash3"></span>\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '                  </td>\n' +
            '              </tr>';
        $('#labs-datatable tbody').append(item);

    });

    $('#labs-datatable').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Yeni Laboratuvar Ekle',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    openAddLabModal();
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

async function openAddLabModal(){
    $('#addLabModal').modal('show');
}

async function addLab(){
    let name = document.getElementById('add_lab_name').value;
    let lab_code = document.getElementById('add_lab_lab_code').value;
    let last_no = document.getElementById('add_lab_last_no').value;
    let formData = JSON.stringify({
        "name": name,
        "lab_code": lab_code,
        "last_no": last_no
    });
    let returned = await servicePostAddLab(formData);
    if(returned){
        $("#add_lab_form").trigger("reset");
        $('#addLabModal').modal('hide');
        initLabs();
    }
}

async function openUpdateLabModal(lab_id){
    $('#updateLabModal').modal('show');
    await initUpdateLabModal(lab_id);
}

async function initUpdateLabModal(lab_id){
    let data = await serviceGetLabById(lab_id);
    let lab = data.lab;
    document.getElementById('update_lab_id').value = lab.id;
    document.getElementById('update_lab_name').value = lab.name;
    document.getElementById('update_lab_lab_code').value = lab.lab_code;
    document.getElementById('update_lab_last_no').value = lab.last_no;
}

async function updateLab(){
    let id = document.getElementById('update_lab_id').value;
    let name = document.getElementById('update_lab_name').value;
    let lab_code = document.getElementById('update_lab_lab_code').value;
    let last_no = document.getElementById('update_lab_last_no').value;
    let formData = JSON.stringify({
        "name": name,
        "lab_code": lab_code,
        "last_no": last_no
    });
    let returned = await servicePostUpdateLab(id, formData);
    if(returned){
        $("#update_lab_form").trigger("reset");
        $('#updateLabModal').modal('hide');
        initLabs();
    }
}

async function deleteLab(lab_id){
    let returned = await serviceGetDeleteLab(lab_id);
    if(returned){
        initLabs();
    }
}
