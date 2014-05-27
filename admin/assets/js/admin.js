(function ( $ ) {
	"use strict";

	$(function () {

        /*
         * Repeater field
         */

        var inputTypes = [
            'input[type="text"]',
            'textarea'
            ];

        function repeaterIsEmpty() {
            var isEmpty = 0;
            $('.nc-repeater__item').find(inputTypes.join()).each(function() {
                if ($(this).val() === '') {
                    isEmpty = 1;
                }
            });
            if (isEmpty === 0) {
                return false;
            } else {
                return true;
            }
        }

        function repeaterToggleAddRow(makeDisabled) {
            if (makeDisabled) {
                $('#nc_repeater_btn_add').attr('disabled','true');
            } else {
                $('#nc_repeater_btn_add').removeAttr('disabled');
            }
        }

        if (repeaterIsEmpty() === false) {
            repeaterToggleAddRow(false);
        }

        $('.postbox-container').on('keyup', inputTypes.join(), function() {
            if (repeaterIsEmpty() === false) {
                repeaterToggleAddRow(false);
            } else {
                repeaterToggleAddRow(true);
            }
        });

        $('#nc_repeater_btn_add').click(function(e) {

            var repeaterClone = $('.nc-repeater__item').first().closest('.nc-repeater__droparea').clone();
            repeaterClone.find(inputTypes.join()).val('');
            repeaterClone.insertBefore('.nc-repeater__btn-row');
            $('.nc-repeater__droparea').first().clone().empty().insertBefore('.nc-repeater__btn-row');
            repeaterToggleAddRow(true);

            repeaterClone.droppable({
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
                }
            });

            repeaterClone.find('.nc-repeater__item').draggable({
                start: function(event, ui) {
                    $(this).data('originalOffset', ui.offset);
                },
                revert: 'invalid'
            });

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