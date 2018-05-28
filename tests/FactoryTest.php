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
//        $this->factory->setLength(6);
    }

    public function testEncodeAndDecode()
    {
        $id = rand(10000, 99999);

        $encodeId = $this->factory->encode($id, 6);
        var_dump($id, $encodeId);die();

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
    public function testSetAlphabet(string $alphabet)
    {
        $this->factory->setAlphabet($alphabet);
        $newAlphabet = Assert::readAttribute($this->factory, 'alphabet');

        $this->assertSame($alphabet, $newAlphabet);
    }

    /**
     * @param string $alphabet
     *
     * @dataProvider wrongAlphabetsProvider
     */
    public function testSetWrongAlphabet($alphabet)
    {
        $this->expectException(\InvalidArgumentException::class);

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
            'rand' => [sha1(random_int(0, getrandmax()))],
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

    public function testSetWrongLengthType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->factory->setLength('invalid');
    }

    public function testSetWrongLengthRange()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->factory->setLength(0);
    }
}