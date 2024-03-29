(function($) {
    "use strict";

    $(":input").inputmask();
    $("#update_quote_freight").maskMoney({thousands:'.', decimal:','});
    $("#update_quote_advance_price").maskMoney({thousands:'.', decimal:','});
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

let sale_price = 0;

function checkRole(){
	return true;
}

async function changeStatus(){
    let sale_id = getPathVariable('sw-3-rev');
    fastChangeStatus(11, sale_id);
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
    let sale_id = getPathVariable('sw-3-rev');
    console.log(sale_id)
    let data = await serviceGetSaleById(sale_id);
    let sale = data.sale;
    console.log(sale)
    $('#sw_customer_name').text(sale.request.company.name);

    if (sale.status_id >= 4) {
        let offers = sale.sale_offers;
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
                type: "readonly",
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

                // Submit the edited row data
                editor.submit();
            }
        });
        editor.on('submitSuccess', async function() {
            // Reload the DataTable
            await initSaleTableFooter();
        });
        console.log(offers)
        table = $('#sales-detail').DataTable( {
            dom: "Bfrtip",
            data: offers,
            columns: [{
                title: 'N#',
                data: "sequence"
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
                { data: "measurement_name_tr" },
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
                { data: "sale_price",
                    render: function (data, type, row) {
                        if (type === 'display' && data === '0,00') {
                            return '';
                        }
                        return changeCommasToDecimal(data);
                    }  },
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
                { data: "offer_currency", className:  "row-edit", editable: false,
                    render: function (data, type, row) {
                        return sale.currency;
                    } },
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
            console.log(row)
            let id = row.id;
            console.log(id)
            let profit_rate = document.getElementById('add_batch_offer_profit_rate').value;
            // let offer_currency = document.getElementById('add_batch_offer_currency').value;
            // let offer_currency_change = document.getElementById('add_batch_offer_currency_change').value;
            let offer_lead_time = document.getElementById('add_batch_offer_lead_time').value;
            // let total_price = row.total_price;
            // let discounted_price = row.discounted_price;

            let price = row.sale_price;
            // if (discounted_price != '' && discounted_price != null && discounted_price != '0,00'){
            //     price = discounted_price;
            // }

            // let offer_price = parseFloat(changePriceToDecimal(price)) * parseFloat(changePriceToDecimal(offer_currency_change));
            let offer_price = parseFloat(price);
            offer_price = offer_price + (offer_price / 100 * profit_rate);

            let formData = JSON.stringify({
                "id": id,
                "user_id": user_id,
                "offer_price": offer_price,
                "offer_currency": row.sale_currency,
                "offer_lead_time": offer_lead_time
            });
            console.log(formData)
            let returned = await servicePostAddSaleOfferPrice(formData);

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
    let sale_id = getPathVariable('sw-3-rev');
    let data = await serviceGetQuoteBySaleId(sale_id);
    let quote = data.quote;

    document.getElementById('update_quote_id').value = quote.id;
    document.getElementById('update_quote_payment_term').value = checkNull(quote.payment_term);
    document.getElementById('update_quote_advance_price').value = changeCommasToDecimal(quote.advance_price);
    document.getElementById('update_quote_lead_time').value = checkNull(quote.lead_time);
    document.getElementById('update_quote_delivery_term').value = checkNull(quote.delivery_term);
    document.getElementById('update_quote_country_of_destination').value = checkNull(quote.country_of_destination);
    document.getElementById('update_quote_freight').value = changeCommasToDecimal(quote.freight);
    $('#update_quote_note').summernote('code', checkNull(quote.note));
}
async function updateQuote(){
    let sale_id = getPathVariable('sw-3-rev');
    let quote_id = document.getElementById('update_quote_id').value;
    let payment_term = document.getElementById('update_quote_payment_term').value;
    let advance_price = document.getElementById('update_quote_advance_price').value;
    let lead_time = document.getElementById('update_quote_lead_time').value;
    let delivery_term = document.getElementById('update_quote_delivery_term').value;
    let country_of_destination = document.getElementById('update_quote_country_of_destination').value;
    let freight = document.getElementById('update_quote_freight').value;
    let expiry_date = document.getElementById('update_quote_expiry_date').value;
    let note = document.getElementById('update_quote_note').value;

    let formData = JSON.stringify({
        "sale_id": sale_id,
        "quote_id": quote_id,
        "payment_term": payment_term,
        "advance_price": changePriceToDecimal(advance_price),
        "lead_time": lead_time,
        "delivery_term": delivery_term,
        "country_of_destination": country_of_destination,
        "freight": changePriceToDecimal(freight),
        "expiry_date": formatDateDESC2(expiry_date, "-", "-"),
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
    let sale_currency;
    tableSales.rows().every(function() {
        let data = this.data();
        // if (data.discounted_price == '' || data.discounted_price == '0,00'){
        //     supplier_total += parseFloat(changePriceToDecimal(data.total_price));
        // }else{
        //     supplier_total += parseFloat(changePriceToDecimal(data.discounted_price));
        // }
        supplier_total += parseFloat(data.sale_price);
        if (data.offer_price == '' || data.offer_price == '0,00'){
        }else{
            offer_total += parseFloat(changePriceToDecimal(data.offer_price));
        }
        sale_currency = data.sale_currency;

    });

    sale_price = offer_total;

    let profit = offer_total - supplier_total;
    let profit_rate = 100 * (offer_total - supplier_total) / supplier_total;
    let footer_text = '';
    footer_text += 'Tedarik Fiyatı: '+ changeDecimalToPrice(supplier_total) + ' ' + sale_currency;
    footer_text += '&nbsp;&nbsp; | &nbsp;&nbsp;Satış Fiyatı: '+ changeDecimalToPrice(offer_total) + ' ' + sale_currency;

    if (profit <= 0) {
        footer_text += '&nbsp;&nbsp; | &nbsp;&nbsp;Kar: -';
    }else{
        footer_text += '&nbsp;&nbsp; | &nbsp;&nbsp;Kar: ' + changeDecimalToPrice(profit) + ' ' + sale_currency;
    }
    if (profit_rate <= 0) {
        footer_text += '&nbsp;&nbsp; | &nbsp;&nbsp;Kar Oranı: -';
    }else{
        footer_text += '&nbsp;&nbsp; | &nbsp;&nbsp;Kar Oranı: %' + changeDecimalToPrice(profit_rate);
    }
    $("#sales-detail tfoot tr").remove();
    $(".dataTables_scrollFootInner tfoot tr").remove();
    let footer = '<tr>\n' +
        '             <th colspan="20" class="border-bottom-0">'+ footer_text +'</th>\n' +
        '         </tr>';
    $("#sales-detail tfoot").append(footer);
}



async function checkAdvancePrice(){
    let payment_term_id = document.getElementById('update_quote_payment_term').value;
    let data = await serviceGetPaymentTermById(payment_term_id);
    let term = data.payment_term;

    console.log(sale_price)
    if (term.advance != null && term.advance != 0){
        if (sale_price != 0) {
            document.getElementById('update_quote_advance_price').value = changeCommasToDecimal(parseFloat(sale_price / 100 * term.advance).toFixed(2));
        }
    }else{
        document.getElementById('update_quote_advance_price').value = '0,00';
    }


}
