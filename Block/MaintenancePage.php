<?php

namespace Faiz\MaintenanceMode\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Faiz\MaintenanceMode\Helper\Data as MaintenanceModeHelper;

class MaintenancePage extends Template
{
    protected $dateTime;
    protected $helper;

    public function __construct(
        Context $context,
        MaintenanceModeHelper $helper,
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function getStartTimeString()
    {
        return $this->helper->getStartTime();
    }

    public function getEndTimeString()
    {
        return $this->helper->getEndTime();
    }

    public function timeStringToStamp($timeString): int
    {
        return strtotime($timeString);
    }

    public function getStartTimeStamp()
    {
        return $this->timeStringToStamp($this->getStartTimeString());
    }

    public function getEndTimeStamp()
    {
        return $this->timeStringToStamp($this->getEndTimeString());
    }

    public function getMaintenanceModeStatus()
    {
        return $this->helper->getMaintenanceModeStatus();
    }

    public function getTimeOffset(): int
    {
        return $this->dateTime->getGmtOffset();
    }

    public function getHtmlContent(): string
    {
        return $this->helper->getMaintenancePageHtml();
    }
    public function getBackgroundImageUrl()
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => 'media']) . $this->helper::MEDIA_DIR . $this->helper->getMaintenancePageBackgroundImage();
    }
    public function getBackgroundColor(): string
    {
        return $this->helper->getMaintenancePageBackgroundColor();
    }
    public function isCountEnabled()
    {
        return $this->helper->isMaintenancePageCountdownEnabled();
    }
}
