(function ( $ ) {
    "use strict";

    $(function () {

        /*
         * REPEATER FIELD
         *
         * Manages the dragging and dropping of repeater field elements
         */


        /* SCOPED VARIABLES */

            // List the input types used in the repeater
        var inputTypes = [
            'input[type="text"]',
            'textarea'
            ],

            // Store offsets for dragging
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
                accept: function() {
                    // If the drop area already has a repeater item, do not drop
                    var $this = $(this);
                    if ($this.has('.nc-repeater__item').length) {
                        return false;
                    } else {
                        return true;
                    }
                },
                drop: function( event, ui ) {
                    repeaterDrop($(this), event, ui);
                    removeExcessDropAreas();
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
            repeaterClone.droppable(droppableAttr);

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

            // Check repeater quantity to disable delete button if needed
            checkRepeaterQty();
        }


        /*
         * Add a new repeater item and bind its drag and drop functions
         */

        function addRepeaterBlock() {

            var repeaterClone = $('.nc-repeater__item').first().closest('.nc-repeater__droparea').clone();

            // Empty all values
            repeaterClone.find(inputTypes.join()).val('');

            repeaterClone.appendTo('.nc-repeater');

            // Fill the hidden input with a random hash for id
            repeaterClone.find('.nc-repeater__hidden-id').val(Math.random().toString(36).replace(/[^a-zA-Z0-9]+/g, '').substr(0,8));

            // Get an empty drop area to put after the new repeater
            $('.nc-repeater__droparea').first().clone().empty().appendTo('.nc-repeater');
            repeaterToggleAddRow(true);

            // Bind it to draggable
            repeaterClone.find('.nc-repeater__item').draggable(draggableAttr);

            // Check repeater quantity to undisable delete button if needed
            checkRepeaterQty();

        }


        function checkRepeaterQty() {
            var repeaterQty = $('.nc-repeater__item').length;

            if (repeaterQty <= 1) {
                $('.nc-repeater__droparea-delete').attr('disabled','true');
            }
            if (repeaterQty > 1) {
                $('.nc-repeater__droparea-delete').removeAttr('disabled');
            }

            if (repeaterIsEmpty() === false) {

                repeaterToggleAddRow(false);

            }
        }


        /*
         * Delete repeater item
         * Param    jquery object
         */

        function deleteRepeater($repeaterBtn, cb) {

            $repeaterBtn.closest('.nc-repeater__droparea').remove();
            if (cb) cb();

        }


        /* ON LOAD FUNCTION CALLS */

        /*
         * Bind all repeater items to jquery ui draggable
         */

        $('.nc-repeater__item').draggable(draggableAttr);


        /*
         * Bind all empty repeater drop areas to jquery ui droppable
         */

        $('.nc-repeater__droparea:not(:has(.nc-repeater__item))').droppable(droppableAttr);


        /*
         * Check to see if any field in a repeater block is empty
         */

        if (repeaterIsEmpty() === false) {

            repeaterToggleAddRow(false);

        }


        /*
         * Check set repeater delete button to disabled
         */
        checkRepeaterQty();


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
         * Delete drop area when delete button pressed
         */

        $('body').on('click', '.nc-repeater__droparea-delete', function() {

            deleteRepeater($(this), removeExcessDropAreas);

        });
    });

}(jQuery));