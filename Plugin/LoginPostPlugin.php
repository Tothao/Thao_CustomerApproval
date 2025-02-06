<?php
namespace Thao\CustomerApproval\Plugin;
use Magento\Customer\Model\Session;
use Magento\Framework\Message\ManagerInterface;
use Thao\CustomerApproval\Helper\Data as Helper;
Class LoginPostPlugin {
    private $messageManager;
    private $customerSession;
    private $helper;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;
    public function __construct(
        Session $session,
        ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        Helper $helper
    ) {
        $this->customerSession = $session;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->helper = $helper;
    }
    public function afterExecute(
        \Magento\Customer\Controller\Account\LoginPost $subject,
        $result
    ){
        $isEnable = $this->helper->isEnableCustomerApproval();
        if(!$isEnable){
            return $result;
        }
        $isLoggedIn = $this->customerSession->isLoggedIn();
        if (!$isLoggedIn) {
            return $$result;
        }
        $customer = $this->customerSession->getCustomer();
        $approvalStatus = $customer->getApprovalStatus();
        if($approvalStatus == 2 || $approvalStatus == 0){
            $this->customerSession->logout();
            $this->messageManager->addErrorMessage(__('Your account has not been approved yet. Please contact the administrator.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('customer/account/login');
        }
        return $result;

    }
}
