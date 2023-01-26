(function($) {
    "use strict";

	$(document).ready(function() {
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
		await initOfferDetail();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initOfferDetail(){
    let request_id = getPathVariable('sw-2');
    console.log(request_id)
    let data = await serviceGetOffersByRequestId(request_id);
    let offers = data.offers;
    console.log(offers)
    $("#offer-detail").dataTable().fnDestroy();
    $('#offer-detail tbody > tr').remove();

    $.each(offers, function (i, offer) {
        $.each(offer.products, function (i, product) {
            let item = '<tr id="productRow' + product.id + '">\n' +
                '           <td>' + product.id + '</td>\n' +
                '           <td>' + offer.company_name + '</td>\n' +
                '           <td>' + checkNull(product.product_detail.ref_code) + '</td>\n' +
                '           <td>' + checkNull(product.date_code) + '</td>\n' +
                '           <td>' + checkNull(product.package_type) + '</td>\n' +
                '           <td>' + checkNull(product.request_quantity) + '</td>\n' +
                '           <td>' + checkNull(product.quantity) + '</td>\n' +
                '           <td>' + checkNull(product.pcs_price) + '</td>\n' +
                '           <td>' + checkNull(product.total_price) + '</td>\n' +
                '           <td>' + checkNull(product.discount_rate) + '</td>\n' +
                '           <td>' + checkNull(product.discounted_price) + '</td>\n' +
                '              <td>\n' +
                '                  <div class="btn-list">\n' +
                '                      <button onclick="addSaleTableProduct(\''+ offer.offer_id +'\', '+ product.id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklife Ekle</span></button>\n' +
                '                  </div>\n' +
                '              </td>\n' +
                '       </tr>';
            $('#offer-detail tbody').append(item);
        });
    });

    $('#offer-detail').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Teklife Yeni Ürün Ekle',
                action: function ( e, dt, node, config ) {
                    openAddOfferProductModal();
                }
            }
            // 'excel',
            // 'pdf'
        ],
        pageLength : 20,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'asc']]
    });
}

async function addSaleTableProduct(offer_id, product_id){
    let data = await serviceGetOfferProductById(offer_id, product_id);
    let product = data.product;
    console.log(data)

    $("#sales-detail").dataTable().fnDestroy();

    let item = '<tr id="productRow' + product.id + '">\n' +
        '           <td>' + product.id + '</td>\n' +
        '           <td>' + offer.company_name + '</td>\n' +
        '           <td>' + checkNull(product.product_detail.ref_code) + '</td>\n' +
        '           <td>' + checkNull(product.date_code) + '</td>\n' +
        '           <td>' + checkNull(product.package_type) + '</td>\n' +
        '           <td>' + checkNull(product.request_quantity) + '</td>\n' +
        '           <td>' + checkNull(product.quantity) + '</td>\n' +
        '           <td>' + checkNull(product.pcs_price) + '</td>\n' +
        '           <td>' + checkNull(product.total_price) + '</td>\n' +
        '           <td>' + checkNull(product.discount_rate) + '</td>\n' +
        '           <td>' + checkNull(product.discounted_price) + '</td>\n' +
        '              <td>\n' +
        '                  <div class="btn-list">\n' +
        '                      <button onclick="addSaleTableProduct(\''+ offer_id +'\', '+ product.id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklife Ekle</span></button>\n' +
        '                  </div>\n' +
        '              </td>\n' +
        '       </tr>';
    $('#sales-detail tbody').append(item);

    $('#sales-detail').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Teklife Yeni Ürün Ekle',
                action: function ( e, dt, node, config ) {
                    openAddOfferProductModal();
                }
            }
            // 'excel',
            // 'pdf'
        ],
        pageLength : 20,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'asc']]
    });
}
