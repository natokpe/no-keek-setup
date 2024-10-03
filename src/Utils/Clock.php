<?php
declare(strict_types = 1);

namespace NatOkpe\Wp\Plugin\KeekSetup\Utils;

use \DateTime;
use \DateTimeZone;

/**
 * 
 */
class Clock
{
    /**
     * 
     */
    public static
    function now(string $tz = 'UTC'): float
    {
        $dt = new DateTime('now', new DateTimeZone($tz));
        return (float) $dt->getTimestamp();
    }

    /**
     * 
     */
    public static
    function nowYear(string $tz = 'UTC'): string
    {
        return (new DateTime('now', new DateTimeZone($tz)))->format('Y');
    }
}
