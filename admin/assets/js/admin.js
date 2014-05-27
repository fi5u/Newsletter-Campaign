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
        });

        $('.nc-repeater__item').draggable({
            start: function(event, ui) {
                $(this).data('originalOffset', ui.offset);
            },
            revert: 'invalid'
        });

        var dragAreaOffset = $('.nc-repeater').offset();
        var dropMarginTop = parseInt($('.nc-repeater__droparea').css('marginTop'));
        var dropMarginLeft = parseInt($('.nc-repeater__droparea').css('marginLeft'));

        $('.nc-repeater__droparea').droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ".nc-repeater__item",
            drop: function( event, ui ) {
                ui.draggable.detach().appendTo($(this));
                var originalOffset = ui.draggable.data('originalOffset');
                console.log('originalOffset', originalOffset.top, originalOffset.left);

                var repeaterItem = $(this).children('.nc-repeater__item');
                var boxPosition = repeaterItem.position();
                console.log('repeaterItem position', boxPosition.top, boxPosition.left);

                var container = $(this);
                var containerPosition = container.position();
                console.log(container, containerPosition.top, containerPosition.left);

                var newTop = originalOffset.top + boxPosition.top - containerPosition.top - dragAreaOffset.top - dropMarginTop;
                var newLeft = originalOffset.left + boxPosition.left - containerPosition.left - dragAreaOffset.left - dropMarginLeft;


                console.log('new offset', newTop, newLeft);
                repeaterItem.css({top:newTop,left:newLeft}).animate({top:0,left:0});
                /*$( this ).find( ".placeholder" ).remove();
                $( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );*/
            }
        }).sortable({
            items: '.nc-repeater__item:not(.placeholder)',
            sort: function() {
                // gets added unintentionally by droppable interacting with sortable
                // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
                $( this ).removeClass( "ui-state-default" );
            }
        });

	});

}(jQuery));