

$(document).ready(function () {

    jQuery.fn.extend({
        live: function (event, callback) {
            if (this.selector) {
                jQuery(document).on(event, this.selector, callback);
            }
            return this;
        }
    });

});

$(document).ready(function () {

    $(document.body).on('change', '#county', function () {
        let $url = "/js/index.php";
		let $county = $("#county").val();

        $.ajax({
            url: $url,
            type: 'get',
			data: { county: $county},
            success: function ($data) {
                if ($data) {
                    $('.country').html($data);
                }
            },
            error: function ($data) {
                console.log($data);
            }
        });
        return false;
    });

    $(document.body).on('change', '#country', function () {
        let $url = "/js/index.php";
        let $country = $("#country").val();

        $.ajax({
            url: $url,
            type: 'get',
            data: { country: $country},
            success: function ($data) {
                if ($data) {
                    $('.town').html($data);
                }
            },
            error: function ($data) {
                console.log($data);
            }
        });
        return false;
    });

    $(document.body).on('change', '#country', function () {
        let $url = "/js/index.php";
        let $country = $("#country").val();

        $.ajax({
            url: $url,
            type: 'get',
            data: { country: $country},
            success: function ($data) {
                if ($data) {
                    $('.town').html($data);
                }
            },
            error: function ($data) {
                console.log($data);
            }
        });
        return false;
    });



});


