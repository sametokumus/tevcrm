(function($) {
    "use strict";

    $(":input").inputmask();
    $("#add_offer_price_price").maskMoney({thousands:'.', decimal:','});
    $("#update_offer_price_price").maskMoney({thousands:'.', decimal:','});
    $("#add_batch_offer_currency_change").maskMoney({thousands:'.', decimal:','});

	$(document).ready(function() {

        $('#add_offer_price_form').submit(function (e){
            e.preventDefault();
            addSaleOfferPrice();
        });

        $('#update_offer_price_form').submit(function (e){
            e.preventDefault();
            updateSaleOfferPrice();
        });

        $('#add_batch_process_form').submit(function (e){
            e.preventDefault();
            addOfferBatchProcess();
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
                { data: "product_name" },
                { data: "date_code", visible: false },
                { data: "package_type", visible: false },
                { data: "lead_time" },
                { data: "request_quantity" },
                { data: "offer_quantity" },
                { data: "measurement_name" },
                { data: "pcs_price" },
                { data: "total_price" },
                { data: "discount_rate",
                    render: function (data, type, row) {
                        if (type === 'display' && data === '0.00') {
                            return '';
                        }
                        return data;
                    }  },
                { data: "discounted_price",
                    render: function (data, type, row) {
                        if (type === 'display' && data === '0,00') {
                            return '';
                        }
                        return data;
                    } },
                { data: "vat_rate",
                    render: function (data, type, row) {
                        if (type === 'display' && data === '0.00') {
                            return '';
                        }
                        return data;
                    }  },
                { data: "currency" },
                { data: "offer_price", className:  "row-edit",
                    render: function (data, type, row) {
                        if (type === 'display' && data === '0,00') {
                            return '';
                        }
                        return data;
                    }  },
                { data: "offer_currency", className:  "row-edit" },
                { data: "offer_lead_time", className:  "row-edit" },
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
                    text: 'Seçili Ürünler için Toplu İşlem Uygula',
                    action: function ( e, dt, node, config ) {
                        openAddBatchProcessModal(table.rows( { selected: true } ));
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
            select: {
                style: 'multi'
            }
        } );


    }else{
        alert('Bu sipariş teklif oluşturmaya hazır değildir.');
    }
}

async function openAddBatchProcessModal(){
    $("#addBatchProcessModal").modal('show');
    $('.modal-backdrop').css('background-color', 'transparent');
}

async function addOfferBatchProcess(){
    let user_id = localStorage.getItem('userId');
    let rows = table.rows({ selected: true } ).data();

    if (rows.count() === 0){
        alert("Öncelikle seçim yapmalısınız.");
        $('#addBatchProcessModal').modal('hide');
        $("#add_batch_process_form").trigger("reset");
    }else {
        let success = true;
        $.each(rows, async function (i, row) {

            let id = row.id;
            let profit_rate = document.getElementById('add_batch_offer_profit_rate').value;
            let offer_currency = document.getElementById('add_batch_offer_currency').value;
            let offer_currency_change = document.getElementById('add_batch_offer_currency_change').value;
            let offer_lead_time = document.getElementById('add_batch_offer_lead_time').value;
            let total_price = row.total_price;
            let discounted_price = row.discounted_price;

            console.log(total_price, discounted_price);

            let price = total_price;
            if (discounted_price != '' && discounted_price != null && discounted_price != '0,00'){
                price = discounted_price;
            }

            let offer_price = parseFloat(changePriceToDecimal(price)) * parseFloat(changePriceToDecimal(offer_currency_change));
            offer_price = offer_price + (offer_price / 100 * profit_rate);

            let formData = JSON.stringify({
                "id": id,
                "user_id": user_id,
                "offer_price": offer_price,
                "offer_currency": offer_currency,
                "offer_lead_time": offer_lead_time
            });
            console.log(formData)

            let returned = await servicePostAddSaleOfferPrice(formData);
            console.log(returned)
            if (returned) {
            }else{
                success = false;
            }

        });
        console.log(success)
        if (success) {
            $('#addBatchProcessModal').modal('hide');
            $("#add_batch_process_form").trigger("reset");
            await initOfferDetail()
        }else{
            alert("Hata Oluştu");
        }

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
