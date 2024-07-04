<?php
declare(strict_types=1);

namespace Faiz\MaintenanceMode\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Faiz\MaintenanceMode\Helper\Data as MaintenanceModeHelper;

/**
 * Controller for the 'maintenance/index/index' URL route.
 */
class Index extends Action implements HttpGetActionInterface
{
    protected $helper;
    protected $resultFactory;

    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        MaintenanceModeHelper $helper
    ) {
        $this->resultFactory = $resultFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Execute controller action.
     */
    public function execute()
    {
        $title = $this->helper->getMaintenancePageTitle();

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if (isset($title) && $title !== "") {
            $resultPage->getConfig()->getTitle()->set($title);
        }
        if($this->helper->getMaintenanceModeStatus())
            $this->getResponse()->setHttpResponseCode(503);

        return $resultPage;
    }
}
