Ractive.decorators.combine = function(node, decoratorName) {

    var ractive = this;

    var decorators = [];
    decoratorName.forEach(function (decoratorName) {

        var decorator = ractive.decorators[decoratorName].call(ractive, node);
        if (!decorator || !decorator.teardown) {
            throw new Error('Decorator definition "' + decoratorName + '" must return an object with a teardown method');
        }

        decorators.push(decorator)
    });

    return {
        teardown: function() {
            decorators.forEach(function(decorator) {
                decorator.teardown();
            });
        }
    };
};