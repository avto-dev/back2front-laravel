/**
 * Tests for front-stack.js functional.
 */
var assert = require('chai').assert,
    expect = require('chai').expect;

var frontStack = require('../src/assets/front-stack.js');

/**
 * Object name that use extracting any variables passed from backend.
 *
 * @type {string}
 */
var stack_name = 'backend';

/**
 * Keys that are in the data
 *
 * @type {[string,string,string,string,string,string,string,string]}
 */
var exists_test_keys = [
    'test_string_key',
    'test_key_for_num_val',
    'test_object_key',
    'test_null_key',
    'test_object_key.0',
    'test_object_key.3.test_array',
    'test_object_key.3.test_array.0',
    'test_object_key.test_4',
    88
];

/**
 * Keys that are not in the data
 *
 * @type {[string,string,string,string]}
 */
var not_exists_test_keys = [
    'not_exists_key',
    '0',
    '0.0',
    'test_object_key.0.not_exists',
    0,
    ''
];

describe("Initialization init", function () {

    it('frontStack type of object', function () {
        expect(frontStack).to.be.an('object');
    });

});

describe("Set custom stack name", function () {

    /**
     * Test getiing data of does not exists stack variable.
     */
    it('No data', function () {
        var custom_stack_name = 'test_stack_name';

        frontStack.setStackName(custom_stack_name);

        var data = frontStack.all();

        assert.deepEqual(data, {});
    });

    /**
     * Test getting data from stack variable with custom name.
     */
    it('Is data', function () {
        var custom_stack_name = 'test_stack_name';
        var test_data = {
            'test_custom_key': '1'
        };

        frontStack.setStackName(custom_stack_name);

        Object.defineProperty(global, custom_stack_name, {
            writable: true,
            value: test_data
        });

        var data = frontStack.all();

        assert.deepEqual(data, test_data);
    });
});

describe('Data access', function () {

    /**
     * Init data.
     */
    before(function () {
        frontStack.setStackName(stack_name);
        Object.defineProperty(global, stack_name, {
            writable: true,
            value: {
                'test_string_key': 'test_value',
                'test_key_for_num_val': 1000,
                88: 123,
                'test_object_key': {
                    0: 10,
                    1: 11,
                    3: {
                        'test_array': [1, 'array_val', null]
                    },
                    'test_4': 'value_4'
                },
                'test_null_key': null
            }
        });
    });

    /**
     * Test getting all data.
     */
    it('all()', function () {

        var all_data = frontStack.all();

        expect(all_data).to.be.an('object');

        // 0 => Actual, 1 =>Expecte, 2 => Message
        var test_data = [
            [all_data.test_string_key, 'test_value', 'Check string value'],
            [all_data.test_key_for_num_val, 1000, 'Check numeric value'],
            [all_data[88], 123, 'Check numeric value'],
            [all_data.test_null_key, null, 'Check null value']
        ];

        test_data.forEach(function (test_item) {
            assert.strictEqual(test_item[0], test_item[1], test_item[2]);
        });

        assert.deepEqual(all_data.test_object_key, global[stack_name].test_object_key, 'Check value which is an object');
    });

    /**
     * Tests checking data exists.
     */
    it('has()', function () {
        exists_test_keys.forEach(function (test_key) {
            assert.isOk(frontStack.has(test_key), 'Test has() for key: ' + test_key);
        });
    });

    /**
     * Tests checking data exists for not exists keys.
     */
    it('! has()', function () {
        not_exists_test_keys.forEach(function (test_key) {
            assert.isNotOk(frontStack.has(test_key), 'Test not has() for key: ' + test_key);
        });
    });

    /**
     * Tests checking data exists.
     */
    it('get()', function () {

        // 0 => Key, 1 => Expected, 2 => Message
        var test_data = [
            ['test_string_key', 'test_value', 'Test getting simple string value by string key'],
            ['test_key_for_num_val', 1000, 'Test getting simple numeric value by string key'],
            [88, 123, 'Test getting simple numeric value by numeric key'],
            ['test_null_key', null, 'Test getting null'],
            ['test_object_key.0', 10, 'Dot-notation'],
            ['test_object_key.1', 11, 'Dot-notation'],
            ['test_object_key.3.test_array.0', 1, 'Dot-notation'],
            ['test_object_key.3.test_array.1', 'array_val', 'Dot-notation'],
            ['test_object_key.3.test_array.2', null, 'Dot-notation'],
            ['test_object_key.test_4', 'value_4', 'Dot-notation']
        ];

        test_data.forEach(function (test_item) {
            assert.strictEqual(frontStack.get(test_item[0]), test_item[1], test_item[2]);
        });

        assert.deepEqual(frontStack.get('test_object_key'), global[stack_name].test_object_key, 'Test getting object');

        // Not default
        assert.strictEqual(frontStack.get('test_string_key', 'default'), 'test_value', 'Get actual data instead default value');
    });

    /**
     * Tests checking data not exists.
     */
    it('! get()', function () {
        not_exists_test_keys.forEach(function (key) {
            assert.strictEqual(frontStack.get(key), undefined, 'Get by key ' + key + ' is Undefined');
        });
    });

    /**
     * Tests default value.
     */
    it('get() default value', function () {
        not_exists_test_keys.forEach(function (key) {

            var default_value = Math.random();

            assert.strictEqual(frontStack.get(key, default_value), default_value, 'Default value for key ' + key);
        });
    });

});
