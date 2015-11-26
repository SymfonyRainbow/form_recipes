<?php

namespace Rainbow\FormBundle\Form;

final class SuffixConverter
{
    private static $sizes = array(
        'GB' => 1073741824,
        'MB' => 1048576,
        'KB' => 1024,
        '' => 1,
    );

    /**
     * @param $value
     *
     * @return array
     */
    public static function toSuffix($value)
    {
        foreach (self::$sizes as $suffix => $size) {
            if ($value % $size == 0) {
                $ret['value'] = $value / $size;
                $ret['suffix'] = $suffix;

                return $ret;
            }
        }

        throw new \InvalidArgumentException("Cannot convert size to a value/suffix pair");
    }

    /**
     * @param $value
     * @param $suffix
     *
     * @return float
     */
    public static function fromSuffix($value, $suffix)
    {
        if (in_array($suffix, array_keys(self::$sizes))) {
            return $value * self::$sizes[$suffix];
        }

        throw new \InvalidArgumentException(sprintf("Suffix %s is not a supported suffix", $suffix));
    }
}
