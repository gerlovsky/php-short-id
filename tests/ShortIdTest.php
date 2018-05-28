<?php

namespace Gerlovsky\ShortId\Tests;

use Gerlovsky\ShortId\ShortId;
use PHPUnit\Framework\TestCase;

class ShortIdTest extends TestCase
{
    protected function tearDown()
    {
        ShortId::setFactory(null);
    }

    public function testEncode()
    {
        $id = 1234;

        $encodeId = ShortId::encode($id);

        $this->assertRegExp('/^[a-z0-9\_\-]+$/i', $encodeId);
    }

    public function testGenerateWithLength()
    {
        $id = 1234;
        $generated = ShortId::encode($id, 8);
        $this->assertRegExp('/^[a-z0-9\_\-]{8}$/i', $generated);
    }

    public function testGetFactory()
    {
        $factory = ShortId::getFactory();
        $this->assertInstanceOf('Gerlovsky\ShortId\Factory', $factory);
    }

    public function testSetFactory()
    {
        $factoryMock = $this->getMockBuilder('Gerlovsky\ShortId\Factory')->getMock();

        ShortId::setFactory($factoryMock);

        $this->assertSame($factoryMock, ShortId::getFactory());
    }

    public function testIsValid()
    {
        $this->assertTrue(ShortId::isValid('shortid'));
    }

    public function testIsNotValid()
    {
        $this->assertFalse(Shortid::isValid('/(;#!'));
        $this->assertFalse(Shortid::isValid('harmful string stuff'));
    }

    public function testIsValidWithRegexChar()
    {
        $factory = ShortId::getFactory();
        $factory->setAlphabet('hìjklmnòpqrstùvwxyzABCDEFGHIJKLMNOPQRSTUVWX.\+*?[^]$(){}=!<>|:-/');
        ShortId::setFactory($factory);
        $this->assertTrue(ShortId::isValid('slsh/]?'));
    }
}