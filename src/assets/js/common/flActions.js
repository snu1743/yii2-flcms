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

    let isFunction = function isFunction(obj) {
        return typeof obj === "function" && typeof obj.nodeType !== "number";
    };

    let version = "1.0.0",

        FL_ACTIONS = function (selector, context) {
            return new FL_ACTIONS.fn.init(selector, context);
        };

    FL_ACTIONS.fn = FL_ACTIONS.prototype = {

        flmquery: version,

        constructor: FL_ACTIONS

    };

    FL_ACTIONS.extend = FL_ACTIONS.fn.extend = function () {
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

                    if (deep && copy && (FL_ACTIONS.isPlainObject(copy) ||
                        (copyIsArray = Array.isArray(copy)))) {
                        src = target[name];

                        if (copyIsArray && !Array.isArray(src)) {
                            clone = [];
                        } else if (!copyIsArray && !FL_ACTIONS.isPlainObject(src)) {
                            clone = {};
                        } else {
                            clone = src;
                        }
                        copyIsArray = false;

                        target[name] = FL_ACTIONS.extend(deep, clone, copy);

                    } else if (copy !== undefined) {
                        target[name] = copy;
                    }
                }
            }
        }

        return target;
    };

    FL_ACTIONS.extend({

        editMod: 0,
        actionIdList: [],
        tmp: {},

        action: function(config = false) {
            if(!config) {
                this.getConfig();
            } else {
                this.performActions(config);
            }
            return this;
        },

        performActions: function(config) {
            let func;
            let params;
            if( typeof config.current["action"] === "object" && typeof config.current["action"][0]  === "string") {
                func = config.current["action"][0];
                if( typeof config.current["action"][1]  === "object") {
                    params = this.performActionGetParams(config.current["action"][1], config);
                    this[func](config, params);
                } else {
                    this[func]();
                }
            }
            return this;
        },

        performActionGetParams: function(params, config) {
            for(let i in params) {
                if(params[i] === "_config") {
                    params[i] = config;
                }
            }
            return params;
        },

        getDefaulConfig: function() {
            let config = {};
            config.current = {};
            config.current.element_type = "form";
            config.current.element_id = "#fl-action-form-default";
            config.current.form = {};
            config.current.form.action = "/fl/cms/action";
            config.current.form.body = ".card-body";
            return config;
        },

        getConfigLocal: function() {
            let config = this.getDefaulConfig();
            this.sendForm(config);
            return this;
        },

        /**
         * Get config
         * TODO-docs ?????????????? ???????????? ???? ???????? ???????????????????? ??????????????. ?????????????????? ?? ???????? ???????????????????? ???????????? 
         * ???? ?????????????? ????????????????????(FLA.config.current_local, FLA.config.current, FLA.config.entities[config.current.entity]).
         * ?????????????????? config ?? actionConfig, ?????? ???????????????????? ?????? ???? ???? ???????????????????? ???????????? ?????????? ????????????.
         * ???????? ???????????? ?? ?????????????? ???? ???????????????? ?? ????????, ???? ???????????? ???????????? ???? ????????????. ?? ?????????????????? ????????????,
         * ?????????????????? setConfig.
         * @returns {this}
         */
        getConfig: function() {
            let config = this.getDefaulConfig();
            config.current.entity = FLA.config.current.entity;
            config.current.action = FLA.config.current.action_name;
            config.current.form.action = "/fl/cms/get-config";
            config.current.callback = {};
            let actionConfig = {};
            /**
             * TODO-config_cache ?????????????????? ???????????????? ???? ?????? ???????????? ?? ??????????????. ???????? ????, ???? ???????????????????? ??????. ???? ????????????????????????.
             * ?????????????????????? ??????????????????????.
             */
            if(typeof FLA.config.entities !== "undefined" && typeof FLA.config.entities[config.current.entity] !== "undefined") {
                console.log("???????????????????? " + config.current.entity);
                actionConfig.current = _.cloneDeep(FLA.config.current);
                actionConfig.current_remote = FLA.config.entities[config.current.entity];
                actionConfig.current_local = _.cloneDeep(FLA.config.current_local);
                config.current.callback.success = function(){
                    FLA.setConfig(actionConfig);
                };
            } else {
                console.log(FLA.config.current);
                actionConfig.current = _.cloneDeep(FLA.config.current);
                actionConfig.current_local = _.cloneDeep(FLA.config.current_local);
                config.current.callback.success = function(){
                    FLA.setConfig(actionConfig);
                };
                config.current.callback.errors = function(e){
                    console.log(e);
                };
                config.current.form.fields = {
                    entity : {type : "hidden", value : FLA.config.current.entity},
                    action_name : {type : "hidden", value : FLA.config.current.action_name},
                    only_config : {type : "hidden", value : true}
                };
                if(typeof FLA.config.current.form.values !== 'undefined') {
                    if(typeof FLA.config.current.form.values.e_entity_class_id  === 'string') {
                        config.current.form.fields.e_entity_class_id = {type : "hidden", value : FLA.config.current.form.values.e_entity_class_id};
                    }
                }
                config.current.element = this.createElement(config);
                this.sendForm(config);
            }
            return this;
        },
        
        /**
         * Set config
         * TODO-docs ?????????????????? ?? ?????? ?????????????? (FLA.config.entities), ???????????? ?? ??????????????(response.config).
         * ?????????????? ???????? ???????? ???? ????????????????????????, ?????????? ???????????????? ?????????? ?????????????? ?????????????????????? ??????????.
         * ???????????????????? ?????????????? ????????????(current). ?? ???????????? ???????????? ?? ???????? ???????????? ???? ?????????????? ?? ??????????????, ?????????? ????????????
         * ???????????????? ???? ???????????????????? ??????????????(current_local). ???????? ???????????????? ?????????????? ?? ?????????????? ?? ???????????????????? ??????????????????,
         * ???? ?????? ???????????????????????????? ????????????????????. ???????????? ?????????????????? ???????????? ???????????????????????? ?????????????? ?? ??????????????.
         * @param {Object} config
         * @returns {void}
         */
        setConfig: function(config) {
            let key;
            let funcCreate;
            let funcAssigning;
            let funcCheck;
            let path;
            let response;
            if(typeof config.entities === "undefined") {
                FLA.config.entities = {};
            }
            /**
             * TODO-config_cache ???????? ???????????? ?? ?????????????? ?????? ???? ????????????????, ???? ??????????????????. ???? ????????????????????????.
             * ?????????????????????? ??????????????????????.
             */
            if(typeof FLA.config.entities[config.current_local.entity] === "undefined") {
                response = FLQ.getResponse();
                FLA.config.entities[config.current_local.entity] = response.config;
            }
            config.current_remote = response.config[config.current_local.action_name];
            for (key in config.current_remote) {
                config.current[key] = config.current_remote[key];
            }
            config.current.common = {};
            if(typeof response.config.common !== "undefined") {
                for (key in response.config.common) {
                    
                    config.current.common[key] = response.config.common[key];
                }
            }
            for (key in config.current_local) {
                
                path = '';
                key.split(".").forEach(function(p){
                    path = path + "." + p;
                    funcCheck = new Function("config", "return config.current" + path);
                    if(funcCheck(config) === undefined) {
                        funcCreate = new Function("config", "config.current" + path + "= {}");
                        funcCreate(config);
                    }
                });
                funcAssigning = new Function("config", "value", "config.current" + path + "= value");
                funcAssigning(config, config.current_local[key]);
                path = '';
            }
            config.current.form.action = "/fl/cms/action";
            config.current.form.body = ".card-body";
            config.current.element = this.createElement(config);
            config["action_" + config.current.action_id]= config.current;
            this.action(config);
        },
        
        form: function(config, params) {
            
            switch (params[0]) {
            case "send":
                this.sendForm(config);
                break;
            case "modal":
                    this.CreateModalWithForm(config);
                break;
            case "elem":
                    this.CreateElemWithForm(config);
                break;
            default:
                console.error( "?????? ?????????? ????????????????" );
            }
        },
        
        
        app: function(config, params) {
            
            switch (params[0]) {
            case "aceEditor":
                console.log(config)
                console.log(params)
                FLA.createAceEditor([], config);
                break;
            default:
                console.error( "?????? ?????????? ????????????????" );
            }
        },
        
        CreateElemWithForm: function(config) {
            console.log( config );
        },

        sendForm: function(config) {
            FLQ.request({form: config.current.element, callback: this.getCallback(config), config: config});
        },

        getCallback: function(config) {
            let $this = this;
            if(typeof config.current.callback === "undefined") {
                config.current.callback = {};
                config.current.callback.success = this.getCallbackConsole();
                config.current.callback.errors = this.getCallbackConsole();
            }
            if(typeof config.current.callback.success === "undefined") {
                config.current.callback.success = this.getCallbackConsole();
            }
            if(typeof config.current.callback.errors === "undefined") {
                config.current.callback.errors = this.getCallbackConsole();
            }
            if(typeof config.current.callback.success === "object") {
                config.current.callback.success = this.getCallbackFromObject(config.current.callback.success);
            }
            if(typeof config.current.callback.errors === "object") {
                config.current.callback.errors = this.getCallbackFromObject(config.current.callback.errors);
            }
            if(typeof config.current.callback.success === "string") {
                config.current.callback.success = this.getCallbackFromString(config.current.callback.success);
            }
            if(typeof config.current.callback.errors === "string") {
                config.current.callback.errors = this.getCallbackFromString(config.current.callback.errors);
            }
            return  config.current.callback;
        },

        getCallbackFromString: function(callback) {
            if(typeof callback === "string") {
                callback = "getCallback" + callback[0].toUpperCase() + callback.slice(1);
                if(typeof this[callback] === "function") {
                    return this[callback]();
                } else {
                    return this.getCallbackConsole();
                }
            } else {
                return this.getCallbackConsole();
            }
        },

        getCallbackFromObject: function(data) {
            let callback;
            let params = data[1] ? data[1]: null;
            if(typeof data[0] === "string") {
                callback = data[0];
                callback = "getCallback" + callback[0].toUpperCase() + callback.slice(1);
                if(typeof this[callback] === "function") {
                    return this[callback](params);
                } else {
                    return this.getCallbackConsole(params);
                }
            } else {
                return this.getCallbackConsole(params);
            }
        },

        getCallbackAlert: function(param) {
            return function (r, c) {
                alert(r, c);
            }
        },

        getCallbackReload: function(param) {
            return function (r, c) {
                FLA.entityReload(r, c);
            }
        },

        getCallbackPageReload: function(param) {
            return function (r, c) {
                location.reload();
            }
        },

        getCallbackConsole: function(param) {
            return function (r, c) {
                console.log(r, c);
            }
        },
        
        getCallbackJsgrid: function(param) {
            return function (r, c) {
                FLA.createJsGrid(r, c);
            }
        },
        getCallbackForm: function(param) {
            return function (r, c) {
                //???????? ???? ????????????????????????
                console.log(r);
                console.log(c);
            }
        },

        getCallbackAceEditor: function(param) {
            return function (r, c) {
                FLA.createAceEditor(r, c);
            }
        },

        getCallbackJsonEditor: function(param) {
            return function (r, c) {
                FLA.createJsonEditor(r, c);
            }
        },

        entityReload: function (response, config) {
            $(".fl-app").each(function(){
                let elem = $(this);
                if(config.current.entity === elem.attr("data-string__entity") || config.current.entity === elem.attr("data-string__instance")) {
                    elem.attr("data-string__action_id", FLA.setActionId());
                    FLA.setLocalDataAction(elem.data(), this).action();
                }
            });
        },
        
        buildJsonEditor: function(response, config, appConf, workspace) {
            let id = appConf.id + "-app";
            let idRight = appConf.id + "-app-right";
            $("#" + appConf.id).find(".jsoneditor-app").empty().attr("id", id);
            $("#" + appConf.id).find(".jsoneditor-app-right").empty().attr("id", idRight);
            const container = document.getElementById(id);
            const containerRight = document.getElementById(idRight);
            const options = {
                modes: ['tree', 'text'],
                onCreateMenu: function (items, node) {
                    const path = node.path
                    // log the current items and node for inspection
                    console.log('items:', items, 'node:', node)
                    function pathTojq() {
                        let pathString = ''

                        path.forEach(function (segment, index) { // path is an array, loop through it
                            if (typeof segment == 'number') {  // format the selector for array indexs ...
                                pathString += '[' + segment + ']'
                            } else {  // ... or object keys
                                pathString += '."' + segment + '"'
                            }
                        })

                        alert(pathString) // show it to the user.
                    }
                    if (path) {
                        items.push({
                            text: 'jq Path', // the text for the menu item
                            title: 'Show the jq path for this node', // the HTML title attribute
                            className: 'example-class', // the css class name(s) for the menu item
                            click: pathTojq // the function to call when the menu item is clicked
                        })
                    }
                    items.forEach(function (item, index, items) {
                        if ("submenu" in item) {
                            items[index].className += ' submenu-highlight'
                        } else {
                            items[index].className += ' rainbow'
                        }
                    })
                    items = items.filter(function (item) {
                        return item.type !== 'separator'
                    })
                    return items
                }
            }
            const editor = new JSONEditor(container, options);
            //TODO-dual-editor ?????????????????????? ?????????????? ????????????????
            // const editorRight = new JSONEditor(containerRight, options)
            editor.set(response.properties[0]['content']);
            this.createJsonEditorMenu(appConf, config, workspace, editor, response);
        },

        createJsonEditorWorkspace: function(appConf, config, workspace = null) {
            let template;
            if(typeof appConf.template === "string") {
                //TODO-json-editor ???????????????????????? ?????????????????? ????????????. ???? ??????????????????????????????.
                template = $(appConf.template).clone();
                template.find(".json-template-card:first").attr("id", appConf.id);
                template.find(".app-title:first").empty().append(appConf.title);
            } else {
                template = $(".fl-action-tmp:first").find(".json-template-card:first").clone();
                template.find(".app-title:first").empty().append(appConf.title);
                template.attr("id", appConf.id);
            }
            if( workspace !== null) {
                workspace.empty();
                workspace.append(template);
            } else if(typeof appConf.parent === "undefined" && config.current.init_elem) {
                workspace = $(config.current.init_elem);
                workspace.empty();
                workspace.append(template);
            }
            return workspace;
        },

        createJsonEditorMenuAction: function (actionData, editor, response) {
            actionData["string__form.values.content"] = JSON.stringify(editor.get(), null, 2);
            actionData["string__form.values.params"] = response.properties[0]['params'];
            FLA.setLocalDataAction(actionData).action();
            console.log(response);
        },

        createJsonEditorMenu: function(appConf, config, workspace, editor, response) {
            let templatemenuItem = workspace.find(".menu-items-template:first");
            let menu = workspace.find(".jsoneditor-app-menu:first");
            let $this = this;
            let item;
            let key;
            let callback;
            for(key in appConf.menu.items) {
                item = templatemenuItem.clone();
                item.removeClass("d-none");
                item.removeClass("menu-items-template");
                item.append(appConf.menu.items[key].name);
                callback = function() {
                    $this.createJsonEditorMenuAction(appConf.menu.items[key].action_data, editor, response);
                }
                item.bind("click", callback);
                menu.append(item);
                workspace.find(".menu-items-template:first").remove();
            }
        },

        createJsonEditor: function(response, config, workspace = null) {
            let appConf = {
                id: "json-editor-" + Math.floor(1000000000 + Math.random() * (9999999999 + 1 - 1000000000)),
                title: ""
            };
            if(typeof config.current.common.app === "string") {
                if(typeof config.current.common[config.current.common.app] === "object") {
                    appConf = config.current.common[config.current.common.app];
                    appConf.id = "json-editor-" + Math.floor(1000000000 + Math.random() * (9999999999 + 1 - 1000000000));
                }
            }
            let initBuild = () => {
                workspace = this.createJsonEditorWorkspace(appConf, config, workspace);
                this.buildJsonEditor(response, config, appConf, workspace);
            };
            initBuild(response, config, appConf);
            return this;
        },
        
        /**
         * ?????????????? ?????????????? ?????????????? ?? ???????????? ?????? ?????????????????? ACE
         * ?????????? ?????????????????? ???????????? ???? ?????????????? ?????????????? ?? ??????????????. ???????? ???? ???? ?????????????????? ??????.
         * ???????? ??????, ???? ?????????????????? ???????????? ???? ??????????????????. ?????????? ???????????????? ?????????????? ??????????????.
         * ???????? ???????????????????? "workspace" ?????????? "null", ???? ???????????????????????? ???????????????? ??????????????
         * "init_elem". ?????????? ?????????????????? "workspace", ?? ???????? ?????????????????????? ????????????.
         * @param {Object} appConf
         * @param {Object} config
         * @param {Object} workspace
         * @returns {Object} workspace
         */
        createAceEditorWorkspace:function(appConf, config, workspace = null) {
            let template;
            if(typeof appConf.template === "string") {
                //TODO-ace-editor ???????????????????????? ?????????????????? ????????????. ???? ??????????????????????????????.
                template = $(appConf.template).clone();
                template.find(".ace-template-card:first").attr("id", appConf.id);
                template.find(".app-title:first").empty().append(appConf.title);
            } else {
                template = $(".fl-action-tmp:first").find(".ace-template-card:first").clone();
                template.find(".app-title:first").empty().append(appConf.title);
                template.attr("id", appConf.id);
            }
            if( workspace !== null) {
                workspace.empty();
                workspace.append(template);
            } else if(typeof appConf.parent === "undefined" && config.current.init_elem) {
                workspace = $(config.current.init_elem);
                workspace.empty();
                workspace.append(template);
            }
            return workspace;
        },
        
        buildAceEditor: function(response, config, appConf, workspace) {
            let id = appConf.id + "-app";
            $("#" + appConf.id).find(".container-code").empty().append('<div id="' + id + '" class="doc-editor-ace"></div>');
            let editorData = ace.edit(id);
            editorData.setTheme(appConf.theme);
            editorData.session.setMode(appConf.mode);
            editorData.setValue(response.properties[0]['content']);
            // editorData.setValue(config.current.content);
            if(typeof appConf.context_menu === "object"){
                this.buildAceEditorContextMenu(response, appConf, editorData);
            }
        },

        /**
         * @param response
         * @param appConf
         */
        buildJsGridContextMenu: function(response, appConf) {
            $.contextMenu({
                selector: '#' + appConf.id + ' .jsgrid-row, #' + appConf.id + ' .jsgrid-alt-row',
                callback: function(key, options) {
                    $(options.$trigger).trigger('click');
                    let item = FLA.tmp.row_click_data.args.item;
                    let actionData = options.items[key]['action_data'];
                    let settings;
                    if(typeof options.items[key]['settings'] === 'object') {
                        settings = options.items[key]['settings'];
                    }
                    let dataKey;
                    for(let key in item) {
                        dataKey = renameProperties(key, item, settings);
                        actionData["string__form.values." + dataKey] = item[key];
                    }
                    function renameProperties(key, item, settings) {
                        if(typeof settings === "object" && typeof settings.rename_properties === "object") {
                            for(let i in settings.rename_properties) {
                                if(i === key) {
                                    return settings.rename_properties[i];
                                }
                            }
                        }
                        return key;
                    }
                    FLA.setLocalDataAction(actionData).action();
                },
                items: appConf.context_menu.items
            });
            $('.context-menu-one').on('click', function(e){
                console.log('clicked', this);
            });
        },

        /**
         * TODO-refactoring ???????????????? ?????????? ?????????? ???????????????????? ?? buildJsGridContextMenu.
         * @param response
         * @param appConf
         */
        buildAceEditorContextMenu: function(response, appConf, editorData) {
            $.contextMenu({
                selector: '#' + appConf.id + ' .container-code',
                callback: function(key, options) {
                    let item = {};
                    item.content = window.btoa(unescape(encodeURIComponent(editorData.getValue())));
                    item.params = response.properties[0]['params'];
                    let actionData = options.items[key]['action_data'];
                    let settings;
                    if(typeof options.items[key]['settings'] === 'object') {
                        settings = options.items[key]['settings'];
                    }
                    let dataKey;
                    for(let key in item) {
                        dataKey = renameProperties(key, item, settings);
                        actionData["string__form.values." + dataKey] = item[key];
                    }
                    function renameProperties(key, item, settings) {
                        if(typeof settings === "object" && typeof settings.rename_properties === "object") {
                            for(let i in settings.rename_properties) {
                                if(i === key) {
                                    return settings.rename_properties[i];
                                }
                            }
                        }
                        return key;
                    }
                    FLA.setLocalDataAction(actionData).action();
                },
                items: appConf.context_menu.items
            });
            $('.context-menu-one').on('click', function(e){
                console.log('clicked', this);
            });
        },
        
        createAceEditor: function(response, config, workspace = null) {

            let appConf = {
                id: "ace-editor-" + Math.floor(1000000000 + Math.random() * (9999999999 + 1 - 1000000000)),
                title: ""
            };
            if(typeof config.current.common.app === "string") {
                if(typeof config.current.common[config.current.common.app] === "object") {
                    appConf = config.current.common[config.current.common.app];
                    appConf.id = "ace-editor-" + Math.floor(1000000000 + Math.random() * (9999999999 + 1 - 1000000000));
                }
            }
            if(appConf.data_source === 'local_data'){
                response = config.current.local_data;
            }
            let initBuild = () => {
                workspace = this.createAceEditorWorkspace(appConf, config, workspace);
                this.buildAceEditor(response, config, appConf, workspace);
            };
            initBuild(response, config, appConf);
            return this;
        },
        
        getCallbackModal: function(params) {
            let callback;
            if(typeof params === "string") {
                callback = params;
            }
            if(typeof params === "object") {
                callback = params[0];
            }
            let callbackList = {
                jsgrid : function(r, c) {
                    let modal = FLA.CreateModal(c);
                    FLA.createJsGrid(r, c, modal.find(".modal-body"));
                    modal.modal('show');
                },
                list : function(r, c) {
                    let modal = FLA.CreateModal(c);
                    FLA.createListProperties(r, c, modal.find(".modal-body"), params[1]);
                    modal.modal('show');
                }
            }
            if(typeof callbackList[callback] === "function") {
                return callbackList[callback];
            }
        },
        
        createListProperties: function(response, config, workspace, params) {
            let template = $(".fl-action-tmp").find(".elem-table-list").clone();
            workspace.append(template);
            if(typeof response.properties[0] === "object") {
                this.createListPropertiesSetTbody(template, response.properties, params);
            }
        },
        
        createListPropertiesSetTbody: function(template, properties, params) {
            let tbody = template.find("tbody");
            let tr = tbody.find("tr").detach();
            let trClone;
            let n = 1;
            for(let i in properties) {
                for(let p in properties[i]) {
                    if(typeof params[p] !== "string") {
                        continue;
                    }
                    trClone = tr.clone();
                    trClone.find("td:eq(0)").text(n++ + ".");
                    trClone.find("td:eq(1)").text(params[p]);
                    trClone.find("td:eq(2)").text(properties[i][p]);
                    tbody.append(trClone);
                }
            }
        },
        
        createJsGrid: function(response, config, workspace = null) {
            let appConf = {
                id: "js-grid-" + Math.floor(1000000000 + Math.random() * (9999999999 + 1 - 1000000000)),
                template: ".grid-template-card:first",
                title: ""
            };
            let initBuild = () => {
                workspace = this.createJsGridWorkspace(appConf, config, workspace);
                this.buildJsGrid(response, config, appConf);
                this.buildJsGridPagingApp(response, config, appConf, workspace);
            };
            if(typeof config.current.common.app === "string") {
                if(typeof config.current.common[config.current.common.app] === "object") {
                    appConf = this.setAppConf(config, response, initBuild);
                }
            }
            if(typeof appConf.context_menu === "object"){
                this.buildJsGridContextMenu(response, appConf);
            }
            if(typeof appConf.context_menu_no_data === "object"){
                this.buildJsGridContextMenuNoData(response, appConf);
            }
            initBuild();
            return this;
        },
        
        /**
         * createJsGridWorkspace
         * TODO-docs ???????????????????? ?????????????? ?????????????????????????? ?????? ???????????????????? jsgrid, ???????????????? ????????????
         * ?? ?????????????????? ???????????? ?? ?????????????? ????????????????????????. ???????? ???????????? ???? ???????????? ?? ??????????????,
         * ?????????? ???????????? ???? ??????????????????. ?????????????????? ?? ???????????? ?????????????? id. ?????????????????? ?? ????????????
         * title ???????? ?? ?????????????? ???????????????????? ???????????????????????? ???????????????? title.
         * @param {Object} appConf
         * @param {Object} config
         * @param {Object} workspace
         * @returns {flActionsL#19.flActionsAnonym$0.createJsGridWorkspace.workspace}
         */
        createJsGridWorkspace:function(appConf, config, workspace = null) {
            let template;
            if(typeof appConf.template === "string") {
                template = $(appConf.template).clone();
                template.find(".app-js-grid:first").attr("id", appConf.id);
                template.find(".app-title:first").empty().append(appConf.title);
            } else {
                template = $(".fl-action-tmp:first").find(".app-js-grid:first").clone();
                template.attr("id", appConf.id);
            }
            if( workspace !== null) {
                workspace.empty();
                workspace.append(template);
            } else if(typeof appConf.parent === "undefined" && config.current.init_elem) {
                workspace = $(config.current.init_elem);
                workspace.empty();
                workspace.append(template);
            }
            return workspace;
        },
        
        setAppConf: function(config, response, initBuild) {
            let appConf = config.current.common[config.current.common.app];
            let properties = response.properties;
            for(let key in appConf.settings.fields) {
                if( typeof appConf.settings.fields[key].type !== "string") {
                    continue;
                }
                switch (appConf.settings.fields[key].type) {
                    case 'select_from_properties':
                        this.setSelectFromProperties(appConf.settings.fields[key], properties);
                    break;
                    case 'select_from_entity':
                        this.setSelectFromEntity(appConf.settings.fields[key], appConf, initBuild);
                    break;
                }
            }
            appConf.id = config.current.entity + '-' + Math.floor(1000000000 + Math.random() * (9999999999 + 1 - 1000000000));
            return appConf;
        },
        
        setSelectFromProperties: function(field, properties) {
            field.type = "select";
            field.items = properties;
            return field;
        },
        
        setSelectFromEntity: function(field, appConf, initBuild) {
            let data = {
                set items(value) {
                    field.items= value;
                    initBuild();
                }
            };
            field.params['function__callback.success'] = function(r) {
                data.items = r.properties;
            };
            field.items = [];
            field.type = "select";
            FLA.setLocalDataAction(field.params, this).action();
            return field;
        },

        /**
         * ?????????????????????????? ?????????????????? ?????????????? ???? ???????????????????? FL_ACTIONS. ???????????????? ?????? ??????????????, ?????????????? ???????????? ????????????????????,
         * ?? ???????????? ???????????????????? ???? ?????????????????? ?????? ???????????????????? ????????????????. ???????? ?? ?????????????? ?????????????? ???????????????????? ???????? ??????????????????
         * ???????????????????? ??????????????, ???? ???? ???????????????? ?????????????? ?????? ????????????-??????????????????????, ?? ???????????????? ??????, ???????????????? ?????? ???????????? ??
         * ???????????? ????????????????????.
         * @param {Object} event
         * @param {Object} config
         * @param {Object} args
         * @returns {FL_ACTIONS}
         */
        actionEvent: function(event, config, args) {
            if(typeof config.current.events === "object" && typeof config.current.events[event] !== "undefined" ) {
                this[config.current.events[event]](config, args);
            }
            return this;
        },

        /**
         * ???????????????????????? ?????????????????? ?? ?????????????? "fl-action.reload-as". ???? ???????????????? ???????????????????????? ???????? ?? ?????????????? "fl-action-reload-as".
         * ???????? ???????????? ?????????????????? ?????? ?????????????????????? ?????????????????? ?????? ?????????????????????????? ????????????????. ?????????? ???? ???????????????? ???????????? ????????????????????,
         * ???????????????????????? "actionData" ?? ???????????????????? ?????????? "setLocalDataAction".
         * @param {Object} config
         * @param {Object} args
         */
        reloadEntityAs: function(config, args) {
            let elem;
            let actionData;
            $(".fl-app.reload-as").each(function(elem) {
                elem = $( this );
                actionData = elem.data();
                actionData["string__form.values.e_entity_class_id"] = args.item.e_id;
                FLA.setLocalDataAction(actionData, elem).action();
            });
        },
        
        buildJsGridContextMenuNoData: function(response, appConf) {
            $.contextMenu({
                selector: '#' + appConf.id + ' .jsgrid-nodata-row',
                callback: function(key, options) {
                    $(options.$trigger).trigger('click');
                    // let item = FLA.tmp.row_click_data.args.item;
                    let item = response.additional_data.default_data;
                    console.log(response);
                    console.log(item);
                    let actionData = options.items[key]['action_data'];
                    let settings;
                    if(typeof options.items[key]['settings'] === 'object') {
                        settings = options.items[key]['settings'];
                    }
                    let dataKey;
                    for(let key in item) {
//                        dataKey = key;
                        dataKey = renameProperties(key, item, settings);
                        actionData["string__form.values." + dataKey] = item[key];
                    }
                    function renameProperties(key, item, settings) {
                        if(typeof settings === "object" && typeof settings.rename_properties === "object") {
                            for(let i in settings.rename_properties) {
                                if(i === key) {
                                    return settings.rename_properties[i];
                                }
                            }
                        }
                        return key;
                    }
                    FLA.setLocalDataAction(actionData).action();
                },
                items: appConf.context_menu_no_data.items
            });
            $('.context-menu-one').on('click', function(e){
                console.log('clicked', this);
            });
        },
        
        buildJsGridPagingApp: function(response, config, appConf, workspace) {
            if(!this.buildJsGridPagingAppCheckData(response, config, appConf, workspace)) {
                return;
            }
            let template = $(".fl-action-tmp:first").find(".grid-template-paging:first").clone();
            workspace.find(".app-js-grid").append(template);
            let paginLimit = response.additional_data.pagination_row_count;
            let count = response.additional_data.pagination_count;
            let pageCount = (count - count % paginLimit) / paginLimit +1;
            if(pageCount * paginLimit < count ) {
                pageCount++;
            }
            this.buildJsGridPagingAppSetPajes(pageCount, template, response.additional_data, workspace);
        },
        
        /**
         * ???????????? - ?????????????????????? ?????????????????? ?? ???????????????????? jsGrid.
         * @param {integer} pageCount
         * @param {Object} template
         * @param {Object} additionalData
         * @param {Object} workspace
         * @returns {nm$_flActions.flActionsL#19.flActionsAnonym$0}
         */
        buildJsGridPagingAppSetPajes: function(pageCount, template, additionalData, workspace) {
            let currentPage = template.find(".jsgrid-pager-page.jsgrid-pager-current-page");
            let page = template.find(".jsgrid-pager-page").not(".jsgrid-pager-current-page") ;
            let pageClone;
            let target = template.find(".jsgrid-pager:first");
            let i = 1;
            let numberPaginItemBefore = 2;
            let numberPaginItemAfter = 2;
            let numberPaginItem = numberPaginItemBefore + numberPaginItemAfter;
            if(numberPaginItem > pageCount){
                numberPaginItem = pageCount;
            }
            let startPage = 1;
            if(additionalData.pagination_page > numberPaginItemBefore){
                startPage = additionalData.pagination_page - numberPaginItemBefore;
            }
            let endPage = numberPaginItem;
            if(additionalData.pagination_page > numberPaginItemBefore){
                endPage = additionalData.pagination_page + numberPaginItemAfter;
                if(endPage > pageCount) {
                    endPage = pageCount;
                }
            }
            let rangePages = (additionalData.pagination_page - additionalData.pagination_page % numberPaginItem) / numberPaginItem;
            let $this = this;
            let currentPageName;
            let prevPage;
            let nextPage;
            //???????????????????? ???? First ????????????, ?????????????????? ?????????????????? ?????????????????? ?? ??????????, ?????? ???????????? ????????????????.
            if(additionalData.pagination_page > 1) {
                target.append(template.find(".jsgrid-pager-nav-button.first-page").clone().bind( "click", function(e) {
                        $this.jsgridPagerOpenPage(workspace, {"string__form.values.pagination_page": 1});
                    }));
            }
            //???????????????????? ???? Prev ????????????, ?????????????????? ?????????????????? ?????????????????? ?? ??????????, ?????? ???????????? ????????????????.
            if(additionalData.pagination_page > 1) {
                target.append(template.find(".jsgrid-pager-nav-button.prev-page").clone().bind( "click", function(e) {
                        $this.jsgridPagerOpenPage(workspace, {"string__form.values.pagination_page": prevPage});
                    }));
                if(startPage > 1){
                    target.append(template.find(".jsgrid-pager-nav-button.prev-page-ellipsis").clone().bind( "click", function(e) {
                        $this.jsgridPagerOpenPage(workspace, {"string__form.values.pagination_page": prevPage});
                    }));
                }
            }
            i  = startPage;
            console.log('startPage', startPage);
            console.log('endPage', endPage);
            console.log('pageCount', pageCount);
            do {
                currentPageName = i;
                if((currentPageName) === additionalData.pagination_page) {
                    prevPage = currentPageName - 1;
                    nextPage = currentPageName + 1;
                    currentPage.text(currentPageName);
                    target.append(currentPage.clone());
                } else {
                    page.find('a').text(currentPageName);
                    pageClone = page.clone();
                    pageClone.attr("data-string__form.values.pagination_page", currentPageName);
                    target.append(pageClone);
                    pageClone.bind( "click", function(e) {
                        $this.jsgridPagerOpenPage(workspace, $(this).data());
                    });
                }
            } while (++i <= endPage);
            if(additionalData.pagination_page < pageCount) {
                if(endPage < pageCount) {
                    target.append(template.find(".jsgrid-pager-nav-button.next-page-ellipsis").clone().bind( "click", function(e) {
                        $this.jsgridPagerOpenPage(workspace, {"string__form.values.pagination_page": nextPage});
                    }));
                }
                target.append(template.find(".jsgrid-pager-nav-button.next-page").clone().bind( "click", function(e) {
                        $this.jsgridPagerOpenPage(workspace, {"string__form.values.pagination_page": nextPage});
                    }));
            }
            if(additionalData.pagination_page < pageCount) {
                target.append(template.find(".jsgrid-pager-nav-button.last-page").clone().bind( "click", function(e) {
                        $this.jsgridPagerOpenPage(workspace, {"string__form.values.pagination_page": pageCount});
                    }));
            }
            template.find(".grid-template-paging-tmp").remove();
            return this;
        },
        
        jsgridPagerOpenPage: function(workspace, data) {
            let mergedData = {...workspace.data(), ...data};
            FLA.setLocalDataAction(mergedData, workspace).action();
        },
        
        /**
         * TODO-docs ???????????????? ???????????? ?????? ??????????????????. 
         * 1. ?????????????????? ???????????????? settings.paging ?? ?????????????? ????????????????????.
         * 2. ?????????????????? ?????????????? ???????????? ?????? ?????????????????? ?? ???????????? ????????????????????(response.additional_data).
         * @param {Object} response
         * @param {Object} config
         * @param {Object} appConf
         * @param {Object} workspace
         * @returns {Boolean}
         */
        buildJsGridPagingAppCheckData: function(response, config, appConf, workspace) {
            if(typeof appConf.settings.paging !== "boolean" || appConf.settings.paging !== true) {
                return false;
            }
            if(typeof response.additional_data !== "object") {
                return false;
            }
            if(typeof workspace !== "object") {
                return false;
            }
            return true;
        },
        
        buildJsGrid: function(response, config, appConf) {
            let gridId = "#" + appConf.id;
            let settings = appConf.settings;
            $("#" + appConf.id).jsGrid({
                config: config,
                onDataLoading: function(args) {},
                onDataLoaded: function(args) {},
                onError: function(args) {},
                onInit: function(args) {},
                setArgsItemInFormFields: function(item, data) {
                    for(let key in item) {
                        data["string__form.values." + key] = item[key];
                    }
                    return data;
                },
                onItemInserting: function(args) {
                    if(typeof args.item.remote_insert_ok === "boolean" && args.item.remote_insert_ok) {
                        args.cancel = false;
                    } else {
                        args.cancel = true;
                        let data = {
                            string__entity: args.grid.config.current.entity,
                            string__action_name: "create",
                            obj__callback: {
                                success: ()=>{
                                    args.item.remote_insert_ok = true;
                                    $(gridId).jsGrid("insertItem", args.item);
                                }
                            }
                        };
                        this.setArgsItemInFormFields(args.item, data),
                        FLA.setLocalDataAction(data).action();
                    }
                },
                onItemInserted: function(args) {
                    console.log(args);
                },
                onItemUpdating: function(args) {
                    if(typeof args.item.remote_update_ok === "boolean" && args.item.remote_update_ok) {
                        args.cancel = false;
                    } else {
                        args.cancel = true;
                        let data = {
                            string__entity: args.grid.config.current.entity,
                            string__action_name: "update",
                            obj__callback: {
                                success: ()=>{
                                    args.item.remote_update_ok = true;
                                    $(gridId).jsGrid("updateItem", args.item);
                                }
                            }
                        };
                        this.setArgsItemInFormFields(args.item, data),
                        FLA.setLocalDataAction(data).action();
                    }
                },
                onItemUpdated: function(args) {
                    $(gridId).jsGrid("editItem", args.item);
                },
                onItemDeleting: function(args) {
                    if(typeof args.item.remote_delete_ok === "boolean" && args.item.remote_delete_ok) {
                        args.cancel = false;
                    } else {
                        args.cancel = true;
                        let data = {
                            string__entity: args.grid.config.current.entity,
                            string__action_name: "delete",
                            obj__callback: {
                                success: ()=>{
                                    args.item.remote_delete_ok = true;
                                    $(gridId).jsGrid("deleteItem", args.item);
                                }
                            }
                        };
                        this.setArgsItemInFormFields(args.item, data),
                        FLA.setLocalDataAction(data).action();
                    }
                },
                onItemDeleted: function(args) {},    // on done of controller.deleteItem
                onItemInvalid: function(args) {},    // after item validation, in case data is invalid
                onOptionChanging: function(args) {}, // before changing the grid option
                onOptionChanged: function(args) {},  // after changing the grid option
                onPageChanged: function(args) {},    // after changing the current page    
                onRefreshing: function(args) {},     // before grid refresh
                onRefreshed: function(args) {},      // after grid refresh
                controller: {
                    loadData: $.noop,
                    insertItem: $.noop
                },
                rowClick: function(args) {
                    FLA.tmp.row_click_data = { args : args };
                    FLA.actionEvent('jsgrid_row_click', config, args);
                    return args;
                },
                rowDoubleClick: function(args) {
                    FLA.tmp.row_double_click_data = { args : args };
                    FLA.actionEvent('jsgrid_row_double_click', config, args);
                    return args;
                },
                data: response.properties,
                fields: settings.fields,
                width: !!settings.width  ? settings.width : "100%",
                height: !!settings.height  ? settings.height : "auto",
                filtering: !!settings.filtering  ? settings.filtering : false,
                editing: !!settings.editing  ? settings.editing : false,
                sorting: !!settings.sorting  ? settings.sorting : false,
                paging: !!settings.paging  ? settings.paging : false,
                autoload: !!settings.autoload  ? settings.autoload : false,
                inserting: !!settings.inserting  ? settings.inserting : false,
                pageLoading: false,
            //    rowClass: function(item, itemIndex) { ... },
                noDataContent: !!settings.noDataContent  ? settings.noDataContent :  "Not found",
                confirmDeleting: !!settings.confirmDeleting  ? settings.confirmDeleting : false,
                deleteConfirm: !!settings.deleteConfirm  ? settings.deleteConfirm :  "Are you sure?",
                pagerContainer: !!settings.pagerContainer  ? settings.pagerContainer :  null,
                pageIndex: !!settings.pageIndex  ? settings.pageIndex :  1,
                pageSize: !!settings.pageSize  ? settings.pageSize :  20,
                pageButtonCount: !!settings.pageButtonCount  ? settings.pageButtonCount :  15,
                pagerFormat: !!settings.pagerFormat  ? settings.pagerFormat :  "Pages: {first} {prev} {pages} {next} {last}    {pageIndex} of {pageCount}",
                pagePrevText: !!settings.pagePrevText  ? settings.pagePrevText :  "Prev",
                pageNextText: !!settings.pageNextText  ? settings.pageNextText :  "Next",
                pageFirstText: !!settings.pageFirstText  ? settings.pageFirstText :  "First",
                pageLastText: !!settings.pageLastText  ? settings.pageLastText :  "Last",
                pageNavigatorNextText: !!settings.pageNavigatorNextText  ? settings.pageNavigatorNextText :  "...",
                pageNavigatorPrevText: !!settings.pageNavigatorPrevText  ? settings.pageNavigatorPrevText :  "...",
            //    invalidNotify: function(args) { ... }
                invalidMessage: !!settings.invalidMessage  ? settings.invalidMessage :  "Invalid data entered!",
                loadIndication: !!settings.loadIndication  ? settings.loadIndication :  true,
                loadIndicationDelay: !!settings.loadIndicationDelay  ? settings.loadIndicationDelay :  500,
                loadMessage: !!settings.loadMessage  ? settings.loadMessage :  "Please, wait...",
                loadShading: !!settings.loadShading  ? settings.loadShading :  true,
                updateOnResize: !!settings.updateOnResize  ? settings.updateOnResize :  true,
                rowRenderer: !!settings.rowRenderer  ? settings.rowRenderer :  null,
                headerRowRenderer: !!settings.headerRowRenderer  ? settings.headerRowRenderer :  null,
                filterRowRenderer: !!settings.filterRowRenderer  ? settings.filterRowRenderer :  null,
                insertRowRenderer: !!settings.insertRowRenderer  ? settings.insertRowRenderer :  null,
                editRowRenderer: !!settings.editRowRenderer  ? settings.editRowRenderer :  null
            });
        },
        
        setJsGridParams: function() {
            
        },
        
        setJsGridDefaultParams: function() {
            
        },

        createElement: function(config) {
            let act = config.current.element_type;
            act = "create" + act[0].toUpperCase() + act.slice(1);
            return this[act](config);
        },
        
        getLocalDataAction: function(dataAction) {
            let dataConf = {};
            let dataAttributSplitArr;
            for(let dataAttribut in dataAction) {
                dataAttributSplitArr = dataAttribut.split('__');
                switch (dataAttributSplitArr[0]) {
                    case "str":
                        dataConf[dataAttributSplitArr[1]] = dataAction[dataAttribut];
                        break;
                    case "fn":
                        dataConf[dataAttributSplitArr[1]] = dataAction[dataAttribut];
                        break;
                    case "obj":
                        dataConf[dataAttributSplitArr[1]] = dataAction[dataAttribut];
                        break;
                    case "string":
                        dataConf[dataAttributSplitArr[1]] = dataAction[dataAttribut];
                        break;
                    case "function":
                        dataConf[dataAttributSplitArr[1]] = dataAction[dataAttribut];
                        break;
                    case "object":
                        dataConf[dataAttributSplitArr[1]] = dataAction[dataAttribut];
                        break;
                    case "str_to_obj":
                        dataConf[dataAttributSplitArr[1]] = this.setDataConfValueAsParseResult(dataAction[dataAttribut]);
                        break;
                    case "str_to_arr":
                        dataConf[dataAttributSplitArr[1]] = this.setDataConfValueAsArray(dataAction[dataAttribut]);
                        break;
                    case "json_to_obj":
                        dataConf[dataAttributSplitArr[1]] = this.setDataConfValueAsObject(dataAction[dataAttribut]);
                        break;
                    default:
                        console.error( "The config data type is specified incorrectly, the following values are allowed: string, parse, array, object" );
                }
            };
            return dataConf;
        },
        
        setLocalDataAction: function(dataAction, initElem) {
            FLA.config = this.getDefaulConfig();
            let path;
            let dataConf = this.getLocalDataAction(dataAction);
            dataConf.init_elem = null;
            if(typeof initElem === "object") {
                dataConf.init_elem = initElem;
            }
            FLA.config.current_local = this.getLocalDataAction(dataAction);
            let funcCreate;
            let funcAssigning;
            let funcCheck;
            for(let k in dataConf) {
                path = '';
                k.split(".").forEach(function(p){
                    path = path + "." +p;
                    funcCheck = new Function("", "return FLA.config.current" + path);
                    if(funcCheck() === undefined) {
                        funcCreate = new Function("", "FLA.config.current" + path + "= {}");
                        funcCreate();
                    }
                });
                funcAssigning = new Function("value", "FLA.config.current" + path + "= value");
                funcAssigning(dataConf[k]);
                path = '';
            }
            return this;
        },
        
        setDataConfValueAsArray: function(arrData) {
            let arr = [];
            arrData.split(',').forEach(function(item, key){
                arr[key] = item.trim();
            });
            return arr;
        },
        
        setDataConfValueAsObject: function(data) {
            
            if(typeof data === "object") {
                return data;
            } else if(typeof data === "string") {
                return JSON.parse(data);
            }
            return {};
        },
        
        setDataConfValueAsParseResult: function(data) {
            
            let $this = this;
            let itemArr = [];
            let parseResult = {};
            data.replace(/,+/g, ',').replace(/=+/g, '=').trim().split(',').forEach(function(val) {
                val = val.split('=');
                if(typeof val[0] === "string" && val[0].trim().length > 0) {
                    parseResult[val[0].trim()] = val[1].trim();
                }
            });
            return parseResult;
        },

        CreateModal : function(config) {
            
            let templates;
            let $this = this;
            let modalCloneClass = "fl-action-clone-modal-" + config.current.entity + "-" + config.current.action_name;
            $(document).find("." + modalCloneClass).remove();
            if(typeof config.current.parent === 'undefined') {
                config.current.parent = "#fl-action-modal-xl";
            }
            if(typeof config.current.parent === 'string') {
                templates = $(config.current.parent);
            }
            let modal = templates.clone();
            modal.addClass("fl-action-clone");
            modal.addClass(modalCloneClass);
            if(typeof config.current.modal_title === 'string') {
                modal.find(".modal-title").text(config.current.modal_title);
            } else {
                modal.find(".modal-title").text(config.current.entity + " " + config.current.action_name);
            }
            if(typeof config.current.callback.before !== "object") {
                config.current.callback.before = {};
            }
            config.current.callback.before['hide_modal'] = function(r, c) {
                modal.modal('hide');
            };
            modal.find(".fl-action-modal").bind( "click", function() {
                modal.modal('hide');
            });
            
            return modal;
        },

        CreateModalWithForm: function(config) {

            let templates;
            let $this = this;
            let modalCloneClass = "fl-action-clone-modal-" + config.current.entity + "-" + config.current.action_name;
            $(document).find("." + modalCloneClass).remove();
            if(typeof config.current.parent === 'undefined') {
                config.current.parent = "#fl-action-modal-default";
            }
            if(typeof config.current.parent === 'string') {
                templates = $(config.current.parent);
            }
            let modal = templates.clone();
            modal.addClass("fl-action-clone");
            modal.addClass(modalCloneClass);
            modal.find(".modal-body").append(config.current.element);
            if(typeof config.current.modal_title === 'string') {
                modal.find(".modal-title").text(config.current.modal_title);
            } else {
                modal.find(".modal-title").text(config.current.entity + " " + config.current.action_name);
            }
            if(typeof config.current.callback.before !== "object") {
                config.current.callback.before = {};
            }
            config.current.callback.before['hide_modal'] = function(r, c) {
                modal.modal('hide');
            };
            modal.find(".fl-action-modal").bind( "click", function() {
                $this.sendForm(config);
            });

            modal.modal('show');
            modal.on('shown.bs.modal', function () {
                modal.find('input:first').trigger('focus');
            });
            this.parent = modal;
            return this;
        },

        createForm: function(config) {
            let $this = this;
            let templates;
            let form;
            let formBody;
            let formCloneClass = "fl-action-clone-form-" + config.current.entity + "-" + config.current.action_name;
            $(document).find("." + formCloneClass).remove();
            if(typeof config.current.element_id === 'string') {
                templates = $(config.current.element_id);
            } else {
                console.error('??onfig.current.element - not found');
                return false;
            }
            if(templates.length !== 1) {
                console.error('Form templates - not found');
            }
            if(templates[0].tagName !== "FORM") {
                form = templates.find('form').clone();
            } else {
                form = templates.clone();
            }
            if(typeof config.current.form.action === "string") {
                form.attr("action", config.current.form.action);
            }
            form.addClass("fl-action-clone");
            form.addClass(formCloneClass);
            formBody = form.find(config.current.form.body);
            let func;
            let funcElem, funcElemArr;
            for (let field in config.current.form.fields) {
                if(typeof config.current.form.fields[field].type !== "string") {
                    continue;
                }
                func = config.current.form.fields[field].type;
                funcElemArr = func.split('_');
                funcElem = "formSet";
                if(funcElemArr.length > 1){
                    for(let i in funcElemArr) {
                        funcElem = funcElem + funcElemArr[i][0].toUpperCase() + funcElemArr[i].slice(1);
                    }
                }
                func = "formSetInput" + func[0].toUpperCase() + func.slice(1);
                if(typeof $this[func] === 'function') {
                    $this[func](formBody, field, templates, config);
                } else if(typeof $this[funcElem] === 'function') {
                    $this[funcElem](formBody, field, templates, config);
                } else {
                    continue;
                }
            }
            return form;
        },

        formInputSetVal: function (field, inputData, config) {
            let val = null;
            if(typeof  config.current.form.values === "object" && typeof  config.current.form.values[field] !== "undefined")
            {
                val = config.current.form.values[field];
            } else {
                if(inputData.value !== "undefined") {
                    val = inputData.value;
                }
            }
            return val;
        },

        formSetElemText(formBody, field, templates, config) {
            let input = templates.find(".form-elem-text:first").clone();
            let inputData = config.current.form.fields[field];
            let inputId = config.current.entity + "-" + config.current.action_name + "-" + field;
            input.find(".elem-text").text('field');
            this.formSetElemTextSetVal(inputData, config.current.form.fields, config);
            if(input.length === 1) {
                formBody.append(inputData.text);
            }
        },
        
        formSetElemTextSetVal(inputData, fields, config) {
            if(typeof inputData.text !== "string") {
                inputData.text = "";
                return;
            }
            for(let k in config.current.form.values) {
                if(typeof config.current.form.values[k] !== "string") {
                    continue;
                }
                inputData.text = inputData.text.replace("{{" + k + "}}", config.current.form.values[k]);
            }
        },

        formSetInputHidden(formBody, field, templates, config) {
            let input = templates.find(".input-hidden:first").clone();
            let inputData = config.current.form.fields[field];
            let inputId = config.current.entity + "-" + config.current.action_name + "-" + field;
            input.find("input:first").attr("id", inputId);
            input.find("input:first").attr("name", field);
            input.find("input:first").val(this.formInputSetVal(field, inputData, config));
            if(input.length === 1) {
                formBody.append(input);
            }
        },

        formSetInputText(formBody, field, templates, config) {
            let input = templates.find(".input-text:first").clone();
            let inputData = config.current.form.fields[field];
            let label = input.find(".input-label");
            if(typeof inputData.label !== "undefined") {
                label.text(inputData.label);
            } else {
                label.text(field[0].toUpperCase() + field.slice(1));
            }
            let inputId = config.current.entity + "-" + config.current.action_name + "-" + field;
            label.attr("for", label.attr("for") + inputId);
            input.find("input:first").attr("id", label.attr("for"));
            input.find("input:first").attr("name", field);
            input.find("input:first").val(this.formInputSetVal(field, inputData, config));
            if(input.length === 1) {
                formBody.append(input);
            }
        },
        
        formSetInputSelect(formBody, field, templates, config) {
            console.log(field);
//            let input = templates.find(".input-text:first").clone();
//            let inputData = config.current.form.fields[field];
//            let label = input.find(".input-label");
//            if(typeof inputData.label !== "undefined") {
//                label.text(inputData.label);
//            } else {
//                label.text(field[0].toUpperCase() + field.slice(1));
//            }
//            let inputId = config.current.entity + "-" + config.current.action_name + "-" + field;
//            label.attr("for", label.attr("for") + inputId);
//            input.find("input:first").attr("id", label.attr("for"));
//            input.find("input:first").attr("name", field);
//            input.find("input:first").val(this.formInputSetVal(field, inputData, config));
//            if(input.length === 1) {
//                formBody.append(input);
//            }
        },
        
        randomInteger: function(min, max) {
            let rand = min + Math.random() * (max + 1 - min);
            return Math.floor(rand);
        },
        
        setActionId: function() {
            let id = this.randomInteger(1000000000000, 9999999999999);
            return id;
        },

        initPageEditor()
        {
            let editorData = ace.edit("fl-page-editor");
            editorData.setTheme("ace/theme/monokai");
            editorData.session.setMode("ace/mode/html");
            editorData.setValue($("#fl-page-editor-content").text());
        },

        savePageEditor()
        {
            let editorData = ace.edit("fl-page-editor");
            editorData.setTheme("ace/theme/monokai");
            editorData.session.setMode("ace/mode/html");
            editorData.setValue($("#fl-page-editor-content").text());
        }
    });

    let init = FL_ACTIONS.fn.init = function (selector) {
        this[0] = selector;
        return selector;
    };

    init.prototype = FL_ACTIONS.fn;


    if (typeof define === "function" && define.amd) {
        define("flmquery", [], function () {
            return FL_ACTIONS;
        });
    }

    let _FL_ACTIONS = window.FL_ACTIONS,

        _FLA = window.FLA;

    FL_ACTIONS.noConflict = function (deep) {
        if (window.FLA === FL_ACTIONS) {
            window.FLA = _FLA;
        }

        if (deep && window.FL_ACTIONS === FL_ACTIONS) {
            FL_ACTIONS = _FL_ACTIONS;
        }

        return FL_ACTIONS;
    };

    if (!noGlobal) {
        window.FL_ACTIONS = window.FLA = FL_ACTIONS;
    }
    
    document.addEventListener("flQueryReady", function(event) {
        FLA.editMod = $(".fl-entity-common-data:first").data("edit-mod");
        $(".fl-app.auto-init").each(function(){
            let elem = $(this);
            elem.attr("data-string__action_id", FLA.setActionId());
            FLA.setLocalDataAction(elem.data(), this).action();
        });
    }, false);

    $(document).on("click", ".fl-action", function(){
        FLA.setLocalDataAction($(this).data(), this).action();
    });

    return FL_ACTIONS;

});
