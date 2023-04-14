(function($) {
    "use strict";

    $(":input").inputmask();
    $("#update_quote_freight").maskMoney({thousands:'.', decimal:','});
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

        $('#update_quote_form').submit(function (e){
            e.preventDefault();
            updateQuote();
        });

	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
		await initOfferDetail();


        await getPaymentTermsAddSelectId('update_quote_payment_term');
        await getDeliveryTermsAddSelectId('update_quote_delivery_term');

        await initQuote();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

function initMaskMoney() {
    $('input[id^="DTE_Field_offer_price"]').maskMoney({thousands:'.', decimal:','});
    $('input[id^="DTE_Field_offer_pcs_price"]').maskMoney({thousands:'.', decimal:','});
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
                name: "offer_quantity",
                type: "readonly",
                attr: {
                    class: 'form-control'
                }
            },{
                name: "offer_pcs_price",
                attr: {
                    class: 'form-control'
                }
            },{
                name: "offer_price",
                type: "readonly",
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
        $( editor.field( 'offer_pcs_price' ).input() ).on( 'keyup', function (e){
            let quantity = editor.field('offer_quantity').val();
            let pcs_price = editor.field('offer_pcs_price').val();
            if (pcs_price != ''){
                let total_price = quantity * parseFloat(changePriceToDecimal(pcs_price));
                document.getElementById('DTE_Field_offer_price').value = changeCommasToDecimal(total_price.toFixed(2));
            }
        });

        editor.on('preSubmit', async function(e, data, action) {
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

                // alert("asdsa")
                // await initSaleTableFooter();
                // Submit the edited row data

                editor.submit();
            }
        });
        table = $('#sales-detail').DataTable( {
            dom: "Bfrtip",
            data: offers,
            columns: [{
                title: 'N#',
                data: null,
                render: (data, type, row, meta) => (meta.row + 1)
            },
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
                { data: "offer_pcs_price", className:  "row-edit",
                    render: function (data, type, row) {
                        if (type === 'display' && data === '0,00') {
                            return '';
                        }
                        return data;
                    }  },
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
            paging: false,
            select: {
                style: 'multi'
            }
        } );


    }else{
        alert('Bu sipariş teklif oluşturmaya hazır değildir.');
    }

    await initSaleTableFooter();
}

async function openAddBatchProcessModal(){
    $("#addBatchProcessModal").modal('show');
    $('.modal-backdrop').css('background-color', 'transparent');
}


async function addOfferBatchProcess(){
    let user_id = localStorage.getItem('userId');
    let rows = Array.from(table.rows({ selected: true }).data());


    if (rows.length === 0){
        alert("Öncelikle seçim yapmalısınız.");
        $('#addBatchProcessModal').modal('hide');
        $("#add_batch_process_form").trigger("reset");
    }else {
        let promises = rows.map(async (row) => {
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

            return returned;
        });

        let results = await Promise.all(promises);

        let success = results.every(result => result);
        if (success) {
            $('#addBatchProcessModal').modal('hide');
            $("#add_batch_process_form").trigger("reset");
            await initOfferDetail()
        } else {
            alert("Hata Oluştu");
        }


    }
}



async function initQuote(){
    let sale_id = getPathVariable('sw-3');
    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;

    document.getElementById('update_quote_id').value = quote.id;
    document.getElementById('update_quote_payment_term').value = checkNull(quote.payment_term);
    document.getElementById('update_quote_lead_time').value = checkNull(quote.lead_time);
    document.getElementById('update_quote_delivery_term').value = checkNull(quote.delivery_term);
    document.getElementById('update_quote_country_of_destination').value = checkNull(quote.country_of_destination);
    document.getElementById('update_quote_freight').value = changeCommasToDecimal(quote.freight);
    $('#update_quote_note').summernote('code', checkNull(quote.note));
}
async function updateQuote(){
    let sale_id = getPathVariable('sw-3');
    let quote_id = document.getElementById('update_quote_id').value;
    let payment_term = document.getElementById('update_quote_payment_term').value;
    let lead_time = document.getElementById('update_quote_lead_time').value;
    let delivery_term = document.getElementById('update_quote_delivery_term').value;
    let country_of_destination = document.getElementById('update_quote_country_of_destination').value;
    let freight = document.getElementById('update_quote_freight').value;
    let note = document.getElementById('update_quote_note').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "quote_id": quote_id,
        "payment_term": payment_term,
        "lead_time": lead_time,
        "delivery_term": delivery_term,
        "country_of_destination": country_of_destination,
        "freight": changePriceToDecimal(freight),
        "note": note
    });


    let returned = await servicePostUpdateQuote(formData);
    if (returned){
        showAlert("Bilgiler Kaydedildi.")
    }else{
        alert("Hata Oluştu");
    }
}


async function initSaleTableFooter(){
    let tableSales = $("#sales-detail").DataTable();
    let supplier_total = 0;
    let offer_total = 0;
    tableSales.rows().every(function() {
        let data = this.data();
        console.log(data)
        if (data.discounted_price == '' || data.discounted_price == '0,00'){
            supplier_total += parseFloat(changePriceToDecimal(data.total_price));
        }else{
            supplier_total += parseFloat(changePriceToDecimal(data.discounted_price));
        }
        if (data.offer_price == '' || data.offer_price == '0,00'){
        }else{
            offer_total += parseFloat(changePriceToDecimal(data.offer_price));
        }

    });

    let profit = 100 * (offer_total - supplier_total) / supplier_total;

    $("#sales-detail tfoot tr").remove();
    let footer = '<tr>\n' +
        '             <th colspan="6" class="border-bottom-0">Tedarik Fiyatı: '+ changeDecimalToPrice(supplier_total) +'</th>\n' +
        '             <th colspan="6" class="border-bottom-0">Satış Fiyatı: '+ changeDecimalToPrice(offer_total) +'</th>\n' +
        '             <th colspan="6" class="border-bottom-0">Kar Oranı: '+ changeDecimalToPrice(profit) +'</th>\n' +
        '         </tr>';
    $("#sales-detail tfoot").append(footer);
}
