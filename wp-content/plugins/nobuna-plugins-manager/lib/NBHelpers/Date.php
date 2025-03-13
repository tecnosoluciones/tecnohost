<?php

namespace NBHelpers;

use DateTime;
use DateTimeZone;
use DateInterval;

class Date {
    
    const MYSQL_FORMAT = 'Y-m-d H:i:s';
    
    /**
     * @param DateTime $origin
     * @return DateTime
     */
    public static function CloneToUTC(DateTime $origin) {
        $result = clone $origin;
        $result->setTimezone(new DateTimeZone('UTC'));
        return $result;
    }
    
    /**
     * @return DateTime
     */
    public static function UtcNow() {
        return static::Now('UTC');
    }
    
    public static function TimestampFromUTCMysqlString($date_str) {
        $date = static::UtcDateFromMysqlDateTime($date_str);
        return $date->getTimestamp();
    }
    
    /**
     * 
     * @return string
     */
    public static function UtcNowMysqlFormatted() {
        $date = static::UtcNow();
        $str = $date->format(static::MYSQL_FORMAT);
        return $str;
    }
    
    /**
     * 
     * @param string $timezone
     * @return DateTime
     */
    public static function Now($timezone) {
        return new DateTime('now', new DateTimeZone($timezone));
    }
    
    /**
     * 
     * @param string $datetime
     * @return DateTime
     */
    public static function UtcDateFromMysqlDateTime($datetime) {
        return DateTime::createFromFormat(static::MYSQL_FORMAT, $datetime, new DateTimeZone('UTC'));
    }
    
    /**
     * 
     * @param DateTime $origin
     * @param string $zoneString
     * @return DateTime
     */
    public static function CloneToCustomZone(DateTime $origin, $zoneString) {
        $result = clone $origin;
        $result->setTimezone(new DateTimeZone($zoneString));
        return $result;
    }
    
    /**
     * 
     * @param DateTime $origin
     */
    public static function AddOneDay(DateTime $origin) {
        static::AddDays($origin, 1);
    }
    
    /**
     * 
     * @param DateTime $origin
     * @param int $weeks
     */
    public static function AddWeeks(DateTime $origin, $weeks) {
        static::AddDays($origin, $weeks*7);
    }
    
    /**
     * 
     * @param DateTime $origin
     * @param int $days
     */
    public static function AddDays(DateTime $origin, $days) {
        $origin->add(new DateInterval('P' . $days . 'D'));
    }
    
    /**
     * 
     * @param DateTime $origin
     * @param int $seconds
     */
    public static function AddSeconds(DateTime $origin, $seconds) {
        $origin->add(new DateInterval('PT' . $seconds . 'S'));
    }
    
    /**
     * 
     * @param DateTime $origin
     * @param int $days
     */
    public static function SubsDays(DateTime $origin, $days) {
        $origin->sub(new \DateInterval('P' . $days . 'D'));
    }
    
    /**
     * 
     * @param DateTimeZone $timezone
     * @return DateTime
     */
    public static function GetCurrentWeek(DateTimeZone $timezone) {
        $result = new DateTime('now', $timezone);
        static::GetWeekFirstDay($result);
        return $result;
    }
    
    /**
     * 
     * @param DateTime $origin
     * @return DateTime
     */
    public static function GetWeekFirstDay(DateTime $origin) {
        $origin_copy = clone $origin;
        $weekDay = intval($origin_copy->format('N'));
        if($weekDay !== 1) {
            static::SubsDays($origin_copy, $weekDay-1);
        }
        $weekDate = DateTime::createFromFormat('Y-m-d H:i:s', 
                $origin_copy->format('Y-m-d 00:00:00'), $origin_copy->getTimezone());
        return $weekDate;
    }
    
}
