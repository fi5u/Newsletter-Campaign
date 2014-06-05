(function ( $ ) {
    "use strict";

    $(function () {

        /*
         * NEWSLETTER BUILDER
         *
         * Manages the dragging and dropping of elements within builder
         */

        /* SCOPED VARIABLES */

        // Store offsets for dragging
        var dragAreaOffset = $('.nc-builder__posts').offset(),
            dropMarginTop = parseInt($('.nc-builder__output-text').css('marginTop')),
            dropMarginLeft = parseInt($('.nc-builder__output-text').css('marginLeft')),

            draggableAttr = {
                start: function(event, ui) {
                    $(this).data('originalOffset', ui.offset);
                },
                revert: 'invalid'
            },

            droppableAttr = {
                activeClass: 'ui-state-default',
                hoverClass: 'ui-state-hover',
                accept: '.nc-builder__post',
                drop: function( event, ui ) {
                    drop($(this), event, ui);
                }
            };

        /* FUNCTIONS */

        /*
         * Set the new position of the dropped item after dropping
         * Param    jQuery object of the dropped item
         *          event
         *          ui
         */

        function drop($this, event, ui) {

            ui.draggable.detach().appendTo($this);
            var originalOffset = ui.draggable.data('originalOffset');

            var dropItem = $this.children('.nc-builder__post');
            var boxPosition = dropItem.position();

            var container = $this;
            var containerPosition = container.position();

            var newTop = originalOffset.top + boxPosition.top - containerPosition.top - dragAreaOffset.top - dropMarginTop;
            var newLeft = originalOffset.left + boxPosition.left - containerPosition.left - dragAreaOffset.left - dropMarginLeft;

            dropItem.css({top:newTop,left:newLeft}).animate({top:0,left:0});

        }


        /* ON LOAD FUNCTION CALLS */

        /*
         * Bind all repeater items to jquery ui draggable
         */

        $('.nc-builder__post').draggable(draggableAttr);


        /*
         * Bind all empty repeater drop areas to jquery ui droppable
         */

        $('.nc-builder__output-text').droppable(droppableAttr);

        /* EVENTS */

    });

}(jQuery));