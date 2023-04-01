(function($) {
    "use strict";

	$(document).ready(function() {

		$('#update_status_form').submit(function (e){
			e.preventDefault();
		});
	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initUsers();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initUsers(){
	let type = getURLParam('type');
	let data;
	if (type == "all"){
		data = await serviceGetUsers();
	}else{
		data = await serviceGetUsersByTypeId(type);
	}
	$("#user-datatable").dataTable().fnDestroy();
	$('#user-datatable tbody > tr').remove();

	console.log(data)
	$.each(data.users, function (i, user) {
		let verify = '';
		if (user.verified == 1){
			verify = '<span class="badge badge-success-light">E-posta adresi doğrulandı.</span>';
		}else{
			verify = '<span class="badge badge-danger-light">E-posta adresi doğrulanmadı.</span>';
		}
		let userItem = '<tr>\n' +
			'              <td>'+ user.id +'</td>\n' +
			'              <td>'+ user.name +' '+ user.surname +'</td>\n' +
			'              <td>'+ user.email +'</td>\n' +
			'              <td>'+ user.phone_number +'</td>\n' +
			'              <td>'+ verify +'</td>\n' +
			'              <td>'+ formatDateAndTimeDESC(user.email_verified_at, '.') +'</td>\n' +
			'              <td>'+ user.user_discount +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <a href="update-user.php?id='+ user.id +'" class="btn btn-sm btn-primary"><span class="fe fe-edit"> Profili Görüntüle</span></a>\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-warning" onclick="openActionsModal(\''+ user.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Kullanıcı Hareketleri\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#user-datatable tbody').append(userItem);
	});
	$('#user-datatable').DataTable({
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
		},
		order: [[0, 'desc']],
	});
}

function openActionsModal(user_id){
	let frame = '<iframe src="https://dinamo.klokmork.site/kablocu-view.php?user_id='+ user_id +'" title="Wimco Dinamo" style="width: 100%; height: 500px;"></iframe>\n';
	document.getElementById('userActionModalContent').innerHTML = frame;
	$('#showActionModal').modal('show');
}
