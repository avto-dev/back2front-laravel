(function (root, factory) {

    "use strict";

    if (typeof define === 'function' && define.amd) {
        // AMD
        define([], function () {
            return (root.frontStack = factory(root));
        });
    } else if (typeof exports === 'object') {
        // CommonJS
        module.exports = factory(root);
    } else {
        // Browser
        root.frontStack = factory(root);
    }
}(
    typeof window !== "undefined"
        ? window
        : (global !== "undefined"
        ? global
        : this),

    function (root) {

        console.log(root.frontStack);

        /**
         * Singletone instance.
         *
         * @type {FrontStack|null}
         */
        var instance = null;

        /**
         * Made recursive get of array with dot notation.
         *
         * @param {Object} data Search data
         * @param {string} key Key with dot notation ex. "a.b.c"
         * @param {*} default_value value that must be returned.
         *
         * @returns {*}
         */
        var recursive_get = function (data, key, default_value) {
            if (data.hasOwnProperty(key)) {
                return data[key];
            }

            var key_arr = key.toString().split('.');

            var next_key = key_arr.shift();

            if (!data.hasOwnProperty(next_key)) {
                return default_value;
            }

            return recursive_get(data[next_key], key_arr.join('.'), default_value);
        };

        /**
         * StackFront object (as singletone).
         *
         * @returns {FrontStack}
         */
        var FrontStack = function () {

            // Object name which use extracting any variables passed from backend.
            var stack_name = 'backend';

            /**
             * Set custom name of property with data.
             *
             * @param custom_stack_name
             */
            this.setStackName = function(custom_stack_name) {
                stack_name = custom_stack_name;
            };

            /**
             * Method get params form backend.
             *
             * params {name, default_value} String
             * @returns {object}
             */
            this.get = function (name, default_value) {
                var value = recursive_get(this.all(), name, default_value);
                if (typeof value !== 'undefined') {
                    return value;
                }

                return default_value;
            };

            /**
             * Method check function on existence.
             *
             * @params {name} String
             * @returns {boolean}
             */
            this.has = function (name) {
                var value = recursive_get(this.all(), name);

                return (typeof value !== 'undefined');
            };

            /**
             * Method return all stack data as object.
             *
             * @returns {Object}
             */
            this.all = function () {
                if (root.hasOwnProperty(stack_name)) {
                    var stack = root[stack_name];

                    if (typeof stack === 'object') {
                        return stack;
                    }
                }

                return {};
            };

            // Check instance exists
            if (instance !== null) {
                throw new Error('Cannot instantiate more than one instance, use .getInstance()');
            }
        };

        /**
         * Returns FrontStack object instance.
         *
         * @returns {null|FrontStack}
         */
        FrontStack.__proto__.getInstance = function () {
            if (instance === null) {
                instance = new FrontStack();
            }
            return instance;
        };

        return FrontStack.getInstance();
    }
));
