(function ( $ ) {

    function init() {
        setCodemirrorTextareas();
    }


    function codemirrorEditor(id) {
        CodeMirror.fromTextArea(id, codemirrorArgs);
    }


    function setCodemirrorTextareas() {
        $('textarea').each(function() {
            codemirrorEditor(this);
        });
    }

    init();

}(jQuery));