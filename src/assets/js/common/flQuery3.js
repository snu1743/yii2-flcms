(function (global, factory) {

    "use strict";

    if (typeof module === "object" && typeof module.exports === "object") {

        module.exports = global.document ?
            factory(global, true) :
            function (w) {
                if (!w.document) {
                    throw new Error("jQuery requires a window with a document");
                }
                return factory(w);
            };
    } else {
        factory(global);
    }

})(typeof window !== "undefined" ? window : this, function (window, noGlobal) {

    "use strict";

    window.FLBUFFER = {};
    window.FLBUFFERBACKUP = {};

    let isFunction = function isFunction(obj) {
        return typeof obj === "function" && typeof obj.nodeType !== "number";
    };

    var
        version = "1.0.0",

        FL_QUERY = function (selector, context) {
            return new FL_QUERY.fn.init(selector, context);
        };

    FL_QUERY.fn = FL_QUERY.prototype = {

        flmquery: version,

        constructor: FL_QUERY

    };

    FL_QUERY.extend = FL_QUERY.fn.extend = function () {
        let options, name, src, copy, copyIsArray, clone,
            target = arguments[0] || {},
            i = 1,
            length = arguments.length,
            deep = false;

        if (typeof target === "boolean") {
            deep = target;

            target = arguments[i] || {};
            i++;
        }

        if (typeof target !== "object" && !isFunction(target)) {
            target = {};
        }

        if (i === length) {
            target = this;
            i--;
        }

        for (; i < length; i++) {

            if ((options = arguments[i]) != null) {

                for (name in options) {
                    copy = options[name];

                    if (name === "__proto__" || target === copy) {
                        continue;
                    }

                    if (deep && copy && (FL_QUERY.isPlainObject(copy) ||
                        (copyIsArray = Array.isArray(copy)))) {
                        src = target[name];

                        if (copyIsArray && !Array.isArray(src)) {
                            clone = [];
                        } else if (!copyIsArray && !FL_QUERY.isPlainObject(src)) {
                            clone = {};
                        } else {
                            clone = src;
                        }
                        copyIsArray = false;

                        target[name] = FL_QUERY.extend(deep, clone, copy);

                    } else if (copy !== undefined) {
                        target[name] = copy;
                    }
                }
            }
        }

        return target;
    };

    FL_QUERY.extend({

        request: function(data) {
            if(typeof data.form !== "undefined" && data.in_sync === true) {
                this.sendFormInSync(data);
            } else if(typeof data.form !== "undefined") {
                this.sendForm(data);
            }
        },

        sendForm: function(data) {
            let form = data.form[0];
            let callback = data.callback;
            if(typeof(data.callback) === "function") {
                callback = data.callback;
            }
            let formData = new FormData(form);
            let onlyValidate = 0;
            $.ajax({
                url: data.form.attr('action'),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-Api-Key': this.getAuthToken()
                },
                success: function (response) {
                    FLBUFFER.form = null;
                    FLBUFFER.response = null;
                    FLBUFFER.form = form;
                    console.log(response);
                    if(typeof response === "string" && response.length > 0) {
                        FLBUFFER.response = JSON.parse(response);
                    } else {
                        FLBUFFER.response = response;
                    }
                    if (response.status === "failed")
                    {
                        callback.errors(response, data.config);
                    }
                    if (onlyValidate !== 1 && response.status === "success") {
                        if(typeof callback.before === "function") {
                            callback.before(response, data.config);
                        }
                        if(typeof callback.before === "object") {
                            for(let fn in callback.before) {
                                if(typeof callback.before[fn] === "function") {
                                    callback.before[fn](response, data.config);
                                }
                            }
                        } 
                       // callback.success(response, data.config);
                        if(typeof callback.success === "function") {
                            callback.success(response, data.config);
                        }
                        if(typeof callback.success === "object") {
                            for(let fn in callback.success) {
                                if(typeof callback.success[fn] === "function") {
                                    callback.success[fn](response, data.config);
                                }
                            }
                        }
                        if(typeof callback.after === "function") {
                            callback.after(response, data.config);
                        }
                        if(typeof callback.after === "object") {
                            for(let fn in callback.after) {
                                if(typeof callback.after[fn] === "function") {
                                    callback.after[fn](response, data.config);
                                }
                            }
                        } 
                    }
                },
                 error: function (xhr, ajaxOptions, thrownError) {
                     console.error([xhr.status + ' (' + thrownError + ')']);
                 }
            });
            return this;
        },

        authorization: function() {
            let elem = {};
            elem.config = {
                entity: "security",
                action_name: "authorization"
            }
            FL_ACTIONS.action(elem, true)
            return false;
        },

        getResponse: function() {
            return FLBUFFER.response;
        },

        getAuthToken: function() {
            return this.getCookie('e_token');
        },

        getCookie: function(name) {
            let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }
    });

    let init = FL_QUERY.fn.init = function (selector) {
        this[0] = selector;
        return selector;
    };

    init.prototype = FL_QUERY.fn;


    if (typeof define === "function" && define.amd) {
        define("flmquery", [], function () {
            return FL_QUERY;
        });
    }

    let _FL_QUERY = window.FL_QUERY,

        _FLQ = window.FLQ;

    FL_QUERY.noConflict = function (deep) {
        if (window.FLQ === FL_QUERY) {
            window.FLQ = _FLQ;
        }

        if (deep && window.FL_QUERY === FL_QUERY) {
            FL_QUERY = _FL_QUERY;
        }

        return FL_QUERY;
    };

    if (!noGlobal) {
        window.FL_QUERY = window.FLQ = FL_QUERY;
    }


   $(document).ready(function () {

//            $(this).flLocalization("init");
//
//            $(this).flSession("init");
//
//            $(this).flUser("set");
//
//            $(this).flBuffer("init");

       let event = new CustomEvent("flQueryReady", {
           detail: {name: ""}
       });

       document.dispatchEvent(event);
   });

    return FL_QUERY;
});