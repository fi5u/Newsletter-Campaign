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

        $( "#catalog li" ).draggable({
            appendTo: "body",
            helper: "clone"
        });
        $( "#cart ol" ).droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ":not(.ui-sortable-helper)",
            drop: function( event, ui ) {
                $( this ).find( ".placeholder" ).remove();
                $( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );
            }
        }).sortable({
            items: "li:not(.placeholder)",
            sort: function() {
                // gets added unintentionally by droppable interacting with sortable
                // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
                $( this ).removeClass( "ui-state-default" );
            }
        });

	});

}(jQuery));