{
    "use strict";

    var __plugin = 'destinationList';

    Ractive.decorators[__plugin] = function (node, postalCodeDelimiter) {

        if (!postalCodeDelimiter) {
            postalCodeDelimiter = '/zip:';
        }

        var postalCodeVisualDelimiter = ':';

        var $ = jQuery;

        {
            var keypath = node._ractive.binding.keypath.str;
            var value = this.get(keypath);

            $($.map($.isArray(value) ? value : [value], function (value) {

                var region, postcodes; {
                    var parts = value.split(postalCodeDelimiter);
                    if (parts.length != 2) {
                        return null;
                    }

                    region = parts[0];
                    postcodes = parts[1];
                    if (!region || !postcodes) {
                        return null;
                    }
                }

                var title =
                    ($(node).find('[value="'+region+'"]').text() || region) +
                    postalCodeVisualDelimiter + ' ' +
                    $.map(postcodes.split(','), $.trim).join(', ');

                var $option = $('<option>')
                    .text(title)
                    .attr('value', value)
                    .attr('selected', true);

                return $option[0];

            })).appendTo(node);
        }

        var select2Decorator;
        {
            var select2DecoratorPlugin = Ractive.decorators.select2;

            select2DecoratorPlugin.type[__plugin] = {
                tags: true,
                selectOnClose: true,
                closeOnSelect: true,
                language: {
                    'inputPostalCodes': function() {
                        return 'Type in zip/postal code(s)';
                    }
                },

                createTag: function (params) {
                    var parts = $.map(params.term.split(postalCodeVisualDelimiter, 2), $.trim);
                    var region = parts[0];
                    var postcodes = parts[1];

                    var id = null;
                    if (region) {
                        this.$element.children().each(function() {
                            if ($(this).text() === region) {
                                id = $(this).attr('value') || region;
                                return false;
                            }
                        });
                    }

                    if (!id) {
                        return null;
                    }

                    var term = region;

                    if (postcodes) {
                        postcodes = $.map(postcodes.split(','), function(code) {

                            code = $.trim(code);

                            if (code.indexOf('-') != -1) {
                                code = $.map(
                                    code.replace(/\*/g, '').split('-', 2),
                                    function (part) {
                                        return $.trim(part).replace(/-/g, '');
                                    }
                                ).join('-');

                                if (code.charAt(0) == '-' || code.charAt(code.length - 1) == '-') {
                                    code = code.replace('-', '');
                                }
                            }

                            code = code.replace(/\*+/g, '*');

                            return code === '' ? null : code;

                        }).join(',');
                    }

                    if (!postcodes) {
                        return null;
                    }

                    id += postalCodeDelimiter + postcodes;
                    term += postalCodeVisualDelimiter + ' ' + postcodes.replace(/,/g, ', ');

                    return {
                        id: id,
                        text: term
                    };
                }
            };

            select2Decorator = select2DecoratorPlugin.call(this, node, __plugin);

            var select2Instance = $(node).data('select2');
            var $search = select2Instance.selection.$search;

            select2Instance.$container.on('click', '.select2-selection__choice', function() {

                var item = $(this).data('data');

                select2Instance.trigger('unselect', {data: item});

                $search.val(item.text + (item.text.indexOf(postalCodeVisualDelimiter) == -1 ? postalCodeVisualDelimiter : ''));
                $search.css('width', (($search.val().length + 1) * 0.75) + 'em');
                select2Instance.trigger('query', {term: $search.val()});
            });

            select2Instance.on('results:message', function(params) {
                if (params.message === 'noResults') {
                    var marker = postalCodeVisualDelimiter;
                    var endsWithMarker = $.trim($search.val()).substr(-marker.length) === marker;
                    if (endsWithMarker) {
                        this.trigger('results:message', {
                            message: 'inputPostalCodes'
                        });
                    }
                }
            });
        }

        return {
            teardown: function () {
                if (select2Decorator) {
                    select2Decorator.teardown();
                    select2Decorator = null;
                }
            }
        };
    };
}