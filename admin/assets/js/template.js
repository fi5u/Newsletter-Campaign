(function ( $ ) {
    var ncCodemirror = [];

    function init() {
        setCodemirrorTextareas();
        addButtonBar();
    }


    /**
     * Instantiate the Codemirror editor
     * @param  {obj} id The textarea to apply the editor to
     */
    function codemirrorEditor(id, order) {
        ncCodemirror[order] = CodeMirror.fromTextArea(id, codemirrorArgs);
    }


    /**
     * Get all textareas to pass to Codemirror
     */
    function setCodemirrorTextareas() {
        $('textarea').each(function(i) {
            codemirrorEditor(this, i);
        });
    }


    /**
     * Generate the buttons to go into each button bar
     * @param  {str} textareaId The id of the textarea with which the button bar is located
     * @return {str}            The complete HTML for the buttons
     */
    function generateButtons(textareaId) {
        var buttonBar = '';

        for (var button in buttons) {
            if (buttons.hasOwnProperty(button)) {
                buttonBar += '<li class="' + buttons[button].class + '"><a href="#"';

                buttonBar += buttons[button].id ? '" id="' + buttons[button].id + '"' : '';

                buttonBar += '>' + buttons[button].title + '</a>';

                if (buttons[button].children) {
                    buttonBar += '<ul>';

                    for (var buttonChild in buttons[button].children) {
                        if (buttons[button].children.hasOwnProperty(buttonChild)) {
                            var selfButtonChild = buttons[button].children[buttonChild];
                            // If this button should not be in this button bar, skip this iteration
                            if (selfButtonChild.instance_exclude && selfButtonChild.instance_exclude === textareaId) {
                                continue;
                            }
                            buttonBar += '<li class="' + selfButtonChild.class + '"><a href="#" id="' + selfButtonChild.id + '" title="' + selfButtonChild.title + '" data-shortcode="' + selfButtonChild.shortcode + '">' + selfButtonChild.title + '</a></li>';
                        }
                    }

                    buttonBar += '</ul>';
                }

                buttonBar += '</li>';
            }
        }

        return buttonBar;
    }


    /**
     * Insert the button bar and buttons into the DOM before every Codemirror
     */
    function addButtonBar() {
        $('.CodeMirror').before('<div class="nc-button-bar"><ul></ul></div>');

        $('.CodeMirror').each(function() {
            var selfBtns = generateButtons($(this).prevAll('textarea').attr('id'));
            $(this).prev('.nc-button-bar').find('ul').append(selfBtns);
        });
    }


    /**
     * Insert the args bar into the DOM
     * @param {obj} args      Properties for arguments
     * @param {str} shortcode The shortcode used by WordPress
     * @param {int} iteration The nth instance
     * @param {str} shortcodeTitle Title of the shortcode
     */
    function addArgsBar(args, shortcode, iteration, shortcodeTitle) {
        var argsBar = '<div class="nc-button-bar--args" id="nc-button-bar-args-' + shortcode + '">';

        // First remove any currenly active args bars
        $('.nc-button-bar--args').remove();
        argsBar += '<h5 class="nc-button-bar__title">' + shortcodeTitle + '</h5>';

        for (var arg = 0; arg < args.length; arg++) {
            argsBar += '<div class="nc-button-bar__arg">';
            argsBar += '<label class="nc-button-bar__arg-label" for="' + args[arg].name + '">' + args[arg].title + '</label>';

            if (args[arg].type === 'select' && args[arg].values) {
                argsBar += '<select name="' + args[arg].name + '" id="' + args[arg].name + '" data-arg="' + args[arg].arg + '">';
                // Insert a value-less option
                argsBar += '<option value="">' + translation.selectImageSize + '</option>';
                for (var i = 0; i < args[arg].values.length; i++) {
                    var value = args[arg].values[i];
                    argsBar += '<option value="' + value + '">' + value + '</option>';
                };
                argsBar += '</select>';
            } else {
                argsBar += '<input type="text" name="' + args[arg].name + '" id="' + args[arg].name + '" placeholder="' + translation.optional + '" data-arg="' + args[arg].arg + '">';
            }
            argsBar += '</div>';
        };

        argsBar += '<button type="button" class="button" id="nc-shortcode-arg-btn-' + shortcode + '">' + translation.insert + '</button>'; /* TODO: gettext this val */
        argsBar += '<button type="button" class="button" id="nc-shortcode-cancel-btn-' + shortcode + '">' + translation.cancel + '</button>'; /* TODO: gettext this val */
        argsBar += '</div>';

        // Insert into the DOM
        $('.CodeMirror').eq(iteration).prevAll('.nc-button-bar').after(argsBar);
    }


    /**
     * Fetch all the args for this shortcode
     * @param  {str} shortcode The shortcode WordPress uses
     * @param  {int} iteration The nth instance
     */
    function populateWithArgs(shortcode, iteration) {
        var args = '',
            shortcodeComplete;
        $('#nc-button-bar-args-' + shortcode + '').find('.nc-button-bar__arg').each(function(i) {
            var inputType = $(this).find('input').length ? 'input' : 'select';
            var argName = $(this).find(inputType).data('arg'),
                argVal = $(this).find(inputType).val();

            if (argVal) {
                args += ' ' + argName + '="' + argVal + '"';
            }
        });

        shortcodeComplete = '[' + shortcode + args + ']';

        // Insert the shortcode with args (if supplied)
        ncCodemirror[iteration].doc.replaceSelection(shortcodeComplete);
    }


    /**
     * Insert shortcode at the cursor, or if has optional parameters, fetch them
     * @param  {str} shortcode The shortcode WordPress uses
     * @param  {int} iteration The nth instance
     */
    function populateWithShortcode(shortcode, iteration) {
        // Find out if this shortcode takes args

        for (var button = 0; button < buttons.length; button++) {
            if (buttons[button].children) {
                for (var child = 0; child < buttons[button].children.length; child++) {
                    if (buttons[button].children[child].shortcode === shortcode) {
                        // This is the correct object
                        // Check if it contains args
                        if (buttons[button].children[child].args) {
                            // Add the args bar
                            addArgsBar(buttons[button].children[child].args, shortcode, iteration, buttons[button].children[child].title);
                        } else {
                            // Insert the shortcode without args
                            ncCodemirror[iteration].doc.replaceSelection('[' + shortcode + ']');
                        }
                    }
                };
            }
        };
    }


    /**
     * Remove the current argument bar
     * @param  {obj} $self The pressed cancel button
     */
    function cancelArgInput($self) {
        $self.closest('.nc-button-bar--args').remove();
    }


    /**
     * On load function calls
     */
    init();


    /*
     * EVENTS
     */

    // Click a shortcode button
    $('body').on('click', '.nc-button-bar__button a', function(e) {
        // Get the instance iteration
        var iteration = $(this).closest('.nc-button-bar').nextAll('.CodeMirror').index('.CodeMirror');
        populateWithShortcode($(this).data('shortcode'), iteration);
        e.preventDefault();
    });

    // Click the shortcode insert with args button
    $('body').on('click', '[id^=nc-shortcode-arg-btn-]', function(e) {
        var idSplit = $(this).attr('id').split('-'),
            shortcode = idSplit[idSplit.length - 1],
            iteration = $(this).closest('.nc-button-bar--args').nextAll('.CodeMirror').index('.CodeMirror');
        populateWithArgs(shortcode, iteration);
        e.preventDefault();
    });

    // Click the shortcode args cancel button
    $('body').on('click', '[id^=nc-shortcode-cancel-btn-]', function(e) {
        cancelArgInput($(this));
    });

}(jQuery));