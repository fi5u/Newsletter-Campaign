(function ( $ ) {
	"use strict";

	$(function () {

        /*
         * Repeater field
         */

        $('#nc_repeater_btn_add').click(function(e) {
            var repeaterClone = $('.nc-repeater__item').clone();
            repeaterClone.find('input, textarea').val('');
            repeaterClone.appendTo('.nc-repeater__item');
            e.preventDefault();
        })

	});

}(jQuery));