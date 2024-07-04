<?php

namespace Faiz\MaintenanceMode\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Data extends AbstractHelper
{
    protected $dateTime;
    protected $scopeConfig;

    const MEDIA_DIR = 'faiz/';
    /**
     * Module Settings values path
     */
    const XML_PATH_MAINTENANCEMODE_SETTINGS = 'maintenance/settings/';

    /**
     * Module Configuration values path
     */
    const XML_PATH_MAINTENANCEMODE_CONFIG= 'maintenance/configuration/';

    /**
     * Maintenance Page configuration values path
     */
    const XML_PATH_MAINTENANCEMODE_CONFIG_MAINTENANCE_PAGE = self::XML_PATH_MAINTENANCEMODE_CONFIG . 'maintenance_page/';

    /**
     * Notifier Strip configuration values path
     */
    const XML_PATH_MAINTENANCEMODE_CONFIG_NOTIFIER = self::XML_PATH_MAINTENANCEMODE_CONFIG . 'notifier/';

    /**
     * @param Context $context
     * @param DateTime $dateTime
     */
    public function __construct(Context $context, DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * Returns $field config value
     *
     * @param $field
     * @return mixed
     */
    public function getConfigValue($field)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Notifier Strip Enable Status
     *
     * @return bool
     */
    public function isNotifierEnabled(): bool
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_SETTINGS . "notifier_enabled");
    }

    /**
     * Get Module Status
     *
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_SETTINGS . "is_enabled");
    }

    /**
     * Returns TRUE only in the Maintenance time range.
     * Returns FALSE if module is disabled or time is not in Maintenance time range.
     *
     * @return bool
     */
    public function getMaintenanceModeStatus(): bool
    {
        if (!$this->isModuleEnabled()) {
            return false;
        }

        $startTime = $this->getStartTimeUTC();
        $currentTime = $this->dateTime->timestamp() + $this->dateTime->getGmtOffset();
        $endTime = $this->getEndTimeUTC();

        return ($endTime > $currentTime && $startTime <= $currentTime);
    }

    /**
     * Returns Start Time String of Maintenance Mode.
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_SETTINGS . "start_time");
    }

    /**
     * Returns End Time String of Maintenance Mode.
     *
     * @return string
     */
    public function getEndTime()
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_SETTINGS . "end_time");
    }

    /**
     * Returns Start Timestamp of Maintenance Mode.
     *
     * @return int
     */
    public function getStartTimeUTC(): int
    {
        return strtotime($this->getStartTime());
    }

    /**
     * Returns End Timestamp of Maintenance Mode.
     *
     * @return int
     */
    public function getEndTimeUTC(): int
    {
        return strtotime($this->getEndTime());
    }

    /**
     * Returns TRUE if Notifier strip is enabled for frontend.
     *
     * @return bool
     */
    public function getNotifierEnabled(): bool
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_SETTINGS . 'notifier');
    }

    /**
     * Returns Background Color for Notifier Strip.
     *
     * @return string
     */
    public function getNotifierBackgroundColor(): string
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_CONFIG_NOTIFIER . 'bg_color');
    }

    /**
     * Returns String to display in Notifier.
     *
     * @return string
     */
    public function getNotifierTextContent(): string
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_CONFIG_NOTIFIER . "text_content");
    }

    /**
     * Returns Color for text of Notifier.
     *
     * @return string
     */
    public function getNotifierTextColor(): string
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_CONFIG_NOTIFIER . 'text_color');
    }

    /**
     * Returns HTML content for Maintenance Page.
     *
     * @return string
     */
    public function getMaintenancePageHtml(): string
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_CONFIG_MAINTENANCE_PAGE . 'html_content');
    }

    /**
     * Returns Array of Whitelisted IPs.
     *
     * @return array
     */
    public function getWhitelistIP(): array
    {
        $ipString = $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_SETTINGS . 'whitelist_ip');
        if (empty($ipString)) {
            return [];
        }

        $ip = explode(',', $ipString);
        return array_map('trim', $ip);
    }

    /**
     * Returns Maintenance Page Title
     *
     * @return string
     */
    public function getMaintenancePageTitle(): string
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_CONFIG_MAINTENANCE_PAGE . "page_title");
    }

    /**
     * Returns Maintenance Page Background color
     *
     * @return string
     */
    public function getMaintenancePageBackgroundColor() : string
    {
        return $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_CONFIG_MAINTENANCE_PAGE . 'bg_color');
    }

    /**
     * Returns Maintenance Page Background Image Name string
     *
     * @return string
     */
    public function getMaintenancePageBackgroundImage() : string
    {
        $img = $this->getConfigValue(self::XML_PATH_MAINTENANCEMODE_CONFIG_MAINTENANCE_PAGE . 'bg_image');

        if (!$img)
            return '';

        return $img;
    }

    /**
     * Returns Status for the Countdown timer on Maintenance Page
     *
     * @return bool
     */
    public function isMaintenancePageCountdownEnabled() :bool
    {
        return $this->getConfigValue( self::XML_PATH_MAINTENANCEMODE_CONFIG_MAINTENANCE_PAGE . 'countdown_enabled');
    }
}
