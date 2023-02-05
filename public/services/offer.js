(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#add_offer_product_pcs_price").maskMoney({thousands:''});
        $("#add_offer_product_total_price").maskMoney({thousands:''});
        $("#add_offer_product_discounted_price").maskMoney({thousands:''});
        $("#update_offer_product_pcs_price").maskMoney({thousands:''});
        $("#update_offer_product_total_price").maskMoney({thousands:''});
        $("#update_offer_product_discounted_price").maskMoney({thousands:''});

		$('#add_offer_form').submit(function (e){
			e.preventDefault();
            addOffer();
		});

        $('#add_offer_request_product_button').click(function (e){
            e.preventDefault();
            let refcode = document.getElementById('add_offer_request_product_refcode').value;
            let product_name = document.getElementById('add_offer_request_product_name').value;
            let quantity = document.getElementById('add_offer_request_product_quantity').value;
            if (refcode == '' || product_name == '' || quantity == "0"){
                alert('Formu Doldurunuz');
                return false;
            }
            addProductToTable(refcode, product_name, quantity);
        });

        $('#add_offer_product_form').submit(function (e){
            e.preventDefault();
            addOfferProduct();
        });

        $('#update_offer_product_form').submit(function (e){
            e.preventDefault();
            updateOfferProduct();
        });
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
		// await initPage();
        await initOfferRequest();
        await initOffers();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initPage(){
    await getAdminsAddSelectId('update_offer_request_authorized_personnel');
    await getCompaniesAddSelectId('update_offer_request_company');
}

async function initEmployeeSelect(){
    let company_id = document.getElementById('update_offer_request_company').value;
    await getEmployeesAddSelectId(company_id, 'update_offer_request_company_employee');
}

async function openAddOfferModal(){
    $("#addOfferModal").modal('show');
    await getSuppliersAddSelectId('add_offer_company');
}


async function initOfferRequest(){
    let request_id = getPathVariable('offer');
    let data = await serviceGetOfferRequestById(request_id);
    let offer_request = data.offer_request;

    $("#offer-request-products").dataTable().fnDestroy();
    $('#offer-request-products tbody > tr').remove();

    $.each(offer_request.products, function (i, product) {
        let item = '<tr id="productRow' + product.id + '">\n' +
            '           <td>' + product.id + '</td>\n' +
            '           <td>' + product.ref_code + '</td>\n' +
            '           <td>' + product.product_name + '</td>\n' +
            '           <td>' + product.quantity + '</td>\n' +
            '       </tr>';
        $('#offer-request-products tbody').append(item);
    });

    let productDatatable = $('#offer-request-products').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Seçili Ürünler ile Teklif Oluştur',
                action: function ( e, dt, node, config ) {
                    openAddOfferModal(productDatatable.rows( { selected: true } ));
                }
            },
            'selectAll',
            'selectNone',
            // 'excel',
            // 'pdf'
        ],
        pageLength : 20,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'desc']],
        select: {
            style: 'multi'
        }
    });
}

async function addOffer(){
    let user_id = sessionStorage.getItem('userId');
    let request_id = getPathVariable('offer');
    let supplier_id = document.getElementById('add_offer_company').value;
    let table = $('#offer-request-products').DataTable();
    let rows = table.rows({ selected: true } );

    let products = [];
    if (rows.count() === 0){
        alert("Öncelikle seçim yapmalısınız.");
        $('#addOfferModal').modal('hide');
        $("#add_offer_form").trigger("reset");
    }else {
        rows.every(function (rowIdx, tableLoop, rowLoop) {
            let item = {
                "request_product_id": this.data()[0]
            }
            products.push(item);
        });

        let formData = JSON.stringify({
            "user_id": parseInt(user_id),
            "request_id": request_id,
            "supplier_id": supplier_id,
            "products": products
        });

        console.log(formData);

        let returned = await servicePostAddOffer(formData);
        console.log(returned)
        if (returned){
            $('#offer-request-products-body tr').removeClass('selected');
            $("#add_offer_form").trigger("reset");
            $('#addOfferModal').modal('hide');
            initOffers();
        }else{
            alert("Hata Oluştu");
        }

    }
}

async function initOffers(){
    let request_id = getPathVariable('offer');
    let data = await serviceGetOffersByRequestId(request_id);
    let offers = data.offers;

    $("#offers").dataTable().fnDestroy();
    $('#offers tbody > tr').remove();

    $.each(offers, function (i, offer) {
        let item = '<tr id="offerRow' + offer.id + '">\n' +
            '           <td>' + offer.id + '</td>\n' +
            '           <td>' + offer.offer_id + '</td>\n' +
            '           <td>' + offer.company_name + '</td>\n' +
            '           <td>' + offer.product_count + '</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button onclick="openOfferDetailModal(\'' + offer.offer_id + '\');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklif Detayı</span></button>\n' +
            '                      <a href="offer-print/'+ offer.offer_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Order Request PDF</span></a>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '       </tr>';
        $('#offers tbody').append(item);
    });

    $('#offers').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        dom: 'Bfrtip',
        buttons: [
            'excel',
            'pdf'
        ],
        pageLength : 20,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'desc']]
    });
}

async function openOfferDetailModal(offer_id){
    $("#offerDetailModal").modal('show');
    await initOfferDetailModal(offer_id);
}

async function initOfferDetailModal(offer_id){
    console.log(offer_id)
    document.getElementById('offer-detail-modal-offer-id').value = offer_id;
    let data = await serviceGetOfferById(offer_id);
    let offer = data.offer;
    console.log(offer)

    $("#offer-detail").dataTable().fnDestroy();
    $('#offer-detail tbody > tr').remove();

    $.each(offer.products, function (i, product) {
        let item = '<tr id="productRow' + product.id + '">\n' +
            '           <td>' + product.id + '</td>\n' +
            '           <td>' + checkNull(product.ref_code) + '</td>\n' +
            '           <td>' + checkNull(product.date_code) + '</td>\n' +
            '           <td>' + checkNull(product.package_type) + '</td>\n' +
            '           <td>' + checkNull(product.quantity) + '</td>\n' +
            '           <td>' + checkNull(product.pcs_price) + '</td>\n' +
            '           <td>' + checkNull(product.total_price) + '</td>\n' +
            '           <td>' + checkNull(product.discount_rate) + '</td>\n' +
            '           <td>' + checkNull(product.discounted_price) + '</td>\n' +
            '           <td>' + checkNull(product.vat_rate) + '</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button onclick="openUpdateOfferProductModal(\'' + offer_id + '\', '+ product.id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Güncelle</span></button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '       </tr>';
        $('#offer-detail tbody').append(item);
    });

    $('#offer-detail').DataTable({
        responsive: false,
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
        order: [[0, 'desc']]
    });
}

async function openUpdateOfferProductModal(offer_id, product_id){
    $("#updateOfferProductModal").modal('show');
    await initUpdateOfferProductModal(offer_id, product_id);
}

async function initUpdateOfferProductModal(offer_id, product_id){
    let data = await serviceGetOfferProductById(offer_id, product_id);
    let product = data.product;

    document.getElementById('update_offer_id').value = offer_id;
    document.getElementById('update_offer_product_id').value = product_id;

    document.getElementById('update_offer_product_ref_code').value = product.product_detail.ref_code;
    document.getElementById('update_offer_product_product_name').value = product.product_detail.product_name;
    document.getElementById('update_offer_product_date_code').value = checkNull(product.date_code);
    document.getElementById('update_offer_product_package_type').value = checkNull(product.package_type);
    document.getElementById('update_offer_product_quantity').value = checkNull(product.quantity);
    document.getElementById('update_offer_product_pcs_price').value = checkNull(product.pcs_price);
    document.getElementById('update_offer_product_total_price').value = checkNull(product.total_price);
    document.getElementById('update_offer_product_discount_rate').value = checkNull(product.discount_rate);
    document.getElementById('update_offer_product_discounted_price').value = checkNull(product.discounted_price);
}

async function updateOfferProduct(){
    let offer_id = document.getElementById('update_offer_id').value;
    let product_id = document.getElementById('update_offer_product_id').value;
    let ref_code = document.getElementById('update_offer_product_ref_code').value;
    let product_name = document.getElementById('update_offer_product_product_name').value;
    let date_code = document.getElementById('update_offer_product_date_code').value;
    let package_type = document.getElementById('update_offer_product_package_type').value;
    let quantity = document.getElementById('update_offer_product_quantity').value;
    let pcs_price = document.getElementById('update_offer_product_pcs_price').value;
    let total_price = document.getElementById('update_offer_product_total_price').value;
    let discount_rate = document.getElementById('update_offer_product_discount_rate').value;
    let discounted_price = document.getElementById('update_offer_product_discounted_price').value;
    let vat_rate = document.getElementById('update_offer_product_vat_rate').value;

    let formData = JSON.stringify({
        "ref_code": ref_code,
        "product_name": product_name,
        "date_code": date_code,
        "package_type": package_type,
        "quantity": quantity,
        "pcs_price": pcs_price,
        "total_price": total_price,
        "discount_rate": discount_rate,
        "discounted_price": discounted_price,
        "vat_rate": vat_rate
    });

    console.log(formData);

    let returned = await servicePostUpdateOfferProduct(formData, offer_id, product_id);
    if (returned){
        $("#update_offer_product_form").trigger("reset");
        $('#updateOfferProductModal').modal('hide');
        await initOfferDetailModal(offer_id);
    }else{
        alert("Hata Oluştu");
    }
}

async function openAddOfferProductModal(){
    $("#addOfferProductModal").modal('show');
}

async function addOfferProduct(){
    let offer_id = document.getElementById('offer-detail-modal-offer-id').value;
    let ref_code = document.getElementById('add_offer_product_ref_code').value;
    let product_name = document.getElementById('add_offer_product_product_name').value;
    let date_code = document.getElementById('add_offer_product_date_code').value;
    let package_type = document.getElementById('add_offer_product_package_type').value;
    let quantity = document.getElementById('add_offer_product_quantity').value;
    let pcs_price = document.getElementById('add_offer_product_pcs_price').value;
    let total_price = document.getElementById('add_offer_product_total_price').value;
    let discount_rate = document.getElementById('add_offer_product_discount_rate').value;
    let discounted_price = document.getElementById('add_offer_product_discounted_price').value;
    let vat_rate = document.getElementById('add_offer_product_vat_rate').value;

    let formData = JSON.stringify({
        "ref_code": ref_code,
        "product_name": product_name,
        "date_code": date_code,
        "package_type": package_type,
        "quantity": quantity,
        "pcs_price": pcs_price,
        "total_price": total_price,
        "discount_rate": discount_rate,
        "discounted_price": discounted_price,
        "vat_rate": vat_rate
    });

    console.log(formData);

    let returned = await servicePostAddOfferProduct(formData, offer_id);
    if (returned){
        $("#add_offer_product_form").trigger("reset");
        $('#addOfferProductModal').modal('hide');
        await initOfferDetailModal(offer_id);
    }else{
        alert("Hata Oluştu");
    }
}
