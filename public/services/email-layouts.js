(function($) {
    "use strict";

	$(document).ready(function() {
        $(":input").inputmask();

        $('#add_layout_form').submit(function (e){
            e.preventDefault();
            addLayout();
        });
        $('#update_layout_form').submit(function (e){
            e.preventDefault();
            updateLayout();
        });

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();

        initEmailLayouts();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initEmailLayouts(){

    let data = await serviceGetEmailLayouts();

    console.log(data)
	$("#layout-datatable").dataTable().fnDestroy();
	$('#layout-datatable tbody > tr').remove();

    $.each(data.layouts, function (i, layout) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateEmailLayoutModal(\''+ layout.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteEmailLayout(\''+ layout.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th>'+ layout.id +'</th>\n' +
            '           <td>'+ layout.name +'</td>\n' +
            '           <td>'+ layout.subject +'</td>\n' +
            '           <td>'+ actions +'</td>\n' +
            '       </tr>';
        $('#layout-datatable tbody').append(item);
    });

	$('#layout-datatable').DataTable({
		responsive: false,
		columnDefs: [],
		dom: 'Bfrtip',
        paging: false,
		buttons: [],
        scrollX: true,
		language: {
			url: "services/Turkish.json"
		},
		order: false,
	});
}

async function addLayout(){

    let name = document.getElementById('add_layout_name').value;
    let subject = document.getElementById('add_layout_subject').value;
    let text = $('#add_layout_text').summernote('code');


    let formData = JSON.stringify({
        "name": name,
        "subject": subject,
        "text": text
    });

    console.log(formData);

    let returned = await servicePostAddEmailLayout(formData);
    if (returned){
        $("#add_layout_form").trigger("reset");
        initEmailLayouts();
    }else{
        alert("Hata Oluştu");
    }
}


async function openUpdateLayoutModal(layout_id){
    $("#updateLayoutModal").modal('show');
    initUpdateLayoutModal(layout_id)
}
async function initUpdateLayoutModal(layout_id){
    document.getElementById('update_layout_form').reset();
    let data = await serviceGetNotifySettingById(layout_id);
    let layout = data.layout;
    document.getElementById('update_layout_id').value = layout.id;
    document.getElementById('update_layout_name').value = layout.name;
    document.getElementById('update_layout_subject').value = layout.subject;
    document.getElementById('update_layout_text').value = layout.text;
}
async function updateLayout(){
    let id = document.getElementById('update_layout_id').value;
    let name = document.getElementById('update_layout_name').value;
    let subject = document.getElementById('update_layout_subject').value;
    let text = $('#update_layout_text').summernote('code');


    let formData = JSON.stringify({
        "id": id,
        "name": name,
        "subject": subject,
        "text": text
    });
    let returned = await servicePostUpdateEmailLayout(formData);
    if (returned){
        $("#update_layout_form").trigger("reset");
        $("#updateLayoutModal").modal('hide');
        initEmailLayouts();
    }else{
        showAlert('Güncelleme yapılırken bir hata oluştu. Lütfen tekrar deneyiniz!');
    }
}

async function deleteEmailLayout(layout_id){
    let returned = await serviceGetDeleteEmailLayout(layout_id);
    if(returned){
        initEmailLayouts();
    }
}
