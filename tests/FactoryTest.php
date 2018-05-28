<?php

namespace Gerlovsky\ShortId\Tests;


use Gerlovsky\ShortId\Factory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new Factory();
        $this->factory->setLength(5);
    }

    public function testEncodeAndDecode()
    {
        $id = rand(10000, 99999);

        $encodeId = $this->factory->encode($id);
        $decodeId = $this->factory->decode($encodeId);

        $this->assertEquals($id, $decodeId);
    }

    /**
     * @return array[]
     */
    public function alphabetsProvider()
    {
        $alphabets = [];
        $chars = [];

        for ($i = 1; $i <= 65533; ++$i) {
            $chars[] = mb_convert_encoding("&#$i;", 'UTF-8', 'HTML-ENTITIES');
        }

        $chars = preg_replace('/[^\p{Ll}]/u', '', $chars);
        $chars = array_filter(array_map('trim', $chars));

        for ($i = 0; $i < 100; ++$i) {
            shuffle($chars);
            $alphabets[] = [implode(null, array_slice($chars, 0, 64))];
        }

        return $alphabets;
    }

    /**
     * @param string $alphabet
     *
     * @dataProvider alphabetsProvider
     */
    public function testSetAlphabet($alphabet)
    {
        $this->factory->setAlphabet($alphabet);
        $newAlphabet = Assert::readAttribute($this->factory, 'alphabet');

        $this->assertSame($alphabet, $newAlphabet);
    }

    /**
     * @param string $alphabet
     * @expectedException \InvalidArgumentException
     *
     * @dataProvider wrongAlphabetsProvider
     */
    public function testSetWrongAlphabet($alphabet)
    {
        $this->factory->setAlphabet($alphabet);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function wrongAlphabetsProvider()
    {
        return [
            'test' => ['test'],
            'rand' => [sha1(rand(0, getrandmax()))],
        ];
    }

    public function testSetLength()
    {
        $this->factory->setLength(5);
        $this->assertSame(5, $this->factory->getLength());
    }

    public function testCheckLength()
    {
        $null = $this->factory->checkLength(null, false);

        $this->assertNull($null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongLengthType()
    {
        $this->factory->setLength('invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongLengthRange()
    {
        $this->factory->setLength(0);
    }
}