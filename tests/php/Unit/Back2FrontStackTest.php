<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front\Tests\Unit;

use DateTime;
use Tarampampam\Wrappers\Json;
use AvtoDev\Back2Front\Back2FrontInterface;
use Illuminate\Contracts\Support\Arrayable;
use AvtoDev\Back2Front\Tests\AbstractTestCase;

/**
 * Test service for transferring data from the back to the front.
 *
 * @coversDefaultClass \AvtoDev\Back2Front\Back2FrontStack
 *
 * @group back-to-front
 */
class Back2FrontStackTest extends AbstractTestCase
{
    /**
     * @var \AvtoDev\Back2Front\Back2FrontStack
     */
    protected $service;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(Back2FrontInterface::class);
    }

    /**
     * The toArray method.
     *
     * @covers ::toArray
     */
    public function testToArray(): void
    {
        $test_data = [
            0           => 123,
            1           => '345',
            2           => null,
            'std_class' => new \stdClass,
            'string'    => 'test_value',
            'level1'    => [
                'level2'   => [
                    'level3'   => [
                        'level4' => [
                            'std_class' => new \stdClass,
                            'scalar'    => 'test_val',
                        ],
                    ],
                    'test_l_3' => 'test_depth_3',
                ],
                'test_l_2' => 'test_depth_2',
            ],
        ];

        // Set data
        $this->service->put('test', $test_data);

        $result      = $this->service->toArray();
        $test_result = $result['test'];

        $this->assertEquals($test_data[0], $test_result[0]);
        $this->assertEquals($test_data[1], $test_result[1]);
        $this->assertEquals($test_data['string'], $test_result['string']);
        $this->assertEquals('test_val', $test_result['level1']['level2']['level3']['level4']['scalar']);

        $this->assertEquals('test_depth_2', $test_result['level1']['test_l_2']);
        $this->assertEquals('test_depth_3', $test_result['level1']['level2']['test_l_3']);

        $this->assertNull($test_result[2]);

        $this->assertArrayNotHasKey('std_class', $test_result);
        $this->assertFalse(isset($test_result['level1']['level2']['level3']['level4']['std_class']));
    }

    /**
     * The toArray method. An array of deep nesting.
     *
     * @covers ::formatDataRecursive
     * @covers ::toArray
     */
    public function testToArrayDeepScalar(): void
    {
        $deep_array               = [];
        $test_deep_array_scalar   = str_random();
        $test_deep_array_key_path = '0.test.1.4.A.#test.88';

        array_set($deep_array, $test_deep_array_key_path, $test_deep_array_scalar);

        $this->service->put('test_deep', $deep_array);

        $result = $this->service->toArray();

        $this->assertEquals($test_deep_array_scalar, array_get($result, 'test_deep.' . $test_deep_array_key_path));
    }

    /**
     * The toArray method. An object that can not be converted to an array.
     *
     * @covers ::clearNoScalarsFromArrayRecursive
     * @covers ::toArray
     */
    public function testToArrayStdObject(): void
    {
        $this->service->put('test_obj', new \stdClass);

        $result = $this->service->toArray();

        $this->assertNull(array_get($result, 'test_obj'));
    }

    /**
     * The toArray method. Format the date and time.
     *
     * @covers ::formatDataRecursive
     * @covers ::toArray
     */
    public function testToArrayDateTime(): void
    {
        $date_format = 'Y-m-d H:i:s';

        $date_time = new DateTime('1997-08-29');

        $this->service->put('date_time', $date_time);

        $result = $this->service->toArray();

        $this->assertEquals($date_time->format($date_format), $result['date_time']);
    }

    /**
     * The toArray method. An array of deep nesting. An object that can not be converted to an array.
     *
     * @covers ::toArray
     * @covers ::formatDataRecursive
     * @covers ::clearNoScalarsFromArrayRecursive
     */
    public function testToArrayDeepStdObject(): void
    {
        $deep_array               = [];
        $test_deep_array_object   = new \stdClass;
        $test_deep_array_key_path = '0.test.1.4.A.#test.88';

        array_set($deep_array, $test_deep_array_key_path, $test_deep_array_object);

        $this->service->put('test_deep', $deep_array);

        $result = $this->service->toArray();

        $this->assertNull(array_get($result, 'test_deep.' . $test_deep_array_key_path));
    }

    /**
     * The toArray method. Arrayable object.
     *
     * @covers ::toArray
     * @covers ::formatDataRecursive
     * @covers ::clearNoScalarsFromArrayRecursive
     */
    public function testToArrayArrayable(): void
    {
        $arrayable_mock = $this
            ->getMockBuilder(Arrayable::class)
            ->getMock();

        $arrayable_mock
            ->method('toArray')
            ->willReturn([1, 2, 3]);

        $this->service->put('test_arrayable', $arrayable_mock);

        $result = $this->service->toArray();

        $this->assertEquals([1, 2, 3], array_get($result, 'test_arrayable'));
    }

    /**
     * Adding data to the stack and output to json.
     *
     * @covers ::put
     * @covers ::get
     * @covers ::toJson
     */
    public function testPutGetToJson(): void
    {
        $test_data[]             = [str_random(), random_int(0, 100)];
        $test_data[]             = random_int(-10, 10);
        $test_data[]             = null;
        $test_data[str_random()] = str_random();
        $test_data[-10]          = str_random();
        $test_data['collection'] = collect([1]);

        foreach ($test_data as $key => $value) {
            $this->service->put($key, $value);
        }

        $in_array = $this->service->toArray();

        $parsed_data = Json::decode($this->service->toJson(), true);

        // Verifying that the array received through toArray contains the same data that was returned to toJson
        $this->assertEquals($in_array, $parsed_data);

        foreach ($test_data as $key => $value) {
            // Getting raw data
            $this->assertEquals($value, $this->service->get($key));

            // Checking data after conversion to JSON
            if (is_scalar($value)) {
                $this->assertEquals($value, $parsed_data[$key]);
            } else {
                $this->assertEquals(Json::decode(Json::encode($value), true), $parsed_data[$key]);
            }
        }
    }

    /**
     * Checking the has method.
     *
     * @covers ::has
     */
    public function testHas(): void
    {
        $test_key = str_random();

        $this->service->put($test_key, 1);

        $this->assertTrue($this->service->has($test_key));
        $this->assertFalse($this->service->has(str_random()));
    }

    /**
     * Checking the forget method.
     *
     * @covers ::forget
     */
    public function testForget(): void
    {
        $test_key = str_random();

        $this->service->put($test_key, 1);

        $this->assertTrue($this->service->has($test_key));

        $this->service->forget($test_key);

        $this->assertFalse($this->service->has($test_key));
    }
}
