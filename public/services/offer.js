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



		$('#update_product_name_form').submit(function (e){
			e.preventDefault();
            updateProductName();
		});

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
        await initOfferRequest();
        await initOffers();

	});

})(window.jQuery);
let short_code;
let global_id;
let sale_id;

function checkRole(){
	return true;
}

async function changeStatus(){
    fastChangeStatus(3, sale_id);
}

async function initEmployeeSelect(){
    let company_id = document.getElementById('update_offer_request_company').value;
    await getEmployeesAddSelectId(company_id, 'update_offer_request_company_employee');
}

async function openAddOfferModal(){
    $("#addOfferModal").modal('show');
    await getSuppliersAddSelectId('add_offer_company');
}

async function openOfferRequestNoteModal(note){
    $("#offerRequestNoteModal").modal('show');
    document.getElementById('show_offer_request_note').textContent = note;
}

async function initOfferRequest(){
    let request_id = getPathVariable('offer');
    let data = await serviceGetOfferRequestById(request_id);
    let offer_request = data.offer_request;
    console.log(offer_request)

    sale_id = offer_request.sale_id;

    $("#offer-request-products").dataTable().fnDestroy();
    $('#offer-request-products tbody > tr').remove();

    $.each(offer_request.products, function (i, product) {
        let note = '';
        if (product.note != null && product.note != ''){
            note = '<div class="btn-list">\n' +
                '       <button onclick="openOfferRequestNoteModal(\'' + product.note + '\');" class="btn btn-sm btn-warning"><span class="fe fe-edit"> Satın Alma Notu</span></button>\n' +
                '   </div>';
        }
        let measurement_name = '';
        if (Lang.getLocale() == 'tr'){
            measurement_name = product.measurement_name_tr;
        }else{
            measurement_name = product.measurement_name_tr;
        }
        let classname= '';
        if (product.is_offered == 1){
            classname= 'bg-success bg-opacity-50';
        }
        let item = '<tr id="productRow' + product.id + '" class="'+ classname +'">\n' +
            '           <td>' + product.sequence + '</td>\n' +
            '           <td>' + product.id + '</td>\n' +
            '              <td>\n' +
            '                  '+ note +'\n' +
            '              </td>\n' +
            '           <td>' + product.ref_code + '</td>\n' +
            '           <td>' + product.product_name + '</td>\n' +
            '           <td>' + product.quantity + '</td>\n' +
            '           <td>' + checkNull(measurement_name) + '</td>\n' +
            '           <td class="d-none">' + product.product.id + '</td>\n' +
            '       </tr>';
        $('#offer-request-products tbody').append(item);
    });

    let productDatatable = $('#offer-request-products').DataTable({
        responsive: true,
        columnDefs: [
            { responsivePriority: 1, width: '40px', targets: 0 },
            { responsivePriority: 2, targets: -1 },
            { width: '100px', targets: 1 }
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
            {
                text: 'Seçili Ürün Adını Değiştir',
                action: function ( e, dt, node, config ) {
                    openUpdateProductNameModal(productDatatable.rows( { selected: true } ));
                }
            },
            // 'excel',
            // 'pdf'
        ],
        paging : false,
        scrollX: true,
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
    // let supplier_id = document.getElementById('add_offer_company').value;
    let table = $('#offer-request-products').DataTable();
    let rows = table.rows({ selected: true } );

    let suppliers = [];
    let supplier_objs = $('#add_offer_company').find(':selected');
    for (let i = 0; i < supplier_objs.length; i++) {
        suppliers.push(supplier_objs[i].value);
    }

    let products = [];
    if (rows.count() === 0){
        alert("Öncelikle seçim yapmalısınız.");
        $('#addOfferModal').modal('hide');
        $("#add_offer_form").trigger("reset");
    }else {
        rows.every(function (rowIdx, tableLoop, rowLoop) {
            let item = {
                "request_product_id": this.data()[1]
            }
            products.push(item);
        });

        let returned = true;
        for (const supplier of suppliers) {

            let formData = JSON.stringify({
                "user_id": parseInt(user_id),
                "request_id": request_id,
                "supplier_id": supplier,
                "products": products
            });


            let rt = await servicePostAddOffer(formData);
            if (!rt){returned = false;}
        }


        if (returned){
            $('#offer-request-products-body tr').removeClass('selected');
            $("#add_offer_form").trigger("reset");
            $('#addOfferModal').modal('hide');
            await initOffers();
            await initOfferRequest();

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
        let btn_text = 'Fiyatları Gir';
        let btn_class = 'btn-theme';
        if (offer.products.length > 0){
            if (offer.products[0].total_price != null){
                bg_color = 'bg-secondary';
                btn_text = 'Fiyatları Güncelle';
                btn_class = 'btn-dark';
            }
        }
        let pdf_btn = '';
        if (offer.rfq_url != null) {
            pdf_btn =  '<a href="offer-print/'+ offer.offer_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> RFQ PDF Düzenle</span></a>\n' +
                '       <a href="' + offer.rfq_url + '" target="_blank" class="btn btn-sm btn-theme"><span class="fe fe-edit"> RFQ PDF Görüntüle</span></a>\n';
        }else{
            pdf_btn =  '<a href="offer-print/'+ offer.offer_id +'" class="btn btn-sm btn-theme"><span class="fe fe-edit"> RFQ PDF Oluştur</span></a>\n';
        }

        let item = '<tr id="offerRow' + offer.id + '" class="'+ bg_color +'">\n' +
            '           <td>' + (i+1) + '</td>\n' +
            '           <td>' + short_code + '-RFQ-' + global_id + '-' + offer.id + '</td>\n' +
            '           <td>' + offer.company_name + '</td>\n' +
            '           <td>' + offer.product_count + '</td>\n' +
            '              <td>\n' +
            '                  <div class="btn-list">\n' +
            '                      <button onclick="openOfferDetailModal(\'' + offer.offer_id + '\');" class="btn btn-sm '+ btn_class +'"><span class="fe fe-edit"> '+ btn_text +'</span></button>\n' +
            '                      ' + pdf_btn +
            '                      <button onclick="deleteOffer(\'' + offer.offer_id + '\');" class="btn btn-sm btn-danger"><span class="fe fe-edit"> Sil</span></button>\n' +
            '                  </div>\n' +
            '              </td>\n' +
            '       </tr>';
        $('#offers tbody').append(item);
    });

    $('#offers').DataTable({
        responsive: false,
        columnDefs: [
            { responsivePriority: 1, width: '40px', targets: 0 },
            { responsivePriority: 2, targets: -1 },
            { width: '100px', targets: 3 }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Tedarikçilere Mail Gönder',
                action: function ( e, dt, node, config ) {
                    openSendSupplierMailModal();
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

async function openOfferDetailModal(offer_id){
    $("#offerDetailModal").modal('show');
    await initOfferDetailModal(offer_id);
}

function initMaskMoney() {
    $('input[id^="DTE_Field_pcs_price"]').maskMoney({thousands:'.', decimal:','});
    $('input[id^="DTE_Field_total_price"]').maskMoney({thousands:'.', decimal:','});
    $('input[id^="DTE_Field_discount_rate"]').maskMoney({thousands:'.', decimal:','});
    $('input[id^="DTE_Field_discounted_price"]').maskMoney({thousands:'.', decimal:','});
    $('input[id^="DTE_Field_vat_rate"]').maskMoney({thousands:'.', decimal:','});
}

let editor;
let table;
// Activate an inline edit on click of a table cell
$('#offer-detail').on( 'click', 'tbody td.row-edit', function (e) {
    editor.inline( table.cells(this.parentNode, '*').nodes(), {
        submitTrigger: -1,
        submitHtml: '<i class="fas fa-lg fa-fw me-2 fa-save"/>'
    } );
    initMaskMoney();
} );

async function initOfferDetailModal(offer_id){
    console.log(offer_id)
    document.getElementById('offer-detail-modal-offer-id').value = offer_id;
    let data = await serviceGetOfferById(offer_id);
    let offer = data.offer;
    console.log(offer)
    document.getElementById('offer_detail_show_supplier_name').textContent = offer.company_name;

    $("#offer-detail").dataTable().fnDestroy();
    $('#offer-detail tbody > tr').remove();

    editor = new $.fn.dataTable.Editor( {
        data: offer.products,
        table: "#offer-detail",
        idSrc: "id",
        fields: [ {
            name: "id",
            type: "readonly",
            attr: {
                class: 'form-control'
            }
        },{
            name: "product_name",
            attr: {
                class: 'form-control'
            }
        },{
            name: "date_code",
            attr: {
                class: 'form-control'
            }
        },{
            name: "package_type",
            attr: {
                class: 'form-control'
            }
        },{
            name: "quantity",
            attr: {
                class: 'form-control'
            }
        },{
            name: "pcs_price",
            attr: {
                class: 'form-control'
            }
        },{
            name: "total_price",
            type: "readonly",
            attr: {
                class: 'form-control'
            }
        }, {
            name: "discount_rate",
            attr: {
                class: 'form-control'
            }
        }, {
            name: "discounted_price",
            attr: {
                class: 'form-control'
            }
        }, {
            name: "currency",
            type: "select",
            options: [
                { value: 'TRY', label: 'TRY' },
                { value: 'EUR', label: 'EUR' },
                { value: 'USD', label: 'USD' },
                { value: 'GBP', label: 'GBP' }
            ],
            attr: {
                class: 'form-control'
            }
        }, {
            name: "lead_time",
            attr: {
                class: 'form-control'
            }
        }
        ]
    } );

    editor.on('preSubmit', async function(e, data, action) {
        if (action !== 'remove') {
            let id = editor.field('id').val();
            let product_name = editor.field('product_name').val();
            let quantity = editor.field('quantity').val();
            let pcs_price = editor.field('pcs_price').val();
            let total_price = editor.field('total_price').val();
            let discount_rate = editor.field('discount_rate').val();
            let discounted_price = editor.field('discounted_price').val();
            let currency = editor.field('currency').val();
            let lead_time = editor.field('lead_time').val();

            let formData = JSON.stringify({
                "id": id,
                "date_code": "",
                "package_type": "",
                "product_name": product_name,
                "quantity": quantity,
                "pcs_price": changePriceToDecimal(pcs_price),
                "total_price": changePriceToDecimal(total_price),
                "discount_rate": changePriceToDecimal(discount_rate),
                "discounted_price": changePriceToDecimal(discounted_price),
                "vat_rate": "",
                "currency": currency,
                "lead_time": lead_time,
                "offer_rev": false
            });

            let offer_id = document.getElementById('offer-detail-modal-offer-id').value;
            let returned = await servicePostUpdateOfferProduct(formData, offer_id, id);
            if (returned){
                await initOfferDetailModal(offer_id);
                await initOffers();
            }else{
                alert("Hata Oluştu");
            }

            // Submit the edited row data
            editor.submit();
        }
    });
    $( editor.field( 'pcs_price' ).input() ).on( 'keyup', function (e){
        let quantity = editor.field('quantity').val();
        let pcs_price = editor.field('pcs_price').val();
        if (pcs_price != ''){
            let total_price = quantity * parseFloat(changePriceToDecimal(pcs_price));
            document.getElementById('DTE_Field_total_price').value = changeCommasToDecimal(total_price.toFixed(2));
        }
    });
    $( editor.field( 'discount_rate' ).input() ).on( 'keyup', function (e){
        let total_price = editor.field('total_price').val();
        let pcs_price = editor.field('pcs_price').val();
        let discount_rate = editor.field('discount_rate').val();
        if (pcs_price != '' && total_price != '' && discount_rate != '' && discount_rate != '0,00'){
            let discounted_price = parseFloat(changePriceToDecimal(total_price)) - (parseFloat(changePriceToDecimal(total_price)) / 100 * parseFloat(changePriceToDecimal(discount_rate)));
            console.log(parseFloat(changePriceToDecimal(total_price)), pcs_price, parseFloat(changePriceToDecimal(discount_rate)), discounted_price)
            document.getElementById('DTE_Field_discounted_price').value = changeCommasToDecimal(discounted_price);
        }
    });

    table = $('#offer-detail').DataTable( {
        dom: "Bfrtip",
        data: offer.products,
        columns: [
            { data: "id", editable: false },
            { data: "ref_code" },
            { data: "product_name", className:  "row-edit" },
            { data: "quantity", className:  "row-edit" },
            { data: "measurement_name_tr" },
            { data: "pcs_price", className:  "row-edit" },
            { data: "total_price", className:  "row-edit" },
            { data: "discount_rate", className:  "row-edit" },
            { data: "discounted_price", className:  "row-edit" },
            { data: "currency", className:  "row-edit" },
            { data: "lead_time", className:  "row-edit" },
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
        paging : false,
        scrollX: true,
        language: {
            url: "services/Turkish.json"
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


async function openUpdateProductNameModal(){

    let table = $('#offer-request-products').DataTable();
    let rows = table.rows({ selected: true } );

    if (rows.count() === 0){
        alert("Öncelikle seçim yapmalısınız.");
    }else if(rows.count() > 1){
        alert("Tek bir satır seçmeniz gerekmektedir.");
    }else {
        $("#updateProductNameModal").modal('show');

        let product_id;
        rows.every(function (rowIdx, tableLoop, rowLoop) {
            product_id = this.data()[7];
        });

        document.getElementById('update_product_name_id').value = product_id;



    }
}


async function updateProductName(){
    let product_name = document.getElementById('update_product_name').value;
    if (product_name != ''){

        let product_id = document.getElementById('update_product_name_id').value;
        let formData = JSON.stringify({
            "product_name": product_name
        });

        console.log(formData);
        let returned = await servicePostUpdateProductName(formData, product_id);
        if (returned){
            $('#offer-request-products-body tr').removeClass('selected');
            $("#update_product_name_form").trigger("reset");
            $('#updateProductNameModal').modal('hide');
            initOfferRequest();

            let products = $('#offer-request-products').DataTable();
            products.rows().deselect();
        }else{
            alert("Hata Oluştu");
        }

    }else{
        alert('Geçerli bir isim giriniz.');
    }
}


async function deleteOffer(offer_id){
    let returned = await serviceGetDeleteOffer(offer_id);
    if(returned){
        await initOffers();
        await initOfferRequest();
    }
}



async function openSendSupplierMailModal(){
    $("#sendSupplierMailModal").modal('show');
    let request_id = getPathVariable('offer');
    await initSendSupplierMailModal(request_id);
}

async function initSendSupplierMailModal(request_id){
    await getAdminsAddSelectId('send_mail_staff');
    await getMailLayoutsAddSelectId('send_mail_layouts');

    let data = await serviceGetMailableSuppliersByRequestId(request_id);
    let suppliers = data.suppliers;
    console.log(data)

    document.getElementById('mail_request_id').value = request_id;
    document.getElementById('send_mail_staff').value = data.purchasing_staff_id;

    let tagSourceArray = [];
    $.each(suppliers, function (i, supplier) {
        $.each(supplier.employees, function (i, employee) {
            tagSourceArray.push({ label: employee.email, value: employee.id });
        });
    });

    $('#send_mail_to_address').tagit({
        fieldName: 'to-mails',
        tagSource: ['c++', 'java', 'php', 'javascript', 'ruby', 'python', 'c'],
        autocomplete: false
    });

}

async function setMailLayout(){
    let layout_id = document.getElementById('send_mail_layouts').value;

    let data = await serviceGetEmailLayoutById(layout_id);
    let layout = data.layout;


    document.getElementById('send_mail_subject').value = layout.subject;
    $('#send_mail_subject').value = layout.subject;
    $('#send_mail_text').summernote('code', checkNull(layout.text));
}
