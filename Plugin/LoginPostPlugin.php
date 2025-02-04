<?php

namespace Thao\CustomerApproval\Plugin;

use Magento\Customer\Model\Session;
use Magento\Framework\Message\ManagerInterface;

class LoginPostPlugin
{
    /**
     * @var Session
     */
    private $custonmerSession;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    public function __construct(
        Session $session,
        ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
    ) {
        $this->custonmerSession = $session;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function afterExecute($subject, $result)
    {
        $isLoggedIn = $this->custonmerSession->isLoggedIn();
        if (!$isLoggedIn) {
            return $result;
        }

        $customer = $this->custonmerSession->getCustomer();

        $approveStatus = $customer->getApprovalStatus();

        if ($approveStatus == 2 || $approveStatus == 0) {
            $this->custonmerSession->logout();
            $this->messageManager->addErrorMessage(__('Your account is pending admin approval or has been disabled.'));
            $resultRedirect = $this->resultRedirectFactory->create()->setPath('customer/account/login');
            return $resultRedirect;
        }

        return $result;
    }
}
