(function($) {
    "use strict";

	$(document).ready(function() {
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

async function initOfferDetail(){
    let request_id = getPathVariable('sw-2');
    console.log(request_id)
    let data = await serviceGetOffersByRequestId(request_id);

    if (data.offer_status) {
        let offers = data.offers;
        console.log(offers)
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

                let item = '<tr id="productRow' + product.id + '">\n' +
                    '           <td>' + rowNo + '</td>\n' +
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
                    '           <td>' + checkNull(product.measurement_name) + '</td>\n' +
                    '           <td>' + changeCommasToDecimal(product.pcs_price) + '</td>\n' +
                    '           <td>' + changeCommasToDecimal(product.total_price) + '</td>\n' +
                    '           <td>' + checkNull(product.discount_rate) + '</td>\n' +
                    '           <td>' + changeCommasToDecimal(product.discounted_price) + '</td>\n' +
                    '           <td>' + checkNull(product.vat_rate) + '</td>\n' +
                    '           <td>' + checkNull(product.currency) + '</td>\n' +
                    '           <td>' + checkNull(product.request_quantity) + '</td>\n' +
                    '           <td>' + checkNull(product.quantity) + '</td>\n' +
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
    tableSales.rows().every(function() {
        let data = this.data();
        if (data[15] == ''){
            total += parseFloat(changePriceToDecimal(data[13]));
        }else{
            total += parseFloat(changePriceToDecimal(data[15]));
        }
    });

    $("#sales-detail tfoot tr").remove();
    let footer = '<tr>\n' +
        '             <th colspan="20" class="border-bottom-0">Tedarik Fiyatı: '+ changeDecimalToPrice(total) +'</th>\n' +
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
                "currency": this.data()[16],
                "request_quantity": this.data()[18],
                "offer_quantity": this.data()[19],
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
        if (data.status == "success") {
            window.location.href = "/sw-3/" + data.object.sale_id;
        }else{
            alert("Hata Oluştu");
        }

    }
}
