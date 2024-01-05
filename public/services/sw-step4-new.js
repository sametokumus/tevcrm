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
let revize = 0;
let table;

async function initOfferDetail(){
    let sale_id = getPathVariable('sw-4-new');
    if (sale_id == undefined){
        sale_id = getPathVariable('sw-4-rev');
        revize = 1;
    }
    let data = await serviceGetSaleConfirmationById(sale_id);
    let sale = data.sale;
    console.log(sale)
    $('#sw_customer_name').text(sale.request.company.name);
    initCustomer(sale.request.company, sale.request.company_employee);

    $('#offer-price').html('<b>Satış Fiyatı: ' + changeCommasToDecimal(sale.offer_price) + ' ' + sale.currency +'</b>');
    $('#supply-price').html('<b>Tedarik Fiyatı: ' + changeCommasToDecimal(sale.supply_price) + ' ' + sale.currency +'</b>');
    $('#profit-price').html('<b>Kar: ' + changeCommasToDecimal(sale.profit) + ' ' + sale.currency +'</b>');
    $('#profit-rate').html('<b>Kar Oranı: %' + sale.profit_rate +'</b>');

    if (sale.status_id >= 4) {
        let offers = sale.sale_offers;
        console.log(offers)

        $.each(offers, function (i, offer) {
            console.log(offer)
            let info = 'Ürün: '+ offer.product_name +'   |   Birim Fiyat: '+ offer.offer_pcs_price +' '+ offer.sale_currency +'   |   Satış Fiyatı: '+ offer.offer_price +' '+ offer.sale_currency +'   |   Kar: '+ offer.profit +' '+ offer.sale_currency +'   |   Kar Oranı: '+ offer.profit_rate +'   |   Teslimat Süresi: '+ offer.offer_lead_time +'';
            let item = '';
            item += '<div class="card mb-3">\n' +
                '        <div class="list-group list-group-flush">\n' +
                '            <div class="list-group-item fw-bold px-3 d-flex bg-theme text-white">\n' +
                '                <div class="flex-fill">\n' +
                '                    '+ info +'\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="list-group-item px-3">\n' +
                '                <div class="table-responsive">\n' +
                '                    <table id="offer-products-table-'+ (i+1) +'" class="table table-striped table-borderless mb-2px small text-nowrap">\n' +
                '                        <thead>\n' +
                '                        <tr>\n' +
                '                            <th>Tedarikçi</th>\n' +
                '                            <th>Miktar</th>\n' +
                '                            <th>Birim Fiyat</th>\n' +
                '                            <th>Toplam Fiyat</th>\n' +
                '                            <th>Satış Para Birimi Cinsinden</th>\n' +
                '                            <th>Teslimat Süresi</th>\n' +
                '                        </tr>\n' +
                '                        </thead>\n' +
                '                        <tbody>\n';
            $.each(offer.supplier_offers, function (i, s_offer) {
                let row_bg = '';
                if (s_offer.is_sended_offer == 1){
                    row_bg = 'bg-theme-300';
                }
                item += '                <tr class="'+ row_bg +'">\n' +
                '                            <td>'+ s_offer.supplier.name +'</td>\n' +
                '                            <td>'+ s_offer.quantity +'</td>\n' +
                '                            <td>'+ s_offer.pcs_price +' '+ s_offer.currency +'</td>\n' +
                '                            <td>'+ s_offer.total_price +' '+ s_offer.currency +'</td>\n' +
                '                            <td>'+ s_offer.converted_price +' '+ s_offer.converted_currency +'</td>\n' +
                '                            <td>'+ s_offer.lead_time +'</td>\n' +
                '                        </tr>\n';
            });
                item += '                </tbody>\n' +
                '                    </table>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '        <div class="card-arrow">\n' +
                '            <div class="card-arrow-top-left"></div>\n' +
                '            <div class="card-arrow-top-right"></div>\n' +
                '            <div class="card-arrow-bottom-left"></div>\n' +
                '            <div class="card-arrow-bottom-right"></div>\n' +
                '        </div>\n' +
                '    </div>';

                $('#offer-products').append(item);
        });


        $("#sales-detail").dataTable().fnDestroy();
        $('#sales-detail tbody > tr').remove();

        table = $('#sales-detail').DataTable( {
            dom: "Bfrtip",
            footer: true,
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
                { data: "profit_rate", className:  "row-edit" },
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
                let supplier_total = 0;
                let offer_total = 0;
                let sale_currency;
                $.each(data, function (i, offer) {
                    // if (offer.discounted_price == null || offer.discounted_price == "0,00"){
                    //     supplier_total += parseFloat(changePriceToDecimal(offer.total_price.toString()));
                    // }else{
                    //     supplier_total += parseFloat(changePriceToDecimal(offer.discounted_price.toString()));
                    // }
                    supplier_total += parseFloat(offer.sale_price);

                    if (offer.offer_price == '' || offer.offer_price == '0,00'){
                    }else{
                        offer_total += parseFloat(changePriceToDecimal(offer.offer_price));
                    }
                    sale_currency = offer.sale_currency;
                });

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

                $(api.column(0).footer()).html(footer_text);

            }
        } );


    }else{
        alert('Bu sipariş teklif oluşturmaya hazır değildir.');
    }
}
async function initCustomer(company, employee){
    $('#customer-name').text(company.name);
    $('#employee-name').html('<i class="fa fa-user fa-fw text-inverse text-opacity-50"></i>' + employee.name);
    $('#employee-email').html('<i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i>' + employee.email);
    $('#employee-phone').html('<i class="fa fa-phone fa-fw text-inverse text-opacity-50"></i>' + employee.phone);

    let logo = '/img/company/empty.jpg';
    if (company.logo != null && company.logo != ''){
        logo = company.logo;
    }
    $('#customer-logo').attr('src', logo);
}

async function approveOffer() {
    let sale_id = getPathVariable('sw-4-new');
    if (sale_id == undefined){
        sale_id = getPathVariable('sw-4-rev');
        revize = 1;
    }
    let returned = await serviceGetApproveOfferBySaleId(sale_id, revize);
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
    let sale_id = getPathVariable('sw-4-new');
    if (sale_id == undefined){
        sale_id = getPathVariable('sw-4-rev');
        revize = 1;
    }
    let user_id = localStorage.getItem('userId');
    let returned = await serviceGetRejectOfferBySaleId(sale_id, revize);
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
