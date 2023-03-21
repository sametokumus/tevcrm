(function($) {
    "use strict";

    $(":input").inputmask();
    $("#add_offer_price_price").maskMoney({thousands:'.', decimal:','});
    $("#update_offer_price_price").maskMoney({thousands:'.', decimal:','});

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

function initMaskMoney() {
    $('input[id^="DTE_Field_offer_price"]').maskMoney({thousands:'.', decimal:','});
}

let table;
let editor;

$('#sales-detail').on( 'click', 'tbody td.row-edit', function (e) {
    editor.inline( table.cells(this.parentNode, '*').nodes(), {
        submitTrigger: -1,
        submitHtml: '<i class="fas fa-lg fa-fw me-2 fa-save"/>'
    } );
    initMaskMoney();
} );

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

        // $.each(offers, function (i, offer) {
        //
        //     let btn_list = "";
        //     if (offer.offer_price == null){
        //         btn_list = '<button type="button" onclick="openAddOfferPriceModal(\''+ offer.offer_id +'\', '+ offer.offer_product_id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklif Fiyatı Ekle</span></button>';
        //     }else{
        //         btn_list = '<button type="button" onclick="openUpdateOfferPriceModal(\''+ offer.offer_id +'\', '+ offer.offer_product_id +');" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklif Fiyatı Güncelle</span></button>';
        //     }
        //
        //     let item = '<tr id="productRow' + offer.offer_product_id + '">\n' +
        //         '           <td>' + offer.offer_product_id + '</td>\n' +
        //         '           <td class="d-none">' + offer.offer_id + '</td>\n' +
        //         '           <td class="d-none">' + offer.product_id + '</td>\n' +
        //         '           <td class="d-none">' + offer.supplier_id + '</td>\n' +
        //         '           <td>' + offer.supplier_name + '</td>\n' +
        //         '           <td>' + checkNull(offer.product_ref_code) + '</td>\n' +
        //         '           <td>' + checkNull(offer.date_code) + '</td>\n' +
        //         '           <td>' + checkNull(offer.package_type) + '</td>\n' +
        //         '           <td>' + checkNull(offer.lead_time) + '</td>\n' +
        //         '           <td>' + checkNull(offer.request_quantity) + '</td>\n' +
        //         '           <td>' + checkNull(offer.offer_quantity) + '</td>\n' +
        //         '           <td>' + checkNull(offer.measurement_name) + '</td>\n' +
        //         '           <td>' + changeCommasToDecimal(offer.pcs_price) + '</td>\n' +
        //         '           <td>' + changeCommasToDecimal(offer.total_price) + '</td>\n' +
        //         '           <td>' + checkNull(offer.discount_rate) + '</td>\n' +
        //         '           <td>' + changeCommasToDecimal(offer.discounted_price) + '</td>\n' +
        //         '           <td>' + checkNull(offer.vat_rate) + '</td>\n' +
        //         '           <td>' + checkNull(offer.currency) + '</td>\n' +
        //         '           <td>' + changeCommasToDecimal(offer.offer_price) + '</td>\n' +
        //         '           <td>' + checkNull(offer.offer_currency) + '</td>\n' +
        //         '           <td>' + checkNull(offer.offer_lead_time) + '</td>\n' +
        //         '           <td>\n' +
        //         '               <div class="btn-list">\n' +
        //         '                   '+ btn_list +'\n' +
        //         '               </div>\n' +
        //         '           </td>\n' +
        //         '       </tr>';
        //     $('#sales-detail tbody').append(item);
        // });
        // $('#sales-detail').DataTable({
        //     responsive: false,
        //     columnDefs: [
        //         {responsivePriority: 1, targets: 0},
        //         {responsivePriority: 2, targets: -1}
        //     ],
        //     dom: 'Bfrtip',
        //     buttons: [
        //
        //     ],
        //     pageLength: 20,
        //     scrollX: true,
        //     language: {
        //         url: "services/Turkish.json"
        //     },
        //     order: [[0, 'asc']]
        // });


        editor = new $.fn.dataTable.Editor( {
            data: offers,
            table: "#sales-detail",
            idSrc: "id",
            fields: [ {
                name: "id",
                type: "readonly",
                attr: {
                    class: 'form-control'
                }
            },{
                name: "offer_price",
                attr: {
                    class: 'form-control'
                }
            }, {
                name: "offer_currency",
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
                name: "offer_lead_time",
                attr: {
                    class: 'form-control'
                }
            }
            ]
        } );

        editor.on('preSubmit', async function(e, data, action) {
            console.log(1)
            if (action !== 'remove') {
                let user_id = localStorage.getItem('userId');
                let id = editor.field('id').val();
                let offer_price = editor.field('offer_price').val();
                let offer_currency = editor.field('offer_currency').val();
                let offer_lead_time = editor.field('offer_lead_time').val();

                let formData = JSON.stringify({
                    "id": id,
                    "user_id": user_id,
                    "offer_price": changePriceToDecimal(offer_price),
                    "offer_currency": offer_currency,
                    "offer_lead_time": offer_lead_time
                });

                let returned = await servicePostAddSaleOfferPrice(formData);
                if (returned) {
                }else{
                    alert("Hata Oluştu");
                }

                // Submit the edited row data
                editor.submit();
            }
        });

        table = $('#sales-detail').DataTable( {
            dom: "Bfrtip",
            data: offers,
            columns: [
                { data: "id", editable: false },
                { data: "offer_id", visible: false },
                { data: "product_id", visible: false },
                { data: "supplier_id", visible: false },
                { data: "supplier_name" },
                { data: "product_ref_code" },
                { data: "date_code" },
                { data: "package_type" },
                { data: "lead_time" },
                { data: "request_quantity" },
                { data: "offer_quantity" },
                { data: "measurement_name" },
                { data: "pcs_price" },
                { data: "total_price" },
                { data: "discount_rate" },
                { data: "discounted_price" },
                { data: "vat_rate" },
                { data: "currency" },
                { data: "offer_price" },
                { data: "offer_currency" },
                { data: "offer_lead_time" },
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
            scrollX: true,
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
            language: {
                url: "services/Turkish.json"
            },
        } );


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
    document.getElementById('update_offer_price_currency').value = sale_offer.offer_currency;
}

async function addSaleOfferPrice(){
    let user_id = localStorage.getItem('userId');
    let sale_id = getPathVariable('sw-3');
    let offer_id = document.getElementById('add_offer_price_offer_id').value;
    let offer_product_id = document.getElementById('add_offer_price_offer_product_id').value;
    let price = document.getElementById('add_offer_price_price').value;
    let currency = document.getElementById('add_offer_price_currency').value;

    let formData = JSON.stringify({
        "user_id": user_id,
        "sale_id": sale_id,
        "offer_id": offer_id,
        "offer_product_id": offer_product_id,
        "price": changePriceToDecimal(price),
        "currency": currency
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
    let currency = document.getElementById('update_offer_price_currency').value;

    let formData = JSON.stringify({
        "user_id": user_id,
        "sale_id": sale_id,
        "offer_id": offer_id,
        "offer_product_id": offer_product_id,
        "price": changePriceToDecimal(price),
        "currency": currency
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
