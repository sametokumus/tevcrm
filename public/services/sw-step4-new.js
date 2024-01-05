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
    let sale_id = getPathVariable('sw-4');
    if (sale_id == undefined){
        sale_id = getPathVariable('sw-4-rev');
        revize = 1;
    }
    let data = await serviceGetSaleConfirmationById(sale_id);
    let sale = data.sale;
    console.log(sale)
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
                '                            <th>Tedarik Fiyatı</th>\n' +
                '                            <th>Satış Para Birimi Cinsinden</th>\n' +
                '                            <th>Teslimat Süresi</th>\n' +
                '                        </tr>\n' +
                '                        </thead>\n' +
                '                        <tbody>\n';
            $.each(offer.supplier_offers, function (i, s_offer) {
                let row_bg = '';
                if (s_offer.is_sended_offer == 1){
                    row_bg = 'bg-theme-200';
                }
                item += '                <tr class="'+ row_bg +'">\n' +
                '                            <td>'+ s_offer.supplier.name +'</td>\n' +
                '                            <td>'+ s_offer.quantity +'</td>\n' +
                '                            <td>'+ changeCommasToDecimal(s_offer.pcs_price) +' '+ s_offer.currency +'</td>\n' +
                '                            <td>'+ changeCommasToDecimal(s_offer.total_price) +' '+ s_offer.currency +'</td>\n' +
                '                            <td>'+ changeCommasToDecimal(s_offer.converted_price) +' '+ s_offer.converted_currency +'</td>\n' +
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
    let sale_id = getPathVariable('sw-4');
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
    let sale_id = getPathVariable('sw-4');
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
