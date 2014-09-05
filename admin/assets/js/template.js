(function ( $ ) {

    function init() {
        setCodemirrorTextareas();
    }


    function codemirrorEditor(id) {
        CodeMirror.fromTextArea(id, {});
    }


    function setCodemirrorTextareas() {
        $('textarea').each(function() {
            codemirrorEditor(this);
        });
    }

    init();

}(jQuery));