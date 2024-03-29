(function($) {
    "use strict";

	$(document).ready(function() {
        $('#update_status_form').submit(function (e){
            e.preventDefault();
            updateStatus();
        });

        $('#delete_offer_form').submit(function (e){
            e.preventDefault();
            deleteOffer();
        });

	});

    $(window).on('load', function () {

		checkLogin();
		checkRole();
        initOfferDetail();
        initOfferHistory();

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
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ action.last_status.user_name +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td><small>'+ last_time +'</small></td>\n' +
            '           <td class="text-right">\n' +
            '               <span class="badge bg-theme text-theme-900 bg-opacity-50 rounded-0 pt-5px" style="min-height: 18px">'+ previous_status_name +'</span>\n' +
            '           </td>\n' +
            '           <td>\n' +
            '               <i class="bi bi-arrow-90deg-right"></i>\n' +
            '               <span class="badge bg-theme text-white rounded-0 pt-5px" style="min-height: 18px">'+ action.last_status.status_name +'</span>\n' +
            '           </td>\n' +
            '       </tr>';

        $('#status-history-table tbody').append(item);
    });
}


async function initOffers(){
	let data = await serviceGetOffers();
    $("#offer-datatable").dataTable().fnDestroy();
    $('#offer-datatable tbody > tr').remove();

    $.each(data.offers, function (i, offer) {

        let status_class = "badge badge-sm bg-info";
        let btn_list = '<div class="btn-list">\n';

        if (localStorage.getItem('userRole') == 1) {
            btn_list += '<button id="bDel" type="button" class="btn btn-sm btn-outline-danger" onclick="openDeleteOffer(\'' + offer.id + '\')">\n' +
                '           <span class="fe fe-refresh-cw"> Sil\n' +
                '        </button>\n';
        }

        if (offer.status.action == "send-customer"){
            status_class = "badge badge-sm bg-info";
            btn_list += '<a href="offer-detail/'+ offer.id +'" class="btn btn-sm btn-secondary">Teklif Detayı</a>\n';
        }else if (offer.status.action == "accept-reject"){
            status_class = "badge badge-sm bg-indigo";
            btn_list += '<a href="offer-detail/'+ offer.id +'" class="btn btn-sm btn-secondary">Teklif Detayı</a>\n';
            btn_list += '<button id="bDel" type="button" class="btn btn-sm btn-theme" onclick="acceptOffer(\'' + offer.id + '\')">\n' +
                '           <span class="fe fe-refresh-cw"> Teklif Onaylandı\n' +
                '        </button>\n';
            btn_list += '<button id="bDel" type="button" class="btn btn-sm btn-danger" onclick="rejectOffer(\'' + offer.id + '\')">\n' +
                '           <span class="fe fe-refresh-cw"> Teklif Reddedildi\n' +
                '        </button>\n';
        }else if (offer.status.action == "customer-approved"){
            status_class = "badge badge-sm bg-green";
            btn_list += '<a href="offer-detail/'+ offer.id +'" class="btn btn-sm btn-secondary">Teklif Detayı</a>\n';
            btn_list += '<a href="send-test-laboratory/'+ offer.id +'" class="btn btn-sm btn-info">Testleri Laboratuvara Gönder</a>\n';
        }else if (offer.status.action == "detail"){
            status_class = "badge badge-sm bg-teal";
            btn_list += '<a href="offer-detail/'+ offer.id +'" class="btn btn-sm btn-secondary">Teklif Detayı</a>\n';
        }else if (offer.status.action == "laboratory"){
            status_class = "badge badge-sm bg-blue";
            btn_list += '<a href="offer-detail/'+ offer.id +'" class="btn btn-sm btn-secondary">Teklif Detayı</a>\n';
        }else if (offer.status.action == "cancelled"){
            status_class = "badge badge-sm bg-danger";
            btn_list += '<a href="offer-detail/'+ offer.id +'" class="btn btn-sm btn-secondary">Teklif Detayı</a>\n';
        }

        btn_list += '</div>';

        let status = '<span class="'+ status_class +'" onclick="openStatusModal('+ offer.id +', '+ offer.status_id +')"><i class="fa fa-circle fs-9px fa-fw me-5px"></i> '+ offer.status.name +'</span>';



        let employee = '';
        if (offer.employee != null){
            employee = offer.employee.name;
        }

        let manager = '';
        if (offer.manager != null){
            manager = offer.manager.name + ' ' + offer.manager.surname;
        }

        let price = offer.accounting.test_total;
        if (offer.accounting.sub_total != null){
            price = offer.accounting.sub_total;
        }
        if(offer.accounting.grand_total != null){
            price = offer.accounting.grand_total;
        }
        console.log(price)
        let item = '<tr>\n' +
            '                  <td class="bg-light-green bg-opacity-50">\n' +
            '                      '+ offer.global_id +'\n' +
            '                  </td>\n' +
            '                  <td class="bg-light-green bg-opacity-50">\n' +
            '                      '+ checkNull(offer.customer.name) +'\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      '+ checkNull(employee) +'\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      '+ checkNull(manager) +'\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      '+ changeCommasToDecimal(price) +' ₺\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      '+ formatDateAndTimeDESC2(offer.created_at, "-") +'\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                      '+ status +'\n' +
            '                  </td>\n' +
            '                  <td>\n' +
            '                  ' + btn_list + '\n' +
            '                  </td>\n' +
            '              </tr>';
        $('#offer-datatable tbody').append(item);
    });

    $('#offer-datatable').DataTable({
        responsive: false,
        columnDefs: [
            {
                targets: 1,
                className: 'ellipsis',
                render: function(data, type, row, meta) {
                    return type === 'display' && data.length > 30 ?
                        data.substr(0, 50) + '...' :
                        data;
                }
            }
        ],
        dom: 'Bfrtip',
        paging: false,
        buttons: [
            ],
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: [
            [0, 'desc']
        ],
        fixedColumns: {
            left: 2
        }
    });
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
        $("#update_status_form").trigger("reset");
        $('#updateStatusModal').modal('hide');
        initOffers();
    }
}

async function openDeleteOffer(offer_id){
    $('#deleteOfferModal').modal('show');
    document.getElementById('delete_offer_id').value = offer_id;
}

async function deleteOffer(){
    let offer_id = document.getElementById('delete_offer_id').value;
    let returned = await serviceGetDeleteOffer(offer_id);
    if(returned){
        $('#deleteOfferModal').modal('hide');
        await initOffers();
    }
}

async function acceptOffer(offer_id) {
    let formData = JSON.stringify({
        "offer_id": offer_id,
        "status_id": 3
    });
    let data = await servicePostUpdateOfferStatus(formData);
    if(data.status == "success"){
        initOffers();
    }
}

async function rejectOffer(offer_id) {
    let formData = JSON.stringify({
        "offer_id": offer_id,
        "status_id": 4
    });
    let data = await servicePostUpdateOfferStatus(formData);
    if(data.status == "success"){
        initOffers();
    }
}
