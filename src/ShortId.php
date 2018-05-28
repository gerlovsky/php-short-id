<?php

namespace Gerlovsky\ShortId;


class ShortId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \Gerlovsky\ShortId\Factory
     */
    private static $factory;

    /**
     * ShortId constructor.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return $this->id;
    }

    public static function getFactory()
    {
        if (null === self::$factory) {
            self::$factory = new Factory();
        }

        return self::$factory;
    }

    public static function setFactory($factory = null)
    {
        self::$factory = $factory;
    }

    public static function encode($input, $length = null)
    {
        return self::getFactory()->encode($input, $length);
    }

    public static function decode($input, $length = null)
    {
        return self::getFactory()->decode($input, $length);
    }

    /**
     * @param string $value
     * @param int|null $length
     * @param string|null $alphabet
     *
     * @return bool
     */
    public static function isValid($value, $length = null, $alphabet = null)
    {
        $length = $length ?? self::getFactory()->getLength();
        $alphabet = preg_quote($alphabet ?: self::getFactory()->getAlphabet(), '/');
        $matches = [];
        $ok = preg_match('/^(['.$alphabet.']{'.$length.'})$/', $value, $matches);
        return $ok > 0 && strlen($matches[0]) === $length;
    }
}