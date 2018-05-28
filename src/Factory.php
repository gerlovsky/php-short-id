<?php

namespace Gerlovsky\ShortId;

class Factory
{
    private $length = 7;

    /**
     * @var string
     */
    private $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_-';

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     *
     * @return Factory
     */
    public function setLength($length)
    {
        $this->checkLength($length);
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlphabet()
    {
        return $this->alphabet;
    }

    /**
     * @param string $alphabet
     *
     * @return Factory
     */
    public function setAlphabet($alphabet)
    {
        $this->checkAlphabet($alphabet);
        $this->alphabet = $alphabet;

        return $this;
    }

    public function checkLength($length = null, $strict = false)
    {
        if (null === $length && !$strict) {
            return;
        }

        if ($length < 2 || $length > 20) {
            throw new \InvalidArgumentException('Invalid length.');
        }

        return true;
    }

    public function checkAlphabet($alphabet = null, $strict = false)
    {
        if (null === $alphabet && !$strict) {
            return;
        }

        $alphaLength = mb_strlen($alphabet, 'UTF-8');

        if (64 !== $alphaLength) {
            throw new \InvalidArgumentException(sprintf('Invalid alphabet: %s (length: %u)', $alphabet, $alphaLength));
        }

        return true;
    }

    public function encode($input, $length = null)
    {
        $length = null === $length ? $this->length : $length;

        $output = '';
        $base = strlen($this->alphabet);

        if (is_numeric($length)) {
            $length--;
            if ($length > 0) {
                $input += pow($base, $length);
            }
        }

        for ($current = ($input != 0 ? floor(log($input, $base)) : 0); $current >= 0; $current--) {
            $powed = pow($base, $current);
            $floored = floor($input / $powed) % $base;
            $output = $output.substr($this->alphabet, $floored, 1);
            $input = $input - ($floored * $powed);
        }

        return $output;
    }

    public function decode($input, $neededLength = null)
    {
        $neededLength = null === $neededLength ? $this->length : $neededLength;

        $output = 0;
        $base = strlen($this->alphabet);
        $length = strlen($input) - 1;

        for ($current = $length; $current >= 0; $current--) {
            $powed = pow($base, $length - $current);
            $output = ($output + strpos($this->alphabet, substr($input, $current, 1)) * $powed);
        }

        if (is_numeric($neededLength)) {
            $neededLength--;
            if ($neededLength > 0) {
                $output -= pow($base, $neededLength);
            }
        }

        return $output;
    }
}