(function ( $ ) {
	"use strict";

	$(function () {

        /*
         * Repeater field
         */

        $('#nc_repeater_btn_add').click(function(e) {
            var repeaterClone = $('.nc-repeater__item').first().clone();
            repeaterClone.find('input, textarea').val('');
            repeaterClone.insertBefore('.nc-repeater__btn-row');
            e.preventDefault();
        })

	});

}(jQuery));