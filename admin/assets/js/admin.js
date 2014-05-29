(function ( $ ) {
	"use strict";

	$(function () {

        /*
         * REPEATER FIELD
         *
         * Manages the dragging and dropping of repeater field elements
         */


        /* SCOPED VARIABLES */

        var inputTypes = [
            'input[type="text"]',
            'textarea'
            ],

            dragAreaOffset = $('.nc-repeater').offset(),
            dropMarginTop = parseInt($('.nc-repeater__droparea').css('marginTop')),
            dropMarginLeft = parseInt($('.nc-repeater__droparea').css('marginLeft')),

            draggableAttr = {
                start: function(event, ui) {
                    $(this).data('originalOffset', ui.offset);
                },
                revert: 'invalid'
            },

            droppableAttr = {
                activeClass: 'ui-state-default',
                hoverClass: 'ui-state-hover',
                accept: '.nc-repeater__item',
                drop: function( event, ui ) {
                    repeaterDrop($(this), event, ui);
                    removeExcessDropAreas();
                }
            },

            sortableAttr = {
                items: '.nc-repeater__item:not(.placeholder)',
                sort: function() {
                    // gets added unintentionally by droppable interacting with sortable
                    // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
                    $( this ).removeClass('ui-state-default');
                }
            };

        /* FUNCTIONS */

        /*
         * Detect if any repeater block has any empty fields
         * Return   TRUE if empty
         *          FALSE if not empty
         */

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


        /*
         * Toggle the add row button from disabled to normal
         * Param    TRUE if the button should be disabled
         *          FALSE if the button should be normal
         */

        function repeaterToggleAddRow(makeDisabled) {

            if (makeDisabled) {
                $('#nc_repeater_btn_add').attr('disabled','true');
            } else {
                $('#nc_repeater_btn_add').removeAttr('disabled');
            }

        }


        /*
         * Set the new position of the dropped item after dropping
         * Param    jQuery object of the dropped item
         *          event
         *          ui
         */

        function repeaterDrop($this, event, ui) {

            ui.draggable.detach().appendTo($this);
            var originalOffset = ui.draggable.data('originalOffset');

            var repeaterItem = $this.children('.nc-repeater__item');
            var boxPosition = repeaterItem.position();

            var container = $this;
            var containerPosition = container.position();

            var newTop = originalOffset.top + boxPosition.top - containerPosition.top - dragAreaOffset.top - dropMarginTop;
            var newLeft = originalOffset.left + boxPosition.left - containerPosition.left - dragAreaOffset.left - dropMarginLeft;

            repeaterItem.css({top:newTop,left:newLeft}).animate({top:0,left:0});

        }


        /*
         * Create a clone of a drop area and place it in the desired position
         * Param    jQuery object of the repeater item to clone from (not the droparea)
         *          where the clone should be placed: 'before' or 'after'
         */

        function cloneDropArea($cloneFrom, place) {

            var repeaterClone = $cloneFrom.closest('.nc-repeater__droparea').clone().empty();
            if (place === 'after') {
                $cloneFrom.closest('.nc-repeater__droparea').after(repeaterClone);
            } else {
                $cloneFrom.closest('.nc-repeater__droparea').before(repeaterClone);
            }

            // Bind it to droppable
            repeaterClone.droppable(droppableAttr).sortable(sortableAttr);;

            // Bind it to draggable
            repeaterClone.find('.nc-repeater__item').draggable(draggableAttr);

        }


        /*
         * Ensure that there is an empty drop area between each repeater item
         */

        function addDropAreas() {

            var repeaterQty = $('.nc-repeater__item').length;

            // Iterate through each repeater item
            $('.nc-repeater__item').each(function(i) {

                // If the first element has not got an empty drop area above, add one
                if (i === 0 && !$(this).closest('.nc-repeater__droparea').prev('.nc-repeater__droparea').length) {
                    cloneDropArea($(this), 'before');
                }

                // If there are two full drop areas next to each other, put an empty one between
                if ($(this).closest('.nc-repeater__droparea').next('.nc-repeater__droparea').has('.nc-repeater__item').length) {
                    cloneDropArea($(this), 'after');
                }

                // If the last repeater does not have an empty drop area after it, add one
                if (i === repeaterQty-1 && !$(this).closest('.nc-repeater__droparea').next('.nc-repeater__droparea').length) {
                    cloneDropArea($(this), 'after');
                }
            });

        }


        /*
         * If there are two or more empty drop areas together, remove the excess
         */

        function removeExcessDropAreas() {

            $('.nc-repeater__droparea').each(function() {

                // If there are two empty dropareas together, remove the second one
                if ($(this).is(':empty') && $(this).next('.nc-repeater__droparea').is(':empty')) {
                    $(this).remove();
                }
            });

            // Proceed to add in extra drop areas
            addDropAreas();

        }


        /*
         * Add a new repeater item and bind its drag and drop functions
         */

        function addRepeaterBlock() {

            var repeaterClone = $('.nc-repeater__item').first().closest('.nc-repeater__droparea').clone();
            repeaterClone.find(inputTypes.join()).val('');
            repeaterClone.appendTo('.nc-repeater');

            // Get an empty drop area to put after the new repeater
            $('.nc-repeater__droparea').first().clone().empty().appendTo('.nc-repeater');
            repeaterToggleAddRow(true);

            // Bind it to droppable
            repeaterClone.droppable(droppableAttr).sortable(sortableAttr);;

            // Bind it to draggable
            repeaterClone.find('.nc-repeater__item').draggable(draggableAttr);

        }


        /* ON LOAD FUNCTION CALLS */

        /*
         * Check to see if any field in a repeater block is empty
         */

        if (repeaterIsEmpty() === false) {

            repeaterToggleAddRow(false);

        }


        /* EVENTS */

        /*
         * Whilst typing in a repeater field, check to see if any fields are still empty
         */

        $('.postbox-container').on('keyup', inputTypes.join(), function() {

            if (repeaterIsEmpty() === false) {
                repeaterToggleAddRow(false);
            } else {
                repeaterToggleAddRow(true);
            }

        });


        /*
         * When add repeater button is clicked generate a new repeater block
         */

        $('#nc_repeater_btn_add').click(function(e) {

            addRepeaterBlock();
            e.preventDefault();

        });


        /*
         * Bind all repeater items to jquery ui draggable
         */
        $('.nc-repeater__item').draggable(draggableAttr);


        /*
         * Bind all repeater items to jquery ui droppable and sortable
         */

        $('.nc-repeater__droparea').droppable(droppableAttr).sortable(sortableAttr);

	});

}(jQuery));