<?php
function countBusinessDays($start, $stop)
{
    require_once('BusinessDayPeriodIterator.php');
    if($start > $stop){
        $tmpStart = clone $start;
        $start = clone $stop;
        $stop = clone $tmpStart;
    }

    // Adding the time to the end date will include it
    $period = new \DatePeriod($start->setTime(0,0,0), new \DateInterval('P1D'), $stop->setTime(23,59,59), \DatePeriod::EXCLUDE_START_DATE);
    $periodIterator = new BusinessDayPeriodIterator($period);
    $businessDays = 0;
    while($periodIterator->valid()){
        // If we run into a weekend, don't count it
        if(!$periodIterator->isWeekend()){
            $businessDays++;
        }
        $periodIterator->next();
    }

    return $businessDays;
}
?>