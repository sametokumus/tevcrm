'use strict'
$(window).on('load', function () {


    /* table data master */
    $('.footable').footable({
        "paging": {
            "enabled": true,
            "container": '#footable-pagination',
            "countFormat": "{CP} of {TP}",
            "limit": 3,
            "position": "right",
            "size": 5
        },
        "sorting": {
            "enabled": true
        },
    }, function (ft) {
        $('#footablestot').html($('.footable-pagination-wrapper .label').html())

        $('.footable-pagination-wrapper ul.pagination li').on('click', function () {
            setTimeout(function () {
                $('#footablestot').html($('.footable-pagination-wrapper .label').html());
            }, 200);
        });

    });

});
