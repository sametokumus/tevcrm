(function($) {
    "use strict";

	 $(document).ready(function() {


	});

	$(window).load(async function() {

		checkLogin();
        checkRole();
        await initSaleHistory();

	});

})(window.jQuery);

function checkRole(){
    return true;
}

async function initSaleHistory(){
    let data = await serviceGetSaleHistoryActions();
    let actions = data.actions;

    $('#sales-history-table tbody tr').remove();

    $.each(actions, function (i, action) {
        let last_time = formatDateAndTimeDESC(action.sale.created_at, "/");
        if (action.sale.updated_at != null){
            last_time = formatDateAndTimeDESC(action.sale.updated_at, "/");
        }

        let item = '<tr>\n' +
            '           <td>\n' +
            '               <span class="d-flex align-items-center">\n' +
            '                   <i class="bi bi-circle-fill fs-6px text-theme me-2"></i>\n' +
            '                   '+ action.sale.customer_name +'\n' +
            '               </span>\n' +
            '           </td>\n' +
            '           <td><small>'+ last_time +'</small></td>\n' +
            '           <td>\n' +
            '               <span class="badge bg-white bg-opacity-25 rounded-0 pt-5px" style="min-height: 18px">'+ action.previous_status.status_name +'</span>\n' +
            '               <i class="bi bi-arrow-90deg-right"></i>\n' +
            '               <span class="badge bg-theme text-theme-900 rounded-0 pt-5px" style="min-height: 18px">'+ action.last_status.status_name +'</span>\n' +
            '           </td>\n' +
            '           <td>\n' +
            '               <a href="/sale-detail/'+ action.sale_id +'" class="text-decoration-none text-white"><i class="bi bi-search"></i></a>\n' +
            '           </td>\n' +
            '       </tr>';

        $('#sales-history-table tbody').append(item);
    });
}
