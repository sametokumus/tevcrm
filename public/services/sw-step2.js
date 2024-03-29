(function($) {
    "use strict";

	$(document).ready(function() {
        $(":input").inputmask();
        $("#update_currency_rate_usd").maskMoney({thousands:'.', decimal:','});
        $("#update_currency_rate_eur").maskMoney({thousands:'.', decimal:','});
        $("#update_currency_rate_gbp").maskMoney({thousands:'.', decimal:','});

        $('#update_currency_rate_form').submit(function (e){
            e.preventDefault();
            updateCurrencyRate();
        });
	});

	$(window).load(async function() {

		checkLogin();
		checkRole();
        await checkCurrencyLog();
        await initRequestDetail();
        await initSaleDetail();

	});

})(window.jQuery);

function checkRole(){
	return true;
}

async function initRequestDetail(){
    let request_id = getPathVariable('sw-2');
    let data = await serviceGetOfferRequestById(request_id);
    $('#sw_customer_name').text(data.offer_request.company.name);
}
async function initSaleDetail(){
    let request_id = getPathVariable('sw-2');
    let data = await serviceGetSaleByRequestId(request_id);
    let sale = data.sale;

    if (sale.currency != null){
        document.getElementById('update_currency_rate_currency').value = sale.currency;
    }
    if (sale.usd_rate != null){
        document.getElementById('update_currency_rate_usd').value = changeCommasToDecimal(sale.usd_rate);
    }
    if (sale.eur_rate != null){
        document.getElementById('update_currency_rate_eur').value = changeCommasToDecimal(sale.eur_rate);
    }
    if (sale.gbp_rate != null){
        document.getElementById('update_currency_rate_gbp').value = changeCommasToDecimal(sale.gbp_rate);
    }
}

async function checkCurrencyLog(){
    let request_id = getPathVariable('sw-2');
    let data = await serviceGetCheckSaleCurrencyLog(request_id);
    if (data.has_currency == true){
        await initOfferDetail();
    }else{
        await initEmptyTables();
    }
}

async function initEmptyTables(){
    $('#offer-detail').DataTable({
        responsive: false,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1}
        ],
        dom: 'Bfrtip',
        buttons: [],
        paging: false,
        scrollX: true,
        language: {
            url: "services/Turkish.json"
        },
        order: [[0, 'asc']]
    });
    $('#sales-detail').DataTable({
        responsive: false,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1}
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Teklif Oluştur',
                action: function (e, dt, node, config) {
                    addSale();
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

async function initOfferDetail(){

    let request_id = getPathVariable('sw-2');
    let data = await serviceGetOffersByRequestId(request_id);
    console.log(data)
    if (data.offer_status) {
        let offers = data.offers;
        console.log(offers)
        $("#sales-detail").dataTable().fnDestroy();
        $("#offer-detail").dataTable().fnDestroy();
        $('#offer-detail tbody > tr').remove();
        let rowNo = 0;
        $.each(offers, function (i, offer) {
            $.each(offer.products, function (j, product) {
                rowNo ++;
                let cheap_fast = '';
                if (product.cheapest){
                    cheap_fast += '<span class="badge border border-yellow text-yellow px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">En Ucuz</span>';
                }
                if (product.fastest){
                    cheap_fast += '<span class="badge border border-lime text-lime px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center">En Hızlı</span>';
                }

                let measurement_name = '';
                if (Lang.getLocale() == 'tr'){
                    measurement_name = product.measurement_name_tr;
                }else{
                    measurement_name = product.measurement_name_tr;
                }
                let item = '<tr id="productRow' + product.id + '">\n' +
                    '           <td>' + product.sequence + '</td>\n' +
                    '           <td>' + product.id + '</td>\n' +
                    '              <td>\n' +
                    '                  <div class="btn-list">\n' +
                    '                      <button type="button" onclick="addSaleTableProduct(this);" class="btn btn-sm btn-theme"><span class="fe fe-edit"> Teklife Ekle</span></button>\n' +
                    '                  </div>\n' +
                    '              </td>\n' +
                    '           <td>'+ cheap_fast +'</td>\n' +
                    '           <td class="d-none">' + offer.offer_id + '</td>\n' +
                    '           <td class="d-none">' + product.product_detail.id + '</td>\n' +
                    '           <td class="d-none">' + offer.supplier_id + '</td>\n' +
                    '           <td>' + offer.company_name + '</td>\n' +
                    '           <td>' + checkNull(product.product_detail.product_name) + '</td>\n' +
                    '           <td>' + checkNull(product.product_detail.ref_code) + '</td>\n' +
                    '           <td>' + checkNull(product.lead_time) + '</td>\n' +
                    '           <td>' + checkNull(measurement_name) + '</td>\n' +
                    '           <td>' + changeCommasToDecimal(product.pcs_price) + '</td>\n' +
                    '           <td>' + changeCommasToDecimal(product.total_price) + '</td>\n' +
                    '           <td>' + checkNull(product.discount_rate) + '</td>\n' +
                    '           <td>' + changeCommasToDecimal(product.discounted_price) + '</td>\n' +
                    '           <td>' + checkNull(product.vat_rate) + '</td>\n' +
                    '           <td>' + checkNull(product.currency) + '</td>\n' +
                    '           <td>' + checkNull(product.request_quantity) + '</td>\n' +
                    '           <td>' + checkNull(product.quantity) + '</td>\n' +
                    '           <td class="d-none">' + changeCommasToDecimal(product.converted_price) + '</td>\n' +
                    '           <td class="d-none">' + product.converted_currency + '</td>\n' +
                    '       </tr>';
                $('#offer-detail tbody').append(item);
            });
        });

        $('#offer-detail').DataTable({
            responsive: false,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1}
            ],
            dom: 'Bfrtip',
            buttons: [],
            paging: false,
            scrollX: true,
            language: {
                url: "services/Turkish.json"
            },
            order: [[0, 'asc']]
        });
        $('#sales-detail').DataTable({
            responsive: false,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -1}
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Teklif Oluştur',
                    action: function (e, dt, node, config) {
                        addSale();
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
    }else{
        if(data.status_id < 3){
            alert('Bu sipariş teklif oluşturmaya hazır değildir.');
        }else{
            alert('Bu sipariş için daha önce bir teklif oluşturulmuştur.');
        }
    }
}



async function addSaleTableProduct(el){
    let tableSales = $("#sales-detail").DataTable();
    let tableOffer = $("#offer-detail").DataTable();
    $(el).attr('onclick','addOfferTableProduct(this);');
    $(el).text('Tekliften Çıkar');
    let row = tableOffer.row( $(el).parents('tr') );
    let rowNode = row.node();
    row.remove();

    tableSales
        .row.add( rowNode )
        .draw();
    await initSaleTableFooter();
}

async function initSaleTableFooter(){
    let tableSales = $("#sales-detail").DataTable();
    let total = 0;
    let currency = '';
    tableSales.rows().every(function() {
        let data = this.data();
        // if (data[15] == ''){
        //     total += parseFloat(changePriceToDecimal(data[13]));
        // }else{
        //     total += parseFloat(changePriceToDecimal(data[15]));
        // }
        total += parseFloat(changePriceToDecimal(data[20]));
        currency = data[21];
    });

    $("#sales-detail tfoot tr").remove();
    let footer = '<tr>\n' +
        '             <th colspan="22" class="border-bottom-0">Satış Para Birimi Cinsinden Tedarik Fiyatı: '+ changeDecimalToPrice(total) +' '+ currency +'</th>\n' +
        '         </tr>';
    $("#sales-detail tfoot").append(footer);
}

async function addOfferTableProduct(el){
    let tableSales = $("#sales-detail").DataTable();
    let tableOffer = $("#offer-detail").DataTable();
    $(el).attr('onclick','addSaleTableProduct(this);');
    $(el).text('Teklife Ekle');
    let row = tableSales.row( $(el).parents('tr') );
    let rowNode = row.node();
    row.remove();

    tableOffer
        .row.add( rowNode )
        .draw();
    await initSaleTableFooter();
}

async function addSale(){
    let user_id = localStorage.getItem('userId');
    let request_id = getPathVariable('sw-2');
    let table = $('#sales-detail').DataTable();
    let rows = table.rows();

    let offers = [];
    if (rows.count() === 0){
        alert("Öncelikle seçim yapmalısınız.");
    }else {
        rows.every(function (rowIdx, tableLoop, rowLoop) {
            let item = {
                "offer_product_id": this.data()[1],
                "offer_id": this.data()[4],
                "product_id": this.data()[5],
                "supplier_id": this.data()[6],
                "lead_time": this.data()[10],
                "measurement": this.data()[11],
                "pcs_price": changePriceToDecimal(this.data()[12]),
                "total_price": changePriceToDecimal(this.data()[13]),
                "discount_rate": this.data()[14],
                "discounted_price": changePriceToDecimal(this.data()[15]),
                "vat_rate": this.data()[16],
                "currency": this.data()[17],
                "request_quantity": this.data()[18],
                "offer_quantity": this.data()[19],
                "converted_price": changePriceToDecimal(this.data()[20]),
                "converted_currency": this.data()[21],
            }
            offers.push(item);
        });
        console.log(offers)

        let formData = JSON.stringify({
            "user_id": parseInt(user_id),
            "request_id": request_id,
            "offers": offers
        });

        console.log(formData);

        let data = await servicePostAddSale(formData);
        console.log(data)
        if (data.status == "success") {
            window.location.href = "/sw-3/" + data.object.sale_id;
        }else{
            alert("Hata Oluştu");
        }

    }
}

async function getCurrencyLog(){
    let data = await serviceGetLastCurrencyLog();
    let log = data.currency_log;
    document.getElementById('update_currency_rate_usd').value = changeCommasToDecimal(log.usd);
    document.getElementById('update_currency_rate_eur').value = changeCommasToDecimal(log.eur);
    document.getElementById('update_currency_rate_gbp').value = changeCommasToDecimal(log.gbp);
}
async function updateCurrencyRate() {
    let request_id = getPathVariable('sw-2');
    console.log(request_id)

    let formData = JSON.stringify({
        "currency": document.getElementById('update_currency_rate_currency').value,
        "usd_rate": changePriceToDecimal(document.getElementById('update_currency_rate_usd').value),
        "eur_rate": changePriceToDecimal(document.getElementById('update_currency_rate_eur').value),
        "gbp_rate": changePriceToDecimal(document.getElementById('update_currency_rate_gbp').value)
    });
    console.log(formData)

    let returned = await servicePostAddSaleCurrencyLog(formData, request_id);
    if (returned){
        await checkCurrencyLog();
    }else{
        alert("Kur Eklerken Hata Oluştu")
    }
}
