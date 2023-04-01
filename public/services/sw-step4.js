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

let table;

async function initOfferDetail(){
    let sale_id = getPathVariable('sw-4');
    console.log(sale_id)
    let data = await serviceGetSaleById(sale_id);
    let sale = data.sale;

    if (sale.status_id >= 4) {
        let offers = sale.sale_offers;
        console.log(offers)
        $("#sales-detail").dataTable().fnDestroy();
        $('#sales-detail tbody > tr').remove();

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
            ],
            select: false,
            scrollX: true,
            buttons: [
                {
                    text: 'Teklifi Onayla',
                    action: function ( e, dt, node, config ) {
                        approveOffer();
                    }
                },
                // 'selectAll',
                // 'selectNone',
                // 'excel',
                // 'pdf'
            ],
            language: {
                url: "services/Turkish.json"
            }
        } );


    }else{
        alert('Bu sipariş teklif oluşturmaya hazır değildir.');
    }
}

async function approveOffer() {
    let sale_id = getPathVariable('sw-4');
    console.log(sale_id)
    let returned = await serviceGetApproveOfferBySaleId(sale_id);
    if (returned){
        window.location.href = "/sales";
    }else{
        alert("Hata Oluştu")
    }
}
