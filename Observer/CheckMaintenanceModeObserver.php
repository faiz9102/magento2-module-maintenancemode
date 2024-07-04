<?php

namespace Faiz\MaintenanceMode\Observer;

use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\ActionInterface;
use Faiz\MaintenanceMode\Helper\Data as MaintenanceModeHelper;

class CheckMaintenanceModeObserver implements ObserverInterface
{
    protected $redirect;
    protected $url;
    protected $request;
    protected $actionFlag;
    protected $helper;

    public function __construct(
        RedirectInterface $redirect,
        UrlInterface $url,
        RequestInterface $request,
        ActionFlag $actionFlag,
        MaintenanceModeHelper $maintenanceModeHelper
    ) {
        $this->redirect = $redirect;
        $this->url = $url;
        $this->request = $request;
        $this->actionFlag = $actionFlag;
        $this->helper = $maintenanceModeHelper;
    }
    public function execute(Observer $observer)
    {
        $isMaintenanceModeEnabled = $this->helper->getMaintenanceModeStatus();
        $allowedIps = $this->helper->getWhitelistIP();
        $clientIp = $this->request->getServer('REMOTE_ADDR');

        if (in_array($clientIp, $allowedIps)) {
            return;
        }

        $requestedPath = $this->request->getPathInfo();

        // If maintenance mode is off and user tries to access the maintenance page
        if (!$isMaintenanceModeEnabled && strpos($requestedPath, 'maintenance') !== false) {
            $this->redirectUser($observer, '');
        }
        // If maintenance mode is enabled and user is not on the maintenance page
        elseif ($isMaintenanceModeEnabled && strpos($requestedPath, 'maintenance') === false) {
            $this->redirectUser($observer, 'maintenance');
        }
    }
    protected function redirectUser(Observer $observer, $path)
    {
        $controller = $observer->getControllerAction();
        $response = $controller->getResponse();
        $redirectUrl = $this->url->getUrl($path);

        $this->redirect->redirect($response, $redirectUrl);
        $this->actionFlag->set('', ActionInterface::FLAG_NO_DISPATCH, true);
    }
}
