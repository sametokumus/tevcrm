(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#add_currency_log_usd").maskMoney({thousands:'.', decimal:','});
        $("#add_currency_log_eur").maskMoney({thousands:'.', decimal:','});
        $("#add_currency_log_gbp").maskMoney({thousands:'.', decimal:','});

        $('#add_currency_log_form').submit(function (e){
            e.preventDefault();
            addCurrencyLog();
        });
	});

	$(window).on('load', function () {

		checkLogin();
		checkRole();
        initCurrencyLogs();

	});

})(window.jQuery);

function checkRole(){
	return true;
}
async function initCurrencyLogs(){
	let data = await serviceGetCurrencyLogs();
    console.log(data)
    $("#currency-datatable").dataTable().fnDestroy();
    $('#currency-datatable tbody > tr').remove();

    $.each(data.currency_logs, function (i, currency_log) {
        let typeItem = '<tr>\n' +
            '              <td>'+ currency_log.id +'</td>\n' +
            '              <td>'+ formatDateASC(currency_log.day, '.') +'</td>\n' +
            '              <td>'+ changeCommasToDecimal(currency_log.usd) +'</td>\n' +
            '              <td>'+ changeCommasToDecimal(currency_log.eur) +'</td>\n' +
            '              <td>'+ changeCommasToDecimal(currency_log.gbp) +'</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button id="bEdit" type="button" class="btn btn-sm btn-danger" onclick="deleteCurrencyLog(\''+ currency_log.id +'\')">\n' +
            '                          <span class="fe fe-edit"> </span> Sil\n' +
            '                      </button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '          </tr>';
        $('#currency-datatable tbody').append(typeItem);
    });

    $('#currency-datatable').DataTable({
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
        orderable: true,
        order: [[0, 'desc']],
    });

}

async function addCurrencyLog() {
    let usd = document.getElementById('add_currency_log_usd').value;
    let eur = document.getElementById('add_currency_log_eur').value;
    let gbp = document.getElementById('add_currency_log_gbp').value;

    let formData = JSON.stringify({
        "usd": changePriceToDecimal(usd),
        "eur": changePriceToDecimal(eur),
        "gbp": changePriceToDecimal(gbp)
    });


    let returned = await servicePostAddCurrencyLog(formData);
    if (returned){
        initCurrencyLogs();
    }else{
        alert("Kur Eklerken Hata Oluştu")
    }
}


async function deleteCurrencyLog(log_id){
    let returned = await serviceGetDeleteCurrencyLog(log_id);
    if(returned){
        initCurrencyLogs();
    }
}
async function getLiveCurrencyLog(){
    let returned = await serviceGetLiveCurrencyLog();
    if(returned){
        initCurrencyLogs();
    }
}
