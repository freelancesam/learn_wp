(function($) {
    "use strict";

    function updateSize() {
        $(this).attr('size', $(this).val().length);
    }

    Ractive.decorators.autosize = function(node) {

        var $node = $(node);

        $node.on('input.autosize change.autosize keyup.autosize', updateSize);

        updateSize.call($node);

        return {
            teardown: function() {
                $node.off('.autosize').removeAttr('size');
            }
        };
    };

})(jQuery);