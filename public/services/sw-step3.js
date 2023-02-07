(function($) {
    "use strict";

    $(":input").inputmask();
    $("#add_offer_price_price").maskMoney({thousands:''});
    $("#update_offer_price_price").maskMoney({thousands:''});

	$(document).ready(function() {

        $('#add_offer_price_form').submit(function (e){
            e.preventDefault();
            addSaleOfferPrice();
        });

        $('#update_offer_price_form').submit(function (e){
            e.preventDefault();
            updateSaleOfferPrice();
        });

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
    let sale_id = getPathVariable('sw-3');
    console.log(sale_id)
    let data = await serviceGetSaleById(sale_id);
    let sale = data.sale;

    if (sale.status_id >= 4) {
        let offers = sale.sale_offers;
        console.log(offers)
        $("#sales-detail").dataTable().fnDestroy();
        $('#sales-detail tbody > tr').remove();

        $.each(offers, function (i, offer) {

            let btn_list = "";
            if (offer.offer_price == null){
                btn_list = '<button type="button" onclick="openAddOfferPriceModal(\''+ offer.offer_id +'\', '+ offer.offer_product_id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklif Fiyatı Ekle</span></button>';
            }else{
                btn_list = '<button type="button" onclick="openUpdateOfferPriceModal(\''+ offer.offer_id +'\', '+ offer.offer_product_id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklif Fiyatı Güncelle</span></button>';
            }

            let item = '<tr id="productRow' + offer.offer_product_id + '">\n' +
                '           <td>' + offer.offer_product_id + '</td>\n' +
                '           <td class="d-none">' + offer.offer_id + '</td>\n' +
                '           <td class="d-none">' + offer.product_id + '</td>\n' +
                '           <td class="d-none">' + offer.supplier_id + '</td>\n' +
                '           <td>' + offer.supplier_name + '</td>\n' +
                '           <td>' + checkNull(offer.product_ref_code) + '</td>\n' +
                '           <td>' + checkNull(offer.date_code) + '</td>\n' +
                '           <td>' + checkNull(offer.package_type) + '</td>\n' +
                '           <td>' + checkNull(offer.request_quantity) + '</td>\n' +
                '           <td>' + checkNull(offer.offer_quantity) + '</td>\n' +
                '           <td>' + checkNull(offer.pcs_price) + '</td>\n' +
                '           <td>' + checkNull(offer.total_price) + '</td>\n' +
                '           <td>' + checkNull(offer.discount_rate) + '</td>\n' +
                '           <td>' + checkNull(offer.discounted_price) + '</td>\n' +
                '           <td>' + checkNull(offer.vat_rate) + '</td>\n' +
                '           <td>' + checkNull(offer.offer_price) + '</td>\n' +
                '           <td>\n' +
                '               <div class="btn-list">\n' +
                '                   '+ btn_list +'\n' +
                '               </div>\n' +
                '           </td>\n' +
                '       </tr>';
            $('#sales-detail tbody').append(item);
        });
        $('#sales-detail').DataTable({
            responsive: false,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1}
            ],
            dom: 'Bfrtip',
            buttons: [

            ],
            pageLength: 20,
            language: {
                url: "services/Turkish.json"
            },
            order: [[0, 'asc']]
        });
    }else{
        alert('Bu sipariş teklif oluşturmaya hazır değildir.');
    }
}

async function openAddOfferPriceModal(offer_id, offer_product_id){
    $("#addOfferPriceModal").modal('show');
    document.getElementById('add_offer_price_offer_id').value = offer_id;
    document.getElementById('add_offer_price_offer_product_id').value = offer_product_id;
}

async function openUpdateOfferPriceModal(offer_id, offer_product_id){
    $("#updateOfferPriceModal").modal('show');
    await initUpdateOfferPriceModal(offer_id, offer_product_id);
}

async function initUpdateOfferPriceModal(offer_id, offer_product_id){
    document.getElementById('update_offer_price_offer_id').value = offer_id;
    document.getElementById('update_offer_price_offer_product_id').value = offer_product_id;
    let data = await serviceGetSaleOfferById(offer_product_id);
    console.log(data)
    let sale_offer = data.sale_offer;
    document.getElementById('update_offer_price_price').value = sale_offer.offer_price;
}

async function addSaleOfferPrice(){
    let user_id = localStorage.getItem('userId');
    let sale_id = getPathVariable('sw-3');
    let offer_id = document.getElementById('add_offer_price_offer_id').value;
    let offer_product_id = document.getElementById('add_offer_price_offer_product_id').value;
    let price = document.getElementById('add_offer_price_price').value;

    let formData = JSON.stringify({
        "user_id": user_id,
        "sale_id": sale_id,
        "offer_id": offer_id,
        "offer_product_id": offer_product_id,
        "price": price
    });

    console.log(formData);

    let returned = await servicePostAddSaleOfferPrice(formData);
    if (returned) {
        $("#add_offer_price_form").trigger("reset");
        $('#addOfferPriceModal').modal('hide');
        await initOfferDetail();
    }else{
        alert("Hata Oluştu");
    }
}

async function updateSaleOfferPrice(){
    let user_id = localStorage.getItem('userId');
    let sale_id = getPathVariable('sw-3');
    let offer_id = document.getElementById('update_offer_price_offer_id').value;
    let offer_product_id = document.getElementById('update_offer_price_offer_product_id').value;
    let price = document.getElementById('update_offer_price_price').value;

    let formData = JSON.stringify({
        "user_id": user_id,
        "sale_id": sale_id,
        "offer_id": offer_id,
        "offer_product_id": offer_product_id,
        "price": price
    });

    console.log(formData);

    let returned = await servicePostUpdateSaleOfferPrice(formData);
    if (returned) {
        $("#update_offer_price_form").trigger("reset");
        $('#updateOfferPriceModal').modal('hide');
        await initOfferDetail();
    }else{
        alert("Hata Oluştu");
    }
}
