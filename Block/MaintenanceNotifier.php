<?php

namespace Faiz\MaintenanceMode\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Faiz\MaintenanceMode\Helper\Data as MaintenanceModeHelper;
use Magento\Framework\Stdlib\DateTime\DateTime;

class MaintenanceNotifier extends Template
{
    protected $dateTime;
    protected $helper;

    public function __construct(Context $context, MaintenanceModeHelper $helper, DateTime $dateTime)
    {
        $this->helper = $helper;
        $this->dateTime = $dateTime;
        parent::__construct($context);
    }

    public function getTime()
    {
        return date("Y-m-d H:i:s", $this->dateTime->getGmtOffset() + $this->dateTime->timestamp());
    }

    public function getCurrentTime(): int
    {
        return $this->dateTime->getGmtOffset() + $this->dateTime->timestamp();
    }

    public function getMaintenanceModeStatus(): bool
    {
        return $this->helper->getMaintenanceModeStatus();
    }

    public function getStartTimeString(): string
    {
        return $this->helper->getStartTime();
    }

    public function getEndTimeString(): string
    {
        return $this->helper->getEndTime();
    }

    public function timeStringToStamp($timeString): int
    {
        return strtotime($timeString);
    }

    public function getTimeOffset() :int
    {
        return $this->dateTime->getGmtOffset();
    }
    public function getStartTimeStamp(): int
    {
        return $this->timeStringToStamp($this->getStartTimeString());
    }

    public function getEndTimeStamp(): int
    {
        return $this->timeStringToStamp($this->getEndTimeString());
    }

    public function getTimeDifference() : int
    {
        $currentTime = $this->getCurrentTime();
        $startTime = $this->getStartTimeStamp();
        $endTime = $this->getEndTimeStamp();

        if ($currentTime >= $endTime || $currentTime >= $startTime)
            return 0;

        return $startTime - $currentTime ;
    }

    public function getTextContent(): string
    {
        return $this->helper->getNotifierTextContent();
    }
    public function getTextColor(): string
    {
        return $this->helper->getNotifierTextColor();
    }
    public function getBackgroundColor() : string
    {
        return $this->helper->getNotifierBackgroundColor();
    }
}
