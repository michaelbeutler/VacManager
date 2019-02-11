<?php
class BusinessDayPeriodIterator implements \Iterator
{
    private $current;
    private $period = [];
    public function __construct(\DatePeriod $period) {
        $this->period = $period;
        $this->current = $this->period->getStartDate();
        if(!$period->include_start_date){
            $this->next();
        }
        $this->endDate = $this->period->getEndDate();
    }
    public function rewind() {
        $this->current->subtract($this->period->getDateInterval());
    }
    public function current() {
        return clone $this->current;
    }
    public function key() {
        return $this->current->diff($this->period->getStartDate());
    }
    public function next() {
        $this->current->add($this->period->getDateInterval());
    }
    public function valid() {
        return $this->current < $this->endDate;
    }
    public function extend()
    {
        $this->endDate->add($this->period->getDateInterval());
    }
    public function isSaturday()
    {
        return $this->current->format('N') == 6;
    }
    public function isSunday()
    {
        return $this->current->format('N') == 7;
    }
    public function isWeekend()
    {
        return ($this->isSunday() || $this->isSaturday());
    }
}