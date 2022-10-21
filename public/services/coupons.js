(function($) {
    "use strict";
	
	 $(document).ready(function() {

		 $("#add_coupon_form").submit(async function( event ) {
			 event.preventDefault();

			 let code = document.getElementById('coupon_code').value;
			 let count_of_uses = document.getElementById('coupon_count_of_uses').value;
			 let count_of_used = document.getElementById('coupon_count_of_used').value;
			 let start_date = document.getElementById('coupon_start_date').value;
			 start_date = formatDateDESC(start_date, '-', '/') + " 00:00:00";
			 let end_date = document.getElementById('coupon_end_date').value;
			 end_date = formatDateDESC(end_date, '-', '/') + " 23:59:59";
			 let user_id = document.getElementById('coupon_user_id').value;
			 let type = document.getElementById('coupon_type').value;
			 let coupon_user_type = document.getElementById('coupon_user_type').value;
			 let discount = document.getElementById('coupon_user_type').value;
			 let coupon_user_group = document.getElementById('coupon_user_group').value;
			 let formData = JSON.stringify({
				 "code":code,
				 "count_of_uses":count_of_uses,
				 "count_of_used":count_of_used,
				 "start_date":start_date,
				 "end_date":end_date,
				 "coupon_user_type":coupon_user_type,
				 "group_id":coupon_user_group,
				 "user_id":user_id,
				 "discount_type":type,
				 "discount":discount
			 });
			 console.log(formData)
			 let returned = await servicePostAddCoupon(formData);
			 if(returned){
				 $("#add_coupon_form").trigger("reset");
				 initCouponsTable();
			 }

		 });

		 $("#update_coupon_form").submit(async function( event ) {
			 event.preventDefault();

			 let coupon_id = document.getElementById('update_coupon_id').value;
			 let code = document.getElementById('update_coupon_code').value;
			 let count_of_uses = document.getElementById('update_coupon_count_of_uses').value;
			 let count_of_used = document.getElementById('update_coupon_count_of_used').value;
			 let start_date = document.getElementById('update_coupon_start_date').value;
			 start_date = formatDateDESC(start_date, '-', '/') + " 00:00:00";
			 let end_date = document.getElementById('update_coupon_end_date').value;
			 end_date = formatDateDESC(end_date, '-', '/') + " 23:59:59";
			 let user_id = document.getElementById('update_coupon_user_id').value;
			 let type = document.getElementById('update_coupon_type').value;
			 let discount = document.getElementById('update_coupon_discount').value;
			 let coupon_user_group = document.getElementById('update_coupon_user_group').value;
			 let coupon_user_type = document.getElementById('update_coupon_user_type').value;

			 let formData = JSON.stringify({
				 "code":code,
				 "count_of_uses":count_of_uses,
				 "count_of_used":count_of_used,
				 "start_date":start_date,
				 "end_date":end_date,
				 "user_id":user_id,
				 "discount_type":type,
				 "discount":discount,
				 "coupon_user_type":coupon_user_type,
				 "group_id":coupon_user_group,
			 });

			 let returned = await servicePostUpdateCoupon(coupon_id, formData);
			 if(returned){
				 $("#update_coupon_form").trigger("reset");
				 $('#updateCouponModal').modal('hide');
				 initCouponsTable();
			 }

		 });

	});

	$(window).load( function() {

		checkLogin();
		checkRole();
		initCouponsTable();
		initUserTypeList();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initUserTypeList(){
	let data = await serviceGetUserTypes();
	$.each(data.user_types, function (i, user_type) {
		var typeItem = '<option value="'+ user_type.id +'">'+ user_type.name +'</option>';

		$('#coupon_user_group').append(typeItem);
		$('#update_coupon_user_group').append(typeItem);
	});
}

function openCouponModal(coupon_id){
	$('#updateCouponModal').modal('show');
	initCouponModal(coupon_id);
}

async function initCouponsTable(){
	$("#coupon-datatable").dataTable().fnDestroy();
	$('#coupon-datatable tbody > tr').remove();

	let data = await serviceGetCoupons();
	$.each(data.coupons, async function (i, coupon) {
		let discount_type_name = "";
		if (coupon.discount_type == 1){
			discount_type_name = "Tam İndirim";
		}else{
			discount_type_name = "Yüzdelik İndirim";
		}
		let coupon_user_type = "";
		if (coupon.coupon_user_type == 1){
			coupon_user_type = "Kullanıcı";
		}else if (coupon.coupon_user_type == 2){
			coupon_user_type = "Kullanıcı Grubu";
		}
		let user_info = "0";
		if (coupon.user_id != "0"){
			let userData = await serviceGetUserProfile(coupon.user_id);
			let user = userData.user;
			let user_profile = userData.user_profile;
			user_info = user_profile.name + " " + user_profile.surname + "(" + user.email + ")";
		}
		let group_info = "Grup Seçilmedi";
		if (coupon.group_id != "0"){
			let groupData = await serviceGetUserTypeById(coupon.group_id);
			let group = groupData.user_type;
			group_info = group.name;
		}
		let couponItem = '<tr>\n' +
			'              <td>'+ coupon.code +'</td>\n' +
			'              <td>'+ discount_type_name +'</td>\n' +
			'              <td>'+ coupon.discount +'</td>\n' +
			'              <td>'+ coupon.start_date +'</td>\n' +
			'              <td>'+ coupon.end_date +'</td>\n' +
			'              <td>'+ coupon.count_of_uses +'</td>\n' +
			'              <td>'+ coupon.count_of_used +'</td>\n' +
			'              <td>'+ coupon_user_type +'</td>\n' +
			'              <td>'+ user_info +'</td>\n' +
			'              <td>'+ group_info +'</td>\n' +
			'              <td>\n' +
			'                  <div class="btn-list">\n' +
			'                      <button id="bEdit" type="button" class="btn btn-sm btn-primary" onclick="openCouponModal(\''+ coupon.id +'\')">\n' +
			'                          <span class="fe fe-edit"> </span> Düzenle\n' +
			'                      </button>\n';
		if (coupon.count_of_used == 0) {
			couponItem = couponItem + '<button id="bDel" type="button" class="btn  btn-sm btn-danger" onclick="deleteCoupon(\'' + coupon.id + '\')">\n' +
				'                          <span class="fe fe-trash-2"> </span> Sil\n' +
				'                      </button>\n';
		}
		couponItem = couponItem + '                  </div>\n' +
			'              </td>\n' +
			'          </tr>';
		$('#coupon-datatable tbody').append(couponItem);
	});

	$('#coupon-datatable').DataTable({
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

async function initCouponModal(coupon_id){
	let data = await serviceGetCouponById(coupon_id);
	let coupon = data.coupon;
	document.getElementById('update_coupon_id').value = coupon.id;
	document.getElementById('update_coupon_code').value = coupon.code;
	document.getElementById('update_coupon_count_of_uses').value = coupon.count_of_uses;
	document.getElementById('update_coupon_count_of_used').value = coupon.count_of_used;
	document.getElementById('update_coupon_start_date').value = formatDateASC(coupon.start_date, '/');
	document.getElementById('update_coupon_end_date').value = formatDateASC(coupon.end_date, '/');
	document.getElementById('update_coupon_user_id').value = coupon.user_id;
	document.getElementById('update_coupon_type').value = coupon.discount_type;
	document.getElementById('update_coupon_user_type').value = coupon.coupon_user_type;
	document.getElementById('update_coupon_user_group').value = coupon.group_id;
	document.getElementById('update_coupon_discount').value = coupon.discount;

}

async function deleteCoupon(coupon_id){
	let returned = await serviceGetDeleteCoupon(coupon_id);
	if(returned){
		initCouponsTable();
	}
}
