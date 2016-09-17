<?php

/**
 * Manage date
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib;

use \DateTime as DateTime;

/**
 * This class manage the date
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Date
{
    /**
     * set name of image
     *
     * @access public
     * @param  int $iWeek number of week
     * @param  int $iYear year
     * @param string $sFormat
     * @return Date
     */
    public static function getWeek(int $iWeek, int $iYear, string $sFormat = "Y-m-d") : Date
    {
        $iFirstDayInYear = date("N",mktime(0, 0, 0, 1, 1, $iYear));

        if ($iFirstDayInYear < 5) { $iShift = -($iFirstDayInYear - 1) * 86400; } else { $iShift = (8 - $iFirstDayInYear) * 86400; }

        if ($iWeek > 1) { $iWeekInSeconds = ($iWeek-1) * 604800; } else { $iWeekInSeconds = 0; }

        $iTimestamp = mktime(0, 0, 0, 1, 1, $iYear) + $iWeekInSeconds + $iShift;
        $iTimestampLastDay = mktime(0, 0, 0, 1, 6, $iYear) + $iWeekInSeconds + $iShift + 604800;

        return array(date($sFormat, $iTimestamp), date($sFormat, $iTimestampLastDay));
    }

    /**
     * set name of image
     *
     * @access public
     * @return \Venus\lib\Date
     */
    public static function getActualWeek() : Date
    {
        return self::getWeek(date('W'), date('Y'));
    }

    /**
     * set name of image
     *
     * @access public
     * @param  string $sMonth number of week
     * @param  string $sLanguage language
     * @return \Venus\lib\Date
     */
    public static function getMonthInWord(string $sMonth, string $sLanguage = 'fr') : Date
    {
        if ($sLanguage == 'fr') {

            if ($sMonth == '01' || $sMonth == 1) { return 'Janvier'; }
            else if ($sMonth == '02' || $sMonth == 2) { return 'Février'; }
            else if ($sMonth == '03' || $sMonth == 3) { return 'Mars'; }
            else if ($sMonth == '04' || $sMonth == 4) { return 'Avril'; }
            else if ($sMonth == '05' || $sMonth == 5) { return 'Mai'; }
            else if ($sMonth == '06' || $sMonth == 6) { return 'Juin'; }
            else if ($sMonth == '07' || $sMonth == 7) { return 'Juillet'; }
            else if ($sMonth == '08' || $sMonth == 8) { return 'Août'; }
            else if ($sMonth == '09' || $sMonth == 9) { return 'Septembre'; }
            else if ($sMonth == 10) { return 'Octobre'; }
            else if ($sMonth == 11) { return 'Novembre'; }
            else if ($sMonth == 12) { return 'Décembre'; }
        }
    }

    /**
     * set name of image
     *
     * @access public
     * @param  mixed $sDay number of day
     * @param  string $sLanguage language
     * @return \Venus\lib\Date
     */
    public static function getDayInWord(string $sDay, string $sLanguage = 'fr') : Date
    {
        if ($sLanguage == 'fr') {

            if ($sDay == 0) { return 'dimanche'; }
            else if ($sDay == 1) { return 'lundi'; }
            else if ($sDay == 2) { return 'mardi'; }
            else if ($sDay == 3) { return 'mercredi'; }
            else if ($sDay == 4) { return 'jeudi'; }
            else if ($sDay == 5) { return 'vendredi'; }
            else if ($sDay == 6) { return 'samedi'; }
        }
    }

    /**
     * get age by date
     *
     * @access public
     * @param string $sBirthday
     * @return int
     */
    public static function getAgeByDate(string $sBirthday) : int
    {
        list($iYear, $iMonth, $iDay) = preg_split('/[-.]/', $sBirthday);

        $aToday = array();
        $aToday['mois'] = date('n');
        $aToday['jour'] = date('j');
        $aToday['annee'] = date('Y');

        $iYears = $aToday['annee'] - $iYear;

        if ($aToday['mois'] <= $iMonth) {

            if ($iMonth == $aToday['mois']) {

                if ($iDay > $aToday['jour']) { $iYears--; }
            }
            else {

                $iYears--;
            }
        }

        return $iYears;
    }

    /**
     * set name of image
     *
     * @access public
     * @param  int $iWeek number of week
     * @param  int $iYear year
     * @param string $sFormat
     * @return array|Date
     */
    public static function getMiddleWeek(int $iWeek, int $iYear, string $sFormat = "Y-m-d") : array
    {
        $iFirstDayInYear = date("N",mktime(0, 0, 0, 1, 1, $iYear));

        if ($iFirstDayInYear < 5) { $iShift = -($iFirstDayInYear - 1) * 86400; }
        else { $iShift = (8 - $iFirstDayInYear) * 86400; }

        if ($iWeek > 1) { $iWeekInSeconds = ($iWeek-1) * 604800; }
        else { $iWeekInSeconds = 0; }

        if (date('N') > 2) {

            $iTimestamp = mktime(0, 0, 0, 1, 1, $iYear) + $iWeekInSeconds + $iShift + 172800;
            $iTimestampLastDay = $iTimestamp + 604800;
        }
        else {

            $iTimestamp = mktime(0, 0, 0, 1, 1, $iYear) + $iWeekInSeconds + $iShift - 432000;
            $iTimestampLastDay = $iTimestamp + 604800;
        }

        $aDates = array(date($sFormat, $iTimestamp), date($sFormat, $iTimestampLastDay));

        if (preg_replace('/^([0-9]+)-[0-9]+-[0-9]+$/', '$1', $aDates[0]) != date('Y')) {

            $aDates[0] = preg_replace('/^[0-9]+(-[0-9]+-[0-9]+)$/', date('Y').'$1', $aDates[0]);
            $aDates[1] = preg_replace('/^[0-9]+(-[0-9]+-[0-9]+)$/', (date('Y')+1).'$1', $aDates[1]);
        }

        return $aDates;
    }

    /**
     * set name of image
     *
     * @access public
     * @return array
     */
    public static function getActualMiddleWeek() : array
    {
        return self::getMiddleWeek(date('W'), date('Y'));
    }

    /**
     * get time of kind "X hour ago"
     *
     * @access public
     * @param  string $sDateTime datetime to convert
     * @param  string $sLanguage language
     * @return string
     */
    public static function getTimeAgoInString(string $sDateTime, string $sLanguage = 'fr') : string
    {
        if ($sLanguage == 'fr') {

            $sStartReturn = 'Il y a';
            $sEndReturn = '';
            $sMinutes = 'minute(s) ';
            $sHours = 'heure(s) ';
            $sDays = 'jour(s) ';
            $sMonths = 'mois ';
            $sYears = 'années ';
        } else {
            $sStartReturn = 'Ago';
            $sEndReturn = '';
            $sMinutes = 'minute(s) ';
            $sHours = 'hour(s) ';
            $sDays = 'day(s) ';
            $sMonths = 'month ';
            $sYears = 'years ';
        }

        $oDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $sDateTime);
        $iTimeStamp = time() - $oDateTime->getTimestamp();

        if ($iTimeStamp < 3600) { return $sStartReturn.' '.(int)($iTimeStamp/60).' '.$sMinutes.$sEndReturn; }
        if ($iTimeStamp < 86400) { return $sStartReturn.' '.(int)($iTimeStamp/3600).' '.$sHours.$sEndReturn; }
        if ($iTimeStamp < 2592000) { return $sStartReturn.' '.(int)($iTimeStamp/86400).' '.$sDays.$sEndReturn; }
        if ($iTimeStamp < 31536000) { return $sStartReturn.' '.(int)($iTimeStamp/2592000).' '.$sMonths.$sEndReturn; }
        else { return $sStartReturn.' '.(int)($iTimeStamp/31536000).' '.$sYears.$sEndReturn; }
    }
}
