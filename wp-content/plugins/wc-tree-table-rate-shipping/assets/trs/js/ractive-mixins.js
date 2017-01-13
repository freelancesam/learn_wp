Ractive.prototype.keypath = function(keypath) {
    if (keypath.hasOwnProperty('fullname')) {
        return keypath;
    }

    var ractive = this;

    keypath += '';

    var lastDot = keypath.lastIndexOf('.');
    var parentFullname = keypath.substr(0, lastDot);
    var basename = keypath.substring(lastDot + 1);
    var parent;

    return {
        fullname: keypath,
        basename: basename,

        parent: function() {
            if (parent === undefined) {
                parent = parentFullname ? ractive.keypath(parentFullname) : null;
            }
            return parent;
        },

        join: function(joinWith) {
            joinWith = ractive.keypath(joinWith);
            return ractive.keypath(
                this.fullname +
                ((this.fullname !== '' && joinWith.fullname !== '') ? '.' : '') +
                joinWith.fullname
            );
        }
    };
};

Ractive.prototype.remove = function(keypath) {
    keypath = this.keypath(keypath);

    var parentKeypath = (keypath.parent() || {}).fullname || undefined;
    var parent = this.get(parentKeypath);
    if (parent === undefined) {
        return;
    }

    if (typeof(parent.splice) === 'function') {
        parent.splice(keypath.basename, 1);
    } else {
        delete parent[keypath.basename];

        if (parentKeypath !== undefined) {
            this.set(parentKeypath, parent);
            this.update(parentKeypath);
        } else {
            this.set(parent);
            this.update(parentKeypath);
        }
    }
};

Ractive.prototype.justDo = function(callback) {
    var bkp = this.transitionsEnabled;
    this.transitionsEnabled = false;

    try {
        callback.call(this);
    } catch (e) {
        this.transitionsEnabled = bkp;
        throw e;
    }

    this.transitionsEnabled = bkp;
};

Ractive.prototype.modify = function(keypath, callback) {
    if (keypath.hasOwnProperty('fullname')) {
        keypath = keypath.fullname;
    }

    this.set(keypath, callback(this.get(keypath)));
};

Ractive.prototype.componentKeypath = function(component) {
    var fragment = component.parentFragment;
    while (fragment && fragment.context == null) {
        fragment = fragment.parent;
    }

    if (fragment) {
        return fragment.context;
    }
};