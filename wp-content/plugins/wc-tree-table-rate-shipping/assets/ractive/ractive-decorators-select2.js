/*

 ractive-decorators-select2
 =============================================

 Integrate Ractive with Select2

 ==========================

 Troubleshooting: If you're using a module system in your app (AMD or
 something more nodey) then you may need to change the paths below,
 where it says `require( 'ractive' )` or `define([ 'ractive' ]...)`.

 ==========================

 Usage: Include this file on your page below Ractive, e.g:

 <script src='lib/ractive.js'></script>
 <script src='lib/ractive-decorators-select2.js'></script>

 Or, if you're using a module loader, require this module:

 // requiring the plugin will 'activate' it - no need to use
 // the return value
 require( 'ractive-decorators-select2' );

 */

(function ( global, factory ) {

    'use strict';

    // Common JS (i.e. browserify) environment
    if ( typeof module !== 'undefined' && module.exports && typeof require === 'function' ) {
        factory( require( 'ractive' ), require( 'jquery' ) );
    }

    // AMD?
    else if ( typeof define === 'function' && define.amd ) {
        define([ 'ractive', 'jquery' ], factory );
    }

    // browser global
    else if ( global.Ractive && global.jQuery) {
        factory( global.Ractive, global.jQuery );
    }

    else {
        throw new Error( 'Could not find Ractive or jQuery! They must be loaded before the ractive-decorators-select2 plugin' );
    }

}( typeof window !== 'undefined' ? window : this, function ( Ractive, $ ) {

    'use strict';

    var select2Decorator;

    select2Decorator = function (node, type) {

        var ractive = node._ractive.root || node._ractive.ractive;
        var updating = false;
        var observer;

        var options = {};
        if (type) {
            if (!select2Decorator.type.hasOwnProperty(type)) {
                throw new Error('Ractive Select2 type "' + type + '" is not defined!');
            }

            options = select2Decorator.type[type];
            if (typeof options === 'function') {
                options = options.call(this, node);
            }
        }

        var templateSelectionBkp = options.templateSelection;
        options.templateSelection = function(data) {

            var result = templateSelectionBkp && templateSelectionBkp(data) || data.text;

            if (!data.title) {
                data.title = ' ';
            }

            return result;
        };

        var update = function (newvalue) {

            if (!updating) {

                updating = true;

                try {

                    if (newvalue === "") {
                        $(node).select2("val", "");
                    } else if ($.isArray(newvalue) && newvalue.length > 0) {

                        var existingValues = $(node).find('option').map(function () {
                            return this.value !== undefined ? this.value : $(this).text();
                        }).get();

                        for (var i = 0; i < newvalue.length; i++) {

                            var v = newvalue[i];

                            if (existingValues.indexOf(v) == -1) {
                                $(node).append(
                                    $(document.createElement('option'))
                                        .attr('value', v)
                                        .attr('title', 'The referenced item has been deleted from Woocommerce.')
                                        .text('#' + v + ' (missing)')
                                );
                            }
                        }

                    }

                    $(node).change();
                    $(node).select2("val", newvalue);

                } finally {
                    updating = false;
                }
            }
        };

        // Pull changes from select2 to ractive
        $(node).select2(options).on('change', function () {
            if (!updating) {
                updating = true;
                try {
                    ractive.updateModel();
                } finally {
                    updating = false;
                }
            }
        });

        // Push changes from ractive to select2
        if (node._ractive.binding) {
            var binding = node._ractive.binding;
            var keypath = binding.keypath ? binding.keypath.str : binding.model.key;
            observer = ractive.observe(keypath, update);
        }

        return {
            teardown: function () {
                
                $(node).select2('destroy');

                if (observer) {
                    observer.cancel();
                }
            }
        };
    };

    select2Decorator.type = {};

    Ractive.decorators.select2 = select2Decorator;

}));