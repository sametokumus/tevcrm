(function($) {
    "use strict";

    $(":input").inputmask();
    $("#add_offer_price_price").maskMoney({thousands:'.', decimal:','});
    $("#update_offer_price_price").maskMoney({thousands:'.', decimal:','});
    $("#add_batch_offer_currency_change").maskMoney({thousands:'.', decimal:','});

	$(document).ready(function() {

        $('#add_reject_offer_note_form').submit(function (e){
            e.preventDefault();
            rejectOffer();
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
            footer: true,
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
            paging: false,
            buttons: [
                {
                    text: 'Teklifi Onayla',
                    className: 'btn btn-theme',
                    action: function ( e, dt, node, config ) {
                        approveOffer();
                    }
                },{
                    text: 'Teklifi Reddet',
                    className: 'btn btn-danger',
                    action: function ( e, dt, node, config ) {
                        openRejectOfferModal();
                    }
                }
            ],
            language: {
                url: "services/Turkish.json"
            },
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();
                console.log(data)
                let supplier_total = 0;
                let offer_total = 0;
                $.each(data, function (i, offer) {
                    console.log(offer)
                    if (offer.discounted_price == null || offer.discounted_price == "0,00"){
                        supplier_total += parseFloat(changePriceToDecimal(offer.total_price.toString()));
                    }else{
                        supplier_total += parseFloat(changePriceToDecimal(offer.discounted_price.toString()));
                    }

                    offer_total += parseFloat(changePriceToDecimal(offer.offer_price.toString()));
                });
                let profit = 100 * (offer_total - supplier_total) / supplier_total;

                $(api.column(0).footer()).html('Tedarikçi Fiyatı: ' + changeDecimalToPrice(supplier_total));
                $(api.column(11).footer()).html('Teklif Fiyatı: ' + changeDecimalToPrice(offer_total));
                $(api.column(17).footer()).html('Kar Oranı: ' + changeDecimalToPrice(profit));

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

async function openRejectOfferModal() {
    $("#addRejectOfferNoteModal").modal('show');
}

async function rejectOffer() {
    let sale_id = getPathVariable('sw-4');
    let user_id = localStorage.getItem('userId');
    console.log(sale_id)
    let returned = await serviceGetRejectOfferBySaleId(sale_id);
    if (returned){

        let note = document.getElementById('add_sale_note_description').value;

        let formData = JSON.stringify({
            "sale_id": sale_id,
            "user_id": user_id,
            "note": note
        });


        let returned2 = await servicePostAddSaleNote(formData);
        if (returned2){

            window.location.href = "/sales";
        }else{
            alert("Not Eklerken Hata Oluştu")
        }

    }else{
        alert("Hata Oluştu")
    }
}
