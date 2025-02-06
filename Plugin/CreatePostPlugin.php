<?php
namespace Thao\CustomerApproval\Plugin;
use Magento\Customer\Model\Session;
use Magento\Framework\Message\ManagerInterface;
use Thao\CustomerApproval\Helper\Data as Helper;
Class CreatePostPlugin {
    protected $messageManager;
    protected $customerSession;
    protected $helper;
    public function __construct(
        Session $customerSession,
        ManagerInterface $messageManager,
        Helper $helper
    )
    {
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->helper = $helper;
    }
    public function afterExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        $result
    )
    {
        $isEnable = $this->helper->isEnableCustomerApproval();
        if(!$isEnable){
            return $result;
        }
        $customer = $this->customerSession->getCustomer();
        if(!$customer->getId()){
            return $result;
        }$this->messageManager->addSuccessMessage(__('Registration successful! Please wait for approval.'));
        $this->customerSession->logout();
        return $result;
    }
}
