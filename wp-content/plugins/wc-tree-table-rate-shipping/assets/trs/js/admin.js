if (!window.__trs_admin_js_loaded) {
    window.__trs_admin_js_loaded = true;

    jQuery(function ($) {

        var init = function () {

            // Receive root rule and all its children
            {
                if (!rootRule) {
                    rootRule = {
                        operations: {
                            list: [
                                {operation: 'add', calculator: {calculator: 'children', aggregator: 'all'}}
                            ]
                        }
                    }

                }

                rootRule = Rule.receive(rootRule);

                if (!rootRule.meta.sequence) {
                    rootRule.meta.sequence = 0;
                }
            }


            var Conditions = Ractive.extend({

                isolated: false,
                template: '#trs_conditions',

                data: function () {
                    return {
                        meta: {
                            grouping: ''
                        },
                        list: [],
                        rule: null,

                        isAlwaysSingle: function (condition) {
                            return condition && ['destination', 'customer'].indexOf(condition.condition) != -1;
                        }
                    }
                },

                computed: {

                    hideAdd: function () {
                        return this.isLastConditionDummy(this.get('list'));
                    },

                    hideDelete: function () {
                        var list = this.get('list');
                        return list && list.length == 1 && this.isLastConditionDummy(list);
                    },

                    perItemGrouping: function () {
                        return this.get('meta.grouping') == 'item';
                    }
                },

                oninit: function () {

                    var ractive = this;
                    var maintainDummyCondition = true;

                    this.observe('list', function (newConditions) {

                        if (maintainDummyCondition) {

                            // Being not deferred that conflicts with meaningles conditions auto-removal on before save
                            // hitting a null keypath error deeply inside of ractive lib.
                            (_.debounce(function () {

                                if (!newConditions.length) {
                                    ractive.push('list', {});
                                }

                                if (newConditions.length > 1 && ractive.get('hideAdd')) {
                                    ractive.pop('list')
                                }
                            }, 0))();
                        }

                        {
                            var list = this.get('list');
                            var changed = false;
                            for (var i = 0; i < list.length; i++) {
                                if (get(list[i], 'terms.operator') == 'no' && get(list[i], 'terms.subcondition.condition') != '') {
                                    list[i].terms.subcondition.condition = '';
                                    changed = true;
                                }
                            }

                            if (changed) {
                                this.set('list', list);
                            }
                        }
                    });

                    this.on({

                        add: function (e) {

                            e.original.preventDefault();

                            maintainDummyCondition = false;
                            this.push('list', {});
                            maintainDummyCondition = true;
                        },

                        remove: function (e) {
                            e.original.preventDefault();
                            this.remove(e.keypath);
                        }
                    });
                },

                isDummyCondition: function (condition) {
                    return condition && condition.condition == 'true';
                },

                isLastConditionDummy: function (list) {
                    return list && list.length && this.isDummyCondition(list[list.length - 1]);
                }
            });


            var isFreeOperation = function (operation) {
                return (
                    operation &&
                    operation.operation == 'add' &&
                    get(operation, 'add.calculator.calculator') == 'free'
                );
            };

            var isOperationVisible = function (data, index) {
                return !(
                    !(data.children && data.children.length) &&
                    data.list &&
                    data.list[index] &&
                    data.list[index].operation == 'add' &&
                    get(data.list[index], 'add.calculator.calculator') == 'children'
                );
            };

            var visibleOperations = function (data, beforeIndex) {

                var visibleOperations = [];

                if (!data || !data.list) {
                    return visibleOperations;
                }

                if (beforeIndex === undefined) {
                    beforeIndex = data.list.length;
                }

                for (var i = 0; i < beforeIndex; i++) {
                    if (isOperationVisible(data, i)) {
                        visibleOperations.push(data.list[i]);
                    }
                }

                return visibleOperations;
            };

            var Operations = Ractive.extend({
                isolated: false,
                template: '#trs_operations',
                data: function () {
                    return {
                        meta: {
                            grouping: ''
                        },
                        list: [],

                        isOperationVisible: function (index) {
                            return isOperationVisible(this.get(), index);
                        },

                        getVisibleOperationsCount: function (beforeIndex) {
                            return visibleOperations(this.get(), beforeIndex).length;
                        },

                        hideAdd: function () {

                            var visibleOperations = this.get('visibleOperations');

                            return (
                                visibleOperations.length &&
                                isFreeOperation(visibleOperations[visibleOperations.length - 1])
                            );
                        },

                        hideDelete: function (operation) {
                            return (
                                isFreeOperation(operation) &&
                                this.get('visibleOperationsCount') == 1
                            );
                        }
                    }
                },

                computed: {
                    visibleOperations: function () {
                        return visibleOperations(this.get());
                    },
                    visibleOperationsCount: function () {
                        return this.get('visibleOperations').length;
                    }
                },

                oninit: function () {
                    var previousData;

                    var that = this;

                    this.observe('meta list', _.debounce(function () {

                        if (previousData) {

                            var data = that.get();

                            var calculatorsCheckError = null;
                            {

                                var childCalculators = 0;
                                var unaggregatedCalculatorCount = 0;
                                var unaggregatedInvisibleCalculators = [];

                                $.each(data.list, function (idx, operation) {

                                    var calculatorWrapper = get(operation, operation.operation + '.calculator');
                                    var calculatorName = get(calculatorWrapper, 'calculator');
                                    var calculator = get(calculatorWrapper, calculatorName);
                                    if (!calculator) {
                                        return;
                                    }

                                    if (calculator.aggregator == 'all') {

                                        unaggregatedCalculatorCount++;

                                        var isInvisible = calculatorName == 'children' && !get(data, 'children.length');
                                        if (isInvisible) {
                                            unaggregatedInvisibleCalculators.push(calculator);
                                        }
                                    }

                                    if (calculatorName == 'children') {
                                        childCalculators++;
                                    }
                                });

                                var allowedUnaggregatedCount = (data.meta.grouping ? 0 : 1);

                                if (unaggregatedCalculatorCount > allowedUnaggregatedCount &&
                                    unaggregatedCalculatorCount - unaggregatedInvisibleCalculators.length <= allowedUnaggregatedCount) {

                                    $.each(unaggregatedInvisibleCalculators, function (idx, calc) {
                                        calc.aggregator = 'sum';
                                    });

                                    that.set(data);

                                    unaggregatedCalculatorCount -= unaggregatedInvisibleCalculators.length;
                                }

                                if (childCalculators == 0) {
                                    calculatorsCheckError =
                                        "Please keep at least one child rules calculation to make sure child rules " +
                                        "will take effect. In other case they will not be processed at all.";
                                }

                                if (unaggregatedCalculatorCount > allowedUnaggregatedCount) {
                                    if (data.meta.grouping) {
                                        calculatorsCheckError =
                                            "Processing multiple packages with multiple rates for each is " +
                                            "ambiguous. Please either reset 'Calculate fees' or use a single rate " +
                                            "(i.e. anything except 'all').";
                                    } else {
                                        calculatorsCheckError =
                                            "Adding two or mote rate sets together is ambiguous. Please keep a single " +
                                            "rate set at time and aggregate other ones (i.e. anything except 'all')";
                                    }
                                }
                            }

                            if (calculatorsCheckError) {
                                (function () {
                                    var pd = previousData;
                                    var msg = calculatorsCheckError;
                                    setTimeout(function () {
                                        alert(msg);
                                        pd = JSON.parse(pd);
                                        delete pd.children;
                                        that.set(pd);
                                    }, 0);
                                })();
                            }
                        }

                        previousData = JSON.stringify(that.get());
                    }, 0));

                    var maintainFreeCharge = true;

                    this.observe('visibleOperations', function (newOperations) {

                        if (maintainFreeCharge) {

                            var ractive = this;

                            //(_.debounce(function() {

                            if (!newOperations.length) {
                                ractive.push('list', {});
                            }

                            if (newOperations.length > 1 && ractive.get().hideAdd.call(ractive)) {
                                ractive.pop('list')
                            }
                            //}, 0))();
                        }
                    });

                    this.on({
                        add: function (e) {
                            e.original.preventDefault();

                            maintainFreeCharge = false;
                            this.push('list', {});
                            maintainFreeCharge = true;
                        },

                        remove: function (e) {
                            e.original.preventDefault();
                            this.remove(e.keypath);
                            this.update(); // force tpl changes depending on component functions, e.g. visibleOperations()
                        }
                    })
                }
            });

            Ractive.components.Hint = Ractive.extend({
                isolated: true,
                template: '#trs_hint',
                data: {
                    type: null
                }
            });

            var isUnextendable = function (rule) {
                return (
                    !get(rule, 'conditions.meta.capture') && !get(rule, 'conditions.meta.grouping') && !get(rule, 'operations.meta.grouping')
                );
            };

            var ractive = new Ractive({
                el: '#trs',
                template: '#trs_template',

                data: {
                    rule: rootRule,

                    snippets: snippets,

                    showExtended: function (rule) {
                        return rule && (!isUnextendable(rule) || rule._view.extended);
                    },

                    isUnextendable: isUnextendable
                },

                components: {
                    Conditions: Conditions,
                    Operations: Operations
                }
            });

            ractive.on({
                add: function (e, position, mode, snippet) {

                    e.original.preventDefault();

                    var positions = ['append', 'prepend', 'after', 'before'];
                    var modes = ['new', 'clone', 'snippet'];

                    position = positions[Math.max(0, positions.indexOf(position))];
                    mode = modes[Math.max(0, modes.indexOf(mode))];

                    var child;
                    if (mode == 'clone') {
                        child = clone(e.context);
                    } else {

                        if (mode == 'snippet' && snippets[snippet]) {
                            snippet = Rule.map(clone(snippets[snippet].config), function (rule) {
                                rule._view = rule._view || {};
                                rule._view.settings = false;
                                rule._view.showSettings = false;
                                return rule;
                            });
                        } else {
                            snippet = {};
                        }

                        child = Rule.receive(snippet);
                    }

                    var label = child.meta.label;
                    if (mode == 'clone') {
                        label = 'Copy' + (label ? ' of ' + label : '');
                    } else if (!label) {
                        this.add('rule.meta.sequence');
                        label = 'Rule #' + this.get('rule.meta.sequence');
                    }
                    child.meta.label = label;


                    var keypath = e.keypath;

                    if (!keypath || (keypath !== 'rule' && keypath.substr(0, 5) !== 'rule.')) {
                        keypath = null;
                    }

                    if (!keypath) {

                        if (position == 'after') {
                            position = 'append';
                        } else if (position == 'before') {
                            position = 'prepend';
                        }

                        keypath = 'rule';
                    }


                    var list;

                    if (['append', 'prepend'].indexOf(position) != -1) {
                        list = this.keypath(keypath + '.children');
                        this[position == 'append' ? 'push' : 'unshift'](list.fullname, child);
                    } else if (['after', 'before'].indexOf(position) != -1) {
                        keypath = this.keypath(e.keypath);
                        list = keypath.parent();
                        this.get(list.fullname).splice((keypath.basename | 0) + (position == 'after' ? 1 : 0), 0, child);
                    } else {
                        throw "Invalid insertion position '" + position + "'";
                    }

                    var listParent;
                    if (listParent = list.parent()) {
                        this.modify(listParent.join('_view'), function (view) {
                            return updateView(view, 'expanded', true);
                        });
                    }
                },

                remove: function (e, mode) {

                    e.original.preventDefault();

                    var modes = ['withchildren', 'keepchildren'];
                    mode = modes[Math.max(0, modes.indexOf(mode))];

                    var kp = this.keypath(e.keypath);

                    var args = [kp.parent().fullname, kp.basename, 1];
                    if (mode == 'keepchildren') {
                        args = args.concat(e.context.children);
                    }

                    this.splice.apply(this, args);
                },

                toggle: function (e) {
                    e.original.preventDefault();

                    this.modify(e.keypath + '._view', function (view) {
                        return updateView(view, 'expanded', !view.expanded);
                    });
                },

                settings: function (e) {
                    if (e.original.target != e.node) {
                        return;
                    }

                    e.original.preventDefault();

                    var keypath = e.keypath + '._view';
                    var view = this.get(keypath);

                    if (view.settings && !triggerHtml5Validation()) {
                        return;
                    }

                    this.set(keypath, updateView(view, 'settings', !view.settings));
                },

                extend: function (e) {

                    var data = this.get(e.keypath);

                    if (!isUnextendable(data)) {
                        alert("In order to hide advanced settings reset them to their default state.");
                        return;
                    }

                    data._view.extended = !data._view.extended;

                    this.set(e.keypath, data);
                }
            });

            // Handle saving
            (function () {
                var $submitRow = $form.find('p.submit');
                var $saveButton = $submitRow.find('[type="submit"]').after('<span class="spinner" />');
                var $displayedMessages = $('<div class="trs-messages" />').insertAfter($submitRow);

                var toggleMessage = function ($message, show) {
                    $message[show ? 'slideDown' : 'slideUp'](
                        show ? 'fast' : 'slow',
                        show ? undefined : function () {
                            $message.remove();
                        }
                    );
                };

                var changed = false;

                var onChange = function () {
                    if (!changed) {
                        changed = true;
                        $submitRow.slideDown('fast');
                    }
                };

                ractive.observe('*', onChange, {init: false});
                $form.change(onChange);

                $displayedMessages.on('click', '.dismiss', function () {
                    toggleMessage($(this).closest('.message'), false);
                });

                // 'action=""' makes IE 11 (and maybe other versions) reporting incorrect url for the 'action' form element attribute
                // which is used by ajaxForm to determine the request target. Removing the attribute
                // completely fixes the issue.
                $form.removeAttr('action');

                $form.ajaxForm({
                    beforeSerialize: function () {

                        // Clean config before saving
                        var rule = Rule.map(clone(ractive.get('rule')), function (rule) {

                            rule.conditions.list = $.map(rule.conditions.list, function (condition) {

                                var params = condition[condition.condition];
                                if (params) {
                                    var isEmptyList = $.isArray(params.value) && !params.value.length;
                                    var isEmptyRange = params.operator == 'btw' && '' + params.min + params.max === '';
                                    if (isEmptyList || isEmptyRange) {
                                        return null;
                                    }
                                }

                                return condition;
                            });

                            return rule;
                        });

                        // Let user see the changes
                        ractive.set('rule', clone(rule));

                        // Prepare for save
                        rule = Rule.release(rule);
                        $ruleJsonInput.val(JSON.stringify(rule));
                        $trs.find(':input:not([disabled])').attr('disabled', true).data('trs-enable', true);
                    },

                    beforeSubmit: function (data, $form) {

                        var form = $form[0];
                        if (form.checkValidity && !form.checkValidity()) {
                            return false;
                        }

                        $saveButton.attr('disabled', true).addClass('inprogress');
                    },

                    beforeSend: function (xhr) {

                        // Mask the fact that request made via ajax. We don't want it to be handled specially.
                        // Also, this resolves conflict with WPML: 'You do not have sufficient permissions to access this page'.
                        xhr.setRequestHeader('X-Requested-With', {
                            toString: function () {
                                return '';
                            }
                        });

                        $trs.find(':input:data(trs-enable)').attr('disabled', false).data('trs-enable', false);

                        changed = false;
                    },

                    success: function (response) {
                        var $incomingMessages = $($.parseHTML(response))
                            .find('#message')
                            // Remove notices which many plugins like to show on all admin pages
                            .filter(function () {
                                return (
                                    !$(this).is('.updated') ||
                                    $(this).find('a').filter(function () {
                                        return $(this).text().match(/dismiss/i);
                                    }).length == 0
                                );
                            });

                        if (!$incomingMessages.length) {
                            $incomingMessages = $('<div class="error"><p><strong>Unknown error occurred</strong></p></div>');
                        }

                        $incomingMessages
                            .removeAttr('id')
                            .addClass('message')
                            .find('p')
                            .prepend('<span class="dismiss dashicons dashicons-dismiss"/>')
                            .end()
                            .hide()
                            .appendTo($displayedMessages);

                        toggleMessage($incomingMessages, true);

                        // Hide success messages automatically since they would annoy user otherwise
                        var $successMessages = $incomingMessages.filter('.updated');
                        $successMessages.each(function (idx) {
                            setTimeout(
                                (function ($m) {
                                    return function () {
                                        toggleMessage($m, false);
                                    }
                                })($(this)),
                                3000 + idx * 200
                            );
                        });

                        if (!changed && $successMessages.length == $incomingMessages.length) {
                            $submitRow.slideUp('fast');
                        }
                    },
                    complete: function () {
                        $saveButton.attr('disabled', false).removeClass('inprogress');
                    }
                });

                // Setup navigate-away warning
                {
                    var setupUnsavedChangesWarning = function () {
                        window.onbeforeunload = function () {
                            if (changed) {
                                //noinspection JSUnresolvedVariable
                                return (
                                    (woocommerce_settings_params && woocommerce_settings_params.i18n_nav_warning) ||
                                    'The changes you made will be lost if you navigate away from this page.'
                                );
                            }
                        }
                    };

                    // Execute lately to overwrite woocommerce assignments
                    $(window).load(function () {
                        setTimeout(function () {
                            $(".woo-nav-tab-wrapper a, .submit input").click(setupUnsavedChangesWarning);
                        }, 0);
                    });

                    setupUnsavedChangesWarning();
                }
            })();

            // Dropdowns
            $trs.trs().dropdown();

            // Descriptions
            $trs.on('click', '.hint .handle', function (e) {

                e.preventDefault();

                $(this).closest('.hint')
                    .find('.content')
                    .slideToggle('fast')
                    .end()
                    .toggleClass('expanded');
            });

            // Sortable tree
            $trs.find('.rules > ul').nestedSortable({
                handle: '.header',
                items: '.rule-item',
                toleranceElement: '> .content',
                listType: 'ul',
                cursor: 'move',
                protectRoot: true,
                isTree: true,
                distance: 10,
                collapsedClass: 'collapsed',
                expandedClass: 'expanded',
                branchClass: "has-children",
                leafClass: "no-children",
                placeholder: 'placeholder',
                disableNestingClass: false,
                forcePlaceholderSize: true,
                list: '<ul class="rule-list"></ul>',

                helper: function (e, item) {
                    var _ractive = item[0]._ractive;
                    _ractive.root.justDo(function () {
                        this.modify(_ractive.keypath.str + '._view', function (view) {
                            return updateView(view, 'dragging', true);
                        });
                    });

                    $(this).sortable('refreshPositions', false);

                    return $(item).clone();
                },

                stop: function (e, ui) {
                    var ractive = ui.item[0]._ractive.root;

                    var source;
                    {
                        var keypath = ui.item[0]._ractive.keypath;
                        source = {
                            keypath: keypath.str,
                            parent: keypath.parent.str,
                            position: +keypath.lastKey
                        };
                    }

                    var destination;
                    {
                        var parent = $(ui.item).parent()[0];
                        if (parent._ractive) {
                            destination = {
                                keypath: parent._ractive.keypath.str,
                                position: $(parent).children().index(ui.item)
                            }
                        } else {
                            var grandparent = $(parent).closest('.rule-item')[0];
                            destination = {
                                keypath: grandparent._ractive.keypath.str + '.children',
                                position: 0
                            }
                        }
                    }

                    var removeAfter;
                    {
                        var sourcepath = source.keypath.split('.');
                        var destpath = (destination.keypath + '.' + destination.position).split('.');
                        if (sourcepath.length == destpath.length) {
                            removeAfter = false;
                        } else {
                            var i = 0;
                            while (sourcepath[i] == destpath[i]) {
                                i++;
                            }
                            removeAfter = (+sourcepath[i] < +destpath[i]);
                        }
                    }

                    var remove = function (ractive, after) {
                        if (after == removeAfter) {
                            ractive.remove(source.keypath);
                        }
                    };

                    $(this).sortable('cancel');
                    $(this).find('.rule-list:empty').remove();

                    var data = ractive.get(source.keypath);
                    data._view = updateView(data._view, 'dragging', false);

                    ractive.justDo(function () {
                        remove(this, false);
                        this.splice(destination.keypath, destination.position, 0, data);
                        remove(this, true);
                    });
                },

                tree: {
                    hasChildren: function (item) {
                        var _r = $(item).get(0)._ractive;
                        var keypath = _r.keypath.str;
                        return keypath == '' || _r.root.get(keypath).children.length > 0;
                    },

                    expanded: function (item, expand) {
                        var _r = $(item).get(0)._ractive;
                        var kp = _r.keypath.str + '._view';
                        var view = _r.root.get(kp);

                        if (expand !== undefined) {
                            _r.root.justDo(function () {
                                view = updateView(view, 'expanded', !!expand);
                                this.set(kp, view);
                            });
                        }

                        return view.expanded;
                    }
                }
            });
        };

        // Schema required to normalize rules b/c Ractive considers undefined values unchanged which causes stale
        // view sometimes. Also used as a new rule template.
        var childrenCalculatorSchema = JSON.stringify({
            operation: 'add',
            calculator: {calculator: 'children', aggregator: 'sum'}
        });
        var ruleSchema = JSON.stringify({
            meta: {
                enable: '1',
                title: '',
                label: ''
            },
            conditions: {
                meta: {
                    grouping: '',
                    capture: 0
                },
                list: []
            },
            operations: {
                meta: {
                    grouping: ''
                },
                list: [
                    JSON.parse(childrenCalculatorSchema)
                ]
            },
            children: [],
            _view: {
                expanded: true,
                settings: true,
                dragging: false,
                showExpanded: true,
                showSettings: true
            }
        });

        var snippets = {
            'destination-based': {
                title: 'Shipping zones, zip/postal codes',
                subtitle: 'Domestic & international shipping',
                config: {
                    meta: {
                        label: 'destination-based shipping'
                    },
                    operations: {
                        list: [
                            {operation: 'add', calculator: {calculator: 'children', aggregator: 'first'}}
                        ]
                    },
                    children: [
                        {
                            meta: {
                                label: 'USA',
                                title: 'Domestic Shipping'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'destination',
                                        operator: 'intersect',
                                        value: ["US"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '15.25'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'Canada, Mexico',
                                title: 'Ground Shipping'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'destination',
                                        operator: 'intersect',
                                        value: ["CA", "MX"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '22.50'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'UK mainland',
                                title: 'UK Mainland Shipping'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'destination',
                                        operator: 'intersect',
                                        value: ["GB"]
                                    },
                                    {
                                        condition: 'destination',
                                        operator: 'disjoint',
                                        value: ["GB/zip:BT*", "GB/zip:HS*", "GB/zip:IM*", "GB/zip:IV*", "GB/zip:KA27*", "GB/zip:KA28*", "GB/zip:KW*", "GB/zip:PA20 0AA-PA49 9ZZ,PA60 0AA-PA78 9ZZ", "GB/zip:PH17 0AA-PH26 9ZZ,PH34 0AA-PH44 9ZZ,PH49 0AA-PH50 9ZZ", "GB/zip:PO30 0AA-PO40 9ZZ", "GB/zip:TR21 0AA-TR25 9ZZ", "GB/zip:ZE*", "GB/zip:LL58 0AA-LL62 9ZZ,LL64 0AA-LL78 9ZZ"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '32.05'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'UK other',
                                title: 'UK Shipping'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'destination',
                                        operator: 'intersect',
                                        value: ["GB"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '40.45'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'other',
                                title: 'International Shipping'
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '49.95'
                                        }
                                    }
                                ]
                            }
                        }
                    ]
                }
            },
            'weight-based': {
                title: 'Weight-based shipping',
                subtitle: 'Simple rate table',
                config: {
                    meta: {
                        label: 'weight-based shipping',
                        title: 'Weight-based Shipping'
                    },
                    operations: {
                        list: [
                            {operation: 'add', calculator: {calculator: 'children', aggregator: 'first'}}
                        ]
                    },
                    children: [
                        {
                            meta: {
                                label: 'small'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'weight',
                                        operator: 'lt',
                                        value: 1
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '2.50'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'medium'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'weight',
                                        operator: 'lt',
                                        value: 2
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '3.75'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'large'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'weight',
                                        operator: 'lt',
                                        value: 10
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '9.95'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'more'
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '9.95'
                                        }
                                    },
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'weight',
                                            cost: '0.50',
                                            skip: 10
                                        }
                                    }
                                ]
                            }
                        }
                    ]
                }
            },
            'destination-weight-based': {
                title: 'Destination + weight',
                subtitle: 'Weight-based shipping depending on order destination',
                config: {
                    meta: {
                        label: 'destination + weight'
                    },
                    operations: {
                        list: [
                            {operation: 'add', calculator: {calculator: 'children', aggregator: 'first'}}
                        ]
                    },
                    children: [
                        {
                            meta: {
                                label: 'USA',
                                title: 'Domestic Shipping'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'destination',
                                        operator: 'intersect',
                                        value: ["US"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {operation: 'add', calculator: {calculator: 'children', aggregator: 'first'}}
                                ]
                            },
                            children: [
                                {
                                    meta: {
                                        label: 'small'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 1
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '2.50'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'medium'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 2
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '3.75'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'large'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 10
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '9.95'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'more'
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '9.95'
                                                }
                                            },
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'weight',
                                                    cost: '0.50',
                                                    skip: 10
                                                }
                                            }
                                        ]
                                    }
                                }
                            ]
                        },
                        {
                            meta: {
                                label: 'Canada, Mexico',
                                title: 'Ground Shipping'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'destination',
                                        operator: 'intersect',
                                        value: ["CA", "MX"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {operation: 'add', calculator: {calculator: 'children', aggregator: 'first'}}
                                ]
                            },
                            children: [
                                {
                                    meta: {
                                        label: 'small'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 1
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '3.50'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'medium'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 2
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '4.75'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'large'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 10
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '10.95'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'more'
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '10.95'
                                                }
                                            },
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'weight',
                                                    cost: '1.50',
                                                    skip: 10
                                                }
                                            }
                                        ]
                                    }
                                }
                            ]
                        },
                        {
                            meta: {
                                label: 'other',
                                title: 'International Shipping'
                            },
                            operations: {
                                list: [
                                    {operation: 'add', calculator: {calculator: 'children', aggregator: 'first'}}
                                ]
                            },
                            children: [
                                {
                                    meta: {
                                        label: 'small'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 1
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '6.50'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'medium'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 2
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '7.75'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'large'
                                    },
                                    conditions: {
                                        list: [
                                            {
                                                condition: 'weight',
                                                operator: 'lt',
                                                value: 10
                                            }
                                        ]
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '13.95'
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    meta: {
                                        label: 'more'
                                    },
                                    operations: {
                                        list: [
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'const',
                                                    value: '13.95'
                                                }
                                            },
                                            {
                                                operation: 'add',
                                                calculator: {
                                                    calculator: 'weight',
                                                    cost: '4.50',
                                                    skip: 10
                                                }
                                            }
                                        ]
                                    }
                                }
                            ]
                        }
                    ]
                }
            },
            'conditional-free': {
                title: 'Free shipping over a certain subtotal',
                subtitle: 'Conditional free shipping',
                config: {
                    meta: {
                        label: 'Free shipping over a certain subtotal'
                    },
                    operations: {
                        list: [
                            {operation: 'add', calculator: {calculator: 'children', aggregator: 'first'}}
                        ]
                    },
                    children: [
                        {
                            meta: {
                                label: 'free',
                                title: 'Free Shipping'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'price',
                                        operator: 'gt',
                                        value: 100
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'free'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'paid',
                                title: 'Paid Shipping'
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: 25
                                        }
                                    }
                                ]
                            }
                        }
                    ]
                }
            },
            'multiple-options': {
                title: 'Multiple shipping options',
                subtitle: 'Express & regular shipping',
                config: {
                    meta: {
                        label: 'multiple shipping options'
                    },
                    operations: {
                        list: [
                            {operation: 'add', calculator: {calculator: 'children', aggregator: 'all'}}
                        ]
                    },
                    children: [
                        {
                            meta: {
                                label: 'regular',
                                title: 'Regular Shipping'
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '9.50'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'express',
                                title: 'Express Shipping'
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '15.80'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'express with insurance',
                                title: 'Express (insured)'
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '19.95'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'local pickup',
                                title: 'Local Pickup'
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'free'
                                        }
                                    }
                                ]
                            }
                        }
                    ]
                }
            },
            'class-based': {
                title: 'Shipping classes',
                subtitle: 'Class-based shipping',
                config: {
                    meta: {
                        label: 'class-based shipping',
                        title: 'Class-based Shipping'
                    },
                    operations: {
                        list: [
                            {operation: 'add', calculator: {calculator: 'children', aggregator: 'sum'}}
                        ]
                    },
                    children: [
                        {
                            meta: {
                                label: 'posters'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'terms',
                                        operator: 'any',
                                        value: ["shipping_class:19"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '3.50'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 't-shirts'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'terms',
                                        operator: 'any',
                                        value: ["shipping_class:18"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '7.50'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'hoodies'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'terms',
                                        operator: 'any',
                                        value: ["shipping_class:20"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '9.50'
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            meta: {
                                label: 'other'
                            },
                            conditions: {
                                list: [
                                    {
                                        condition: 'terms',
                                        operator: 'no',
                                        value: ["shipping_class:18", "shipping_class:19", "shipping_class:20"]
                                    }
                                ]
                            },
                            operations: {
                                list: [
                                    {
                                        operation: 'add',
                                        calculator: {
                                            calculator: 'const',
                                            value: '15.90'
                                        }
                                    }
                                ]
                            }
                        }
                    ]
                }
            }
        };

        var updateView = function (view, property, value) {
            view[property] = value;
            view.showExpanded = view.expanded && !view.dragging;
            view.showSettings = view.settings && !view.dragging;
            return view;
        };

        var triggerHtml5Validation = function () {
            var form = $form[0];
            if (form.checkValidity && !form.checkValidity()) {
                $form.find('[type=submit]').click();
                return false;
            }

            return true;
        };

        var Rule = {
            map: function (rule, callback) {
                rule = callback(rule);
                rule.children = $.map(rule.children || [], function (rule) {
                    return Rule.map(rule, callback);
                });
                return rule;
            },

            normalize: function (rule) {
                return Rule.map(rule, function (rule) {
                    return $.extend(true, {}, JSON.parse(ruleSchema), rule);
                });
            },

            receive: function (rule) {
                return Rule.map(Rule.normalize(rule), function (rule) {

                    // Conditions
                    {
                        rule.conditions.list = $.map(rule.conditions.list, function (condition) {

                            if (condition.condition == 'terms') {
                                condition.subcondition = Rule.items.wrap(condition.subcondition || {}, 'condition');
                            }

                            condition = Rule.items.wrap(condition, 'condition');

                            return condition;
                        });
                    }

                    // Operations
                    {
                        var operations = rule.operations.list;

                        // Make sure a children calculator exists
                        {
                            var childrenCalculatorFound = false;
                            for (var i = 0; i < operations.length; i++) {
                                var operation = operations[i];
                                if (operation.operation == 'add' && get(operation, 'calculator.calculator') == 'children') {
                                    childrenCalculatorFound = true;
                                    break;
                                }
                            }

                            if (!childrenCalculatorFound) {
                                operations.push(JSON.parse(childrenCalculatorSchema));
                            }
                        }

                        operations = $.map(operations, function (operation) {

                            operation = Rule.items.wrap(operation, 'operation');

                            var parameters = operation[operation.operation];
                            if (parameters.calculator) {
                                parameters.calculator = Rule.items.wrap(parameters.calculator, 'calculator');
                            }

                            return operation;
                        });

                        rule.operations.list = operations;
                    }

                    return rule;
                });
            },

            release: function (rule) {
                return Rule.map(clone(rule), function (rule) {

                    rule.conditions.list = $.map(rule.conditions.list, function (condition) {

                        condition = Rule.items.flatten(condition, 'condition');

                        if (condition.condition == 'terms') {
                            condition.subcondition = Rule.items.flatten(condition.subcondition, 'condition');
                        }

                        return condition;
                    });

                    rule.operations.list = $.map(rule.operations.list, function (operation) {
                        operation = Rule.items.flatten(operation, 'operation');

                        if (operation.calculator) {
                            operation.calculator = Rule.items.flatten(operation.calculator, 'calculator');
                        }

                        return operation;
                    });

                    return rule;
                });
            },

            items: {
                wrap: function (item, property) {
                    var parameters = $.extend({}, item);
                    delete parameters[property];

                    var wrapped = {};
                    wrapped[property] = item[property];
                    wrapped[wrapped[property]] = parameters;

                    return wrapped;
                },

                flatten: function (item, property) {
                    var flat = {};
                    flat[property] = item[property];
                    flat = $.extend(flat, item[item[property]]);
                    return flat;
                }
            }
        };

        var clone = function (object) {
            return JSON.parse(JSON.stringify(object));
        };

        var get = function (object, path) {

            if (object === null || object === undefined ||
                path === undefined || path === null) {
                return false;
            }

            $.each(('' + path).split('.'), function (idx, part) {
                if (object === undefined || object === null) {
                    return false;
                }

                object = object[part];
            });

            return object;
        };

        var $trs = $('.trs');
        var $form = $trs.closest('form');
        var $ruleJsonInput = $form.find(':input[name$="_rule"]');

        var rootRule;
        {
            try {
                var ruleJson = $ruleJsonInput.val();
                if (ruleJson) {
                    rootRule = JSON.parse(ruleJson);
                }
            } catch (e) {
                console.error(e);
            }
        }

        $form.find('.form-table').toggle(!!(rootRule && rootRule.children && rootRule.children.length > 0));

        init();
    });

// Plugins
    (function ($) {
        "use strict";

        $.fn.trs = function () {
            return $.extend(this, $.fn.trs);
        };

        $.each({
            dropdown: $.extend(function (options, klass) {
                    options = $.extend({
                        container: '.dropdown',
                        handle: '.dropdown-handle',
                        menu: '.dropdown-menu'
                    }, options);

                    klass.init();

                    return this.on('click', options.handle, function (e) {
                        e.preventDefault();
                        klass.toggle($(this).closest(options.container));
                    });
                },
                {
                    $opened: $(),
                    inited: false,

                    init: function () {
                        if (this.inited) return;
                        this.inited = true;

                        var self = this;

                        $(document).on('mouseup', function () {
                            var $opened = self.$opened;
                            $opened.data('trs-dont-toggle', $opened.hasClass('open'));
                            $opened.removeClass('open');
                        });
                    },

                    toggle: function ($this) {
                        if ($this.data('trs-dont-toggle')) {
                            $this.data('trs-dont-toggle', false);
                            return;
                        }

                        $this.toggleClass('open');

                        if ($this.hasClass('open')) {
                            this.$opened.data('trs-dont-toggle', false);
                            this.$opened = $this;
                        }
                    }
                })
        }, plugin);

        function plugin(name, method) {
            $.fn.trs[name] = function (options) {
                var pluginMeta = $.extend({
                    singleton: true,
                    chained: true
                }, method.__plugin);

                var $elements = this;
                if (pluginMeta.singleton) {
                    var dataKey = 'trs.plugin.' + name;
                    $elements = $elements.not(':data(' + dataKey + ')').data(dataKey, true);
                }

                var result = method.call($elements, options || {}, method);

                return pluginMeta.chained ? this : result;
            }
        }
    })(jQuery);
}