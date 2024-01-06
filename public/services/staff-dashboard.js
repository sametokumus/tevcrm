(function($) {
    "use strict";

    $(document).ready(function() {

        $('#update-profile-button').click(function (){
            openUpdateProfileModal();
        });

        $('#update_account_form').submit(function (e){
            e.preventDefault();
            updateProfile();
        });

    });

    $(window).load(async function() {

        checkLogin();

        initStaffs();

    });

})(window.jQuery);

async function initStaffs(){
    let data = await serviceGetAllStaffStatistics();
    let staffs = data.staffs;
    console.log(staffs)
    // $('#staff-name').text(admin.name + ' ' + admin.surname);
    // $('#staff-email').html('<i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i>' + admin.email);
    // $('#staff-phone').html('<i class="fa fa-phone fa-fw text-inverse text-opacity-50"></i>' + admin.phone_number);
    //
    // let profile_photo = '/img/user/null-profile-picture.png';
    // if (admin.profile_photo != null && admin.profile_photo != ''){
    //     profile_photo = admin.profile_photo;
    // }
    // $('#staff-image').attr('src', profile_photo);
}

async function initAdmin(user_id){
    let data = await serviceGetAdminById(user_id);
    let admin = data.admin;
    console.log(admin)
    $('#staff-name').text(admin.name + ' ' + admin.surname);
    $('#staff-email').html('<i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i>' + admin.email);
    $('#staff-phone').html('<i class="fa fa-phone fa-fw text-inverse text-opacity-50"></i>' + admin.phone_number);

    let profile_photo = '/img/user/null-profile-picture.png';
    if (admin.profile_photo != null && admin.profile_photo != ''){
        profile_photo = admin.profile_photo;
    }
    $('#staff-image').attr('src', profile_photo);
}

async function initStaffTargets(user_id){

    let data = await serviceGetStaffTargetsByStaffId(user_id);

    console.log(data)
    $("#targets-datatable").dataTable().fnDestroy();
    $('#targets-datatable tbody > tr').remove();

    $.each(data.targets, function (i, target) {

        let actions = "";
        if (true){
            actions = '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openUpdateStaffTargetModal(\''+ target.id +'\');">Düzenle</button>\n' +
                '      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deleteStaffTarget(\''+ target.id +'\');">Sil</button>\n';
        }

        let item = '<tr>\n' +
            '           <th>'+ target.id +'</th>\n' +
            // '           <td>'+ target.admin.name +' '+ target.admin.surname +'</td>\n' +
            '           <td>'+ target.type_name +'</td>\n' +
            '           <td>'+ changeCommasToDecimal(target.target) +' '+ target.currency +'</td>\n' +
            '           <td>'+ target.month_name +'</td>\n' +
            '           <td>'+ target.year +'</td>\n' +
            // '           <td>'+ target.status.rate +'%</td>\n' +
            '           <td>\n' +
            '               <div class="progress">\n' +
            '                   <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: '+ parseInt(target.status.rate) +'%">'+ target.status.rate +'%</div>\n' +
            '               </div>\n' +
            '           </td>\n' +
            '       </tr>';
        $('#targets-datatable tbody').append(item);
    });

    $('#targets-datatable').DataTable({
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

async function initStaffStats(user_id){

    let data = await serviceGetStaffStatistics(user_id);

    console.log(data)

    $('#stat-1').append(data.total_company_count);
    $('#stat-2').append(data.add_this_month_company);
    $('#stat-3').append(data.activity_this_month);
    $('#stat-4').append(data.request_this_month);
    $('#stat-5').append(data.sale_this_month);

    let data2 = await serviceGetStaffSituation(user_id);

    console.log(data2)
    $('#stat-6').append(data2.position + '. (' + data2.staff.staff_rate + ')');

}
