(function($) {
    "use strict";

	$(document).ready(function() {
        $(":input").inputmask();
        $("#update_currency_rate_usd").maskMoney({thousands:'.', decimal:','});
        $("#update_currency_rate_eur").maskMoney({thousands:'.', decimal:','});
        $("#update_currency_rate_gbp").maskMoney({thousands:'.', decimal:','});

        $('#update_currency_rate_form').submit(function (e){
            e.preventDefault();
            updateCurrencyRate();
        });
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
        await initOfferDetail();
        await initPackingLists();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initOfferDetail(){

    let sale_id = getPathVariable('packing-list');
    let data = await serviceGetPackingableProductsBySaleId(sale_id);
    console.log(data)

        let offers = data.sale.sale_offers;
        console.log(offers)
        // $("#packing-list-detail").dataTable().fnDestroy();
        $("#packingable-list-detail").dataTable().fnDestroy();
        $('#packingable-list-detail tbody > tr').remove();

        $.each(offers, function (i, offer) {
                let item = '<tr id="productRow' + offer.id + '">\n' +
                    '           <td>' + offer.sequence + '</td>\n' +
                    '           <td>' + offer.id + '</td>\n' +
                    '              <td>\n' +
                    '                  <div class="btn-list">\n' +
                    '                      <button type="button" onclick="addPackingListTableProduct(this);" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Listeye Ekle</span></button>\n' +
                    '                  </div>\n' +
                    '              </td>\n' +
                    '           <td>' + checkNull(offer.product_name) + '</td>\n' +
                    '           <td>' + checkNull(offer.product_ref_code) + '</td>\n' +
                    '           <td>' + checkNull(offer.offer_lead_time) + '</td>\n' +
                    '           <td>' + checkNull(offer.offer_quantity) + '</td>\n' +
                    '       </tr>';
                $('#packingable-list-detail tbody').append(item);
        });

        $('#packingable-list-detail').DataTable({
            responsive: false,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1}
            ],
            dom: 'Bfrtip',
            buttons: [],
            paging: false,
            scrollX: true,
            language: {
                url: "services/Turkish.json"
            },
            order: [[0, 'asc']]
        });
        $('#packing-list-detail').DataTable({
            responsive: false,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1}
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Gönderi Listesini Kaydet',
                    action: function (e, dt, node, config) {
                        addPackingList();
                    }
                }
            ],
            paging: false,
            scrollX: true,
            language: {
                url: "services/Turkish.json"
            },
            order: [[0, 'asc']]
        });

}



async function addPackingListTableProduct(el){
    let tablePackingList = $("#packing-list-detail").DataTable();
    let tablePackingableList = $("#packingable-list-detail").DataTable();
    $(el).attr('onclick','removePackingListTableProduct(this);');
    $(el).text('Listeden Çıkar');
    let row = tablePackingableList.row( $(el).parents('tr') );
    let rowNode = row.node();
    row.remove();

    tablePackingList
        .row.add( rowNode )
        .draw();
}

async function removePackingListTableProduct(el){
    let tablePackingList = $("#packing-list-detail").DataTable();
    let tablePackingableList = $("#packingable-list-detail").DataTable();
    $(el).attr('onclick','addPackingListTableProduct(this);');
    $(el).text('Listeye Ekle');
    let row = tablePackingList.row( $(el).parents('tr') );
    let rowNode = row.node();
    row.remove();

    tablePackingableList
        .row.add( rowNode )
        .draw();
}

async function addPackingList(){
    let user_id = localStorage.getItem('userId');
    let sale_id = getPathVariable('packing-list');
    let table = $('#packing-list-detail').DataTable();
    let rows = table.rows();

    let packing_list = [];
    if (rows.count() === 0){
        alert("Öncelikle seçim yapmalısınız.");
    }else {
        rows.every(function (rowIdx, tableLoop, rowLoop) {
            let item = {
                "sale_offer_id": this.data()[1],
            }
            packing_list.push(item);
        });
        console.log(packing_list)

        let formData = JSON.stringify({
            "user_id": parseInt(user_id),
            "sale_id": sale_id,
            "packing_list": packing_list
        });

        console.log(formData);

        // let data = await servicePostAddSale(formData);
        let data = await servicePostAddPackingList(formData);
        console.log(data)
        if (data) {
            window.location.reload();
        }else{
            alert("Hata Oluştu");
        }

    }
}

async function initPackingLists(){

    let sale_id = getPathVariable('packing-list');
    let data = await serviceGetPackingListsBySaleId(sale_id);
    console.log(data)

    let packing_lists = data.packing_lists;
    console.log(packing_lists)
    $("#packing-lists").dataTable().fnDestroy();
    $('#packing-lists tbody > tr').remove();

    $.each(packing_lists, function (i, packing_list) {
        let item = '<tr>\n' +
            '           <td>' + packing_list.packing_list_id + '</td>\n' +
            '           <td>' + packing_list.count + '</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <a href="packing-list-print/'+ packing_list.packing_list_id +'" class="btn btn-sm btn-indigo">Packing List PDF</a>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '       </tr>';
        $('#packing-lists tbody').append(item);
    });

    $('#packing-lists').DataTable({
        responsive: false,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1}
        ],
        dom: 'Bfrtip',
        buttons: [],
        paging: false,
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        }
    });

}

