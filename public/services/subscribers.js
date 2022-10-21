(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $("#update_subscriber_form").submit(async function( event ) {
			 event.preventDefault();

			 let subscriber_id = document.getElementById('update_subscriber_id').value;
			 let email = document.getElementById('update_subscriber_email').value;
			 let referrer = document.getElementById('update_subscriber_referrer').value;
			 let formData = JSON.stringify({
				 "email":email,
				 "referrer":referrer
			 });

			 let returned = await servicePostUpdateSubscriber(formData, subscriber_id);
			 if(returned){
				 $("#update_subscriber_form").trigger("reset");
				 $('#updateSubscriberModal').modal('hide');
				 initSubscribersTable();
			 }

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initSubscribersTable();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function openSubscriberModal(subscriber_id){
	$('#updateSubscriberModal').modal('show');
	initSubscriberModal(subscriber_id);
}

async function initSubscribersTable(){
	$("#subscriber-datatable").dataTable().fnDestroy();
	$('#subscriber-datatable tbody > tr').remove();

	let data = await serviceGetSubscribers();
	console.log(data)
	$.each(data.subscribers, function (i, subscriber) {
		let referrer = '';
		if (subscriber.referrer == 1){
			referrer = 'Page Block';
		}else if(subscriber.referrer == 2){
			referrer = 'Popup';
		}
		let subscriberItem = '<tr>\n' +
			'              <td>'+ subscriber.id +'</td>\n' +
			'              <td>'+ subscriber.email +'</td>\n' +
			'              <td>'+ referrer +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openSubscriberModal(\''+ subscriber.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n' +
			'                      <button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteSubscriber(\''+ subscriber.id +'\')">\n' +
			'                          <span class="fe fe-trash-2"> </span> Sil\n' +
			'                      </button>\n' +
			'                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#subscriber-datatable tbody').append(subscriberItem);
	});

	$('#subscriber-datatable').DataTable({
		responsive: true,
		columnDefs: [
			{ responsivePriority: 1, targets: 0 },
			{ responsivePriority: 2, targets: -1 }
		],
		dom: 'Bfrtip',
		buttons: ['excel', 'pdf'],
		pageLength : 20,
		language: {
			url: "services/Turkish.json"
		}
	});



}

async function initSubscriberModal(subscriber_id){
	let data = await serviceGetSubscriberById(subscriber_id);
	let subscriber = data.subscriber;
	document.getElementById('update_subscriber_id').value = subscriber.id;
	document.getElementById('update_subscriber_email').value = subscriber.email;
	document.getElementById('update_subscriber_referrer').value = subscriber.referrer;

}

async function deleteSubscriber(subscriber_id){
	let returned = await serviceGetDeleteSubscriber(subscriber_id);
	if(returned){
		initSubscribersTable();
	}
}
