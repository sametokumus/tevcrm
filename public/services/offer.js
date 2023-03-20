(function($) {
    "use strict";

	$(document).ready(function() {

        $(":input").inputmask();
        $("#add_offer_product_pcs_price").maskMoney({thousands:'.', decimal:','});
        $("#add_offer_product_total_price").maskMoney({thousands:'.', decimal:','});
        $("#add_offer_product_discounted_price").maskMoney({thousands:'.', decimal:','});
        $("#update_offer_product_pcs_price").maskMoney({thousands:'.', decimal:','});
        $("#update_offer_product_total_price").maskMoney({thousands:'.', decimal:','});
        $("#update_offer_product_discounted_price").maskMoney({thousands:'.', decimal:','});

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
let short_code;
let global_id;

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
    console.log(data)

    $("#offer-request-products").dataTable().fnDestroy();
    $('#offer-request-products tbody > tr').remove();

    $.each(offer_request.products, function (i, product) {
        let item = '<tr id="productRow' + product.id + '">\n' +
            '           <td>' + product.id + '</td>\n' +
            '           <td>' + product.ref_code + '</td>\n' +
            '           <td>' + product.product_name + '</td>\n' +
            '           <td>' + product.quantity + '</td>\n' +
            '           <td>' + product.measurement_name + '</td>\n' +
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
                text: 'Seçili Ürünler için Teklif İste',
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
        order: [[0, 'asc']],
        select: {
            style: 'multi'
        }
    });

    let data2 = await serviceGetContactById(offer_request.owner_id);
    let contact = data2.contact;
    short_code = contact.short_code;
    global_id = offer_request.global_id;
}

async function addOffer(){
    let user_id = localStorage.getItem('userId');
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

            let products = $('#offer-request-products').DataTable();
            products.rows().deselect();
        }else{
            alert("Hata Oluştu");
        }

    }
}

async function initOffers(){
    let request_id = getPathVariable('offer');
    let data = await serviceGetOffersByRequestId(request_id);
    let offers = data.offers;
    console.log(offers)

    $("#offers").dataTable().fnDestroy();
    $('#offers tbody > tr').remove();

    $.each(offers, function (i, offer) {
        let bg_color = '';
        if (offer.products != null){
            if (offer.products[0].total_price != null){
                bg_color = 'bg-secondary';
            }
        }

        let item = '<tr id="offerRow' + offer.id + '" class="'+ bg_color +'">\n' +
            '           <td>' + offer.id + '</td>\n' +
            '           <td>' + short_code + '-RFQ-' + global_id + '</td>\n' +
            '           <td>' + offer.company_name + '</td>\n' +
            '           <td>' + offer.product_count + '</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button onclick="openOfferDetailModal(\'' + offer.offer_id + '\');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Fiyatları Gir</span></button>\n' +
            '                      <a href="offer-print/'+ offer.offer_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> RFQ PDF</span></a>\n' +
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
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'asc']]
    });
}

async function openOfferDetailModal(offer_id){
    $("#offerDetailModal").modal('show');
    await initOfferDetailModal(offer_id);
}
let editor;
let table;
// Activate an inline edit on click of a table cell
$('#offer-detail').on( 'click', 'tbody td.row-edit', function (e) {
    editor.inline( table.cells(this.parentNode, '*').nodes(), {
        submitTrigger: -1,
        submitHtml: '<i class="fas fa-lg fa-fw me-2 fa-save"/>'
    } );
} );
$("#offer-detail").on("click", "tbody td:nth-child(7)", function() {
    editor.field("pcs_price").input().inputmask();
});

async function initOfferDetailModal(offer_id){
    console.log(offer_id)
    document.getElementById('offer-detail-modal-offer-id').value = offer_id;
    let data = await serviceGetOfferById(offer_id);
    let offer = data.offer;
    console.log(offer)

    $("#offer-detail").dataTable().fnDestroy();
    $('#offer-detail tbody > tr').remove();
    //
    // $.each(offer.products, function (i, product) {
    //     let item = '<tr id="productRow' + product.id + '">\n' +
    //         '           <td>' + product.id + '</td>\n' +
    //         '           <td>' + checkNull(product.ref_code) + '</td>\n' +
    //         '           <td>' + checkNull(product.date_code) + '</td>\n' +
    //         '           <td>' + checkNull(product.package_type) + '</td>\n' +
    //         '           <td>' + checkNull(product.quantity) + '</td>\n' +
    //         '           <td>' + checkNull(product.measurement_name) + '</td>\n' +
    //         '           <td>' + changeCommasToDecimal(checkNull(product.pcs_price)) + '</td>\n' +
    //         '           <td>' + changeCommasToDecimal(checkNull(product.total_price)) + '</td>\n' +
    //         '           <td>' + checkNull(product.discount_rate) + '</td>\n' +
    //         '           <td>' + changeCommasToDecimal(checkNull(product.discounted_price)) + '</td>\n' +
    //         '           <td>' + checkNull(product.vat_rate) + '</td>\n' +
    //         '           <td>' + checkNull(product.currency) + '</td>\n' +
    //         '           <td>' + checkNull(product.lead_time) + '</td>\n' +
    //         '              <td>\n' +
    //         '                  <div class="btn-list">\n' +
    //         '                      <button onclick="openUpdateOfferProductModal(\'' + offer_id + '\', '+ product.id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Fiyatı Gir</span></button>\n' +
    //         '                  </div>\n' +
    //         '              </td>\n' +
    //         '       </tr>';
    //     $('#offer-detail tbody').append(item);
    // });

    // $('#offer-detail').DataTable({
    //     responsive: false,
    //     columnDefs: [
    //         { responsivePriority: 1, targets: 0 },
    //         { responsivePriority: 2, targets: -1 }
    //     ],
    //     dom: 'Bfrtip',
    //     // buttons: [
    //     //     {
    //     //         text: 'Teklife Yeni Ürün Ekle',
    //     //         action: function ( e, dt, node, config ) {
    //     //             openAddOfferProductModal();
    //     //         }
    //     //     }
    //     //     // 'excel',
    //     //     // 'pdf'
    //     // ],
    //     pageLength : 20,
    //     scrollX: true,
    //     language: {
    //         url: "services/Turkish.json"
    //     },
    //     order: [[0, 'asc']]
    // });

    editor = new $.fn.dataTable.Editor( {
        data: offer.products,
        table: "#offer-detail",
        idSrc: "id",
        fields: [ {
            name: "pcs_price",
            type: "num-fmt",
            def: "",
            fieldInfo: "Enter the price of the item in dollars and cents.",
            mask: "$9,999.99",
            unmask: function (data) {
                return data.replace(/[\$,]/g, ''); // remove dollar sign and comma separators
            },
            set: function (data) {
                this.val("$" + Inputmask.format(data, { mask: "$9,999.99" })); // add dollar sign and comma separators
            }
        }, {
            name: "discount_rate"
        }, {
            name: "vat_rate"
        }, {
            name: "currency",
            type: "select",
            options: [
                { value: 'TRY', label: 'TRY' },
                { value: 'EUR', label: 'EUR' },
                { value: 'USD', label: 'USD' },
                { value: 'GBP', label: 'GBP' }
            ]
        }, {
            name: "lead_time",
            type: "text",
            validate: {
                number: true
            }
        }
        ]
    } );

    editor.on('preSubmit', function(e, data, action) {
        if (action !== 'remove') {
            let fieldData = editor.field('currency').val();
            console.log(fieldData)
            if (!fieldData) {
                editor.field('first_name').error('First Name is required');
                return false;
            }

            // Submit the edited row data
            editor.submit();
        }
    });

    table = $('#offer-detail').DataTable( {
        dom: "Bfrtip",
        data: offer.products,
        columns: [
            { data: "id", editable: false },
            { data: "ref_code" },
            { data: "date_code" },
            { data: "package_type" },
            { data: "quantity" },
            { data: "measurement_name" },
            { data: "pcs_price" },
            { data: "total_price" },
            { data: "discount_rate" },
            { data: "discounted_price" },
            { data: "vat_rate" },
            { data: "currency" },
            { data: "lead_time" },
            {
                data: null,
                defaultContent: '<i class="fas fa-lg fa-fw me-2 fa-edit"/>',
                className: 'row-edit dt-center',
                orderable: false
            },
        ],
        select: {
            style: 'os',
            selector: 'td:first-child'
        },
    } );

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
    document.getElementById('update_offer_product_pcs_price').value = checkNull(changeCommasToDecimal(product.pcs_price));
    document.getElementById('update_offer_product_total_price').value = checkNull(changeCommasToDecimal(product.total_price));
    document.getElementById('update_offer_product_discount_rate').value = checkNull(changeCommasToDecimal(product.discount_rate));
    document.getElementById('update_offer_product_discounted_price').value = checkNull(changeCommasToDecimal(product.discounted_price));
    document.getElementById('update_offer_product_vat_rate').value = checkNull(changeCommasToDecimal(product.vat_rate));
    document.getElementById('update_offer_product_currency').value = checkNull(product.currency);
    document.getElementById('update_offer_product_lead_time').value = checkNull(product.lead_time);
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
    let currency = document.getElementById('update_offer_product_currency').value;
    let lead_time = document.getElementById('update_offer_product_lead_time').value;

    let formData = JSON.stringify({
        "ref_code": ref_code,
        "product_name": product_name,
        "date_code": date_code,
        "package_type": package_type,
        "quantity": quantity,
        "pcs_price": changePriceToDecimal(pcs_price),
        "total_price": changePriceToDecimal(total_price),
        "discount_rate": changePriceToDecimal(discount_rate),
        "discounted_price": changePriceToDecimal(discounted_price),
        "vat_rate": changePriceToDecimal(vat_rate),
        "currency": currency,
        "lead_time": lead_time
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
    let currency = document.getElementById('add_offer_product_currency').value;
    let lead_time = document.getElementById('add_offer_product_lead_time').value;

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
        "vat_rate": vat_rate,
        "currency": currency,
        "lead_time": lead_time
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
