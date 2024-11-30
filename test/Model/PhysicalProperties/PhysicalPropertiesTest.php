<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\PhysicalProperties;

use Faker\Factory;
use MyParcelNL\Sdk\Model\PhysicalProperties;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class PhysicalPropertiesTest extends TestCase
{
    /**
     * @var \Faker\Factory
     */
    protected $faker;

    /**
     * @param string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->faker = Factory::create('nl_NL');
        parent::__construct($name, $data, $dataName);
    }

    public function testDefaults()
    {
        $physicalProperties = new PhysicalProperties();

        $this->assertEquals(10, $physicalProperties->getWeight());
        $this->assertEquals(10, $physicalProperties->getLength());
        $this->assertEquals(10, $physicalProperties->getWidth());
        $this->assertEquals(10, $physicalProperties->getHeight());
    }

    public function testConstructor()
    {
        $array = [
            'weight' => $this->faker->numberBetween(200, 10000),
            'length' => $this->faker->numberBetween(1, 100),
            'width'  => $this->faker->numberBetween(1, 100),
            'height' => $this->faker->numberBetween(1, 100),
        ];

        $physicalProperties = new PhysicalProperties($array);

        $this->assertEquals($array['weight'], $physicalProperties->getWeight());
        $this->assertEquals($array['length'], $physicalProperties->getLength());
        $this->assertEquals($array['width'], $physicalProperties->getWidth());
        $this->assertEquals($array['height'], $physicalProperties->getHeight());
    }
}
