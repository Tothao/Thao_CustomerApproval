<?php
namespace Thao\CustomerApproval\Plugin;
use Magento\Customer\Model\Session;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
Class CreatePostPlugin {
    protected $messageManager;
    protected $customerSession;
    protected $resultRedirectFactory;
    public function __construct(
        Session $customerSession,
        ManagerInterface $messageManager,
        RedirectFactory $resultRedirectFactory
    )
    {
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }
    public function afterExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        $result
    )
    {
        $customer = $this->customerSession->getCustomer();
        if(!$customer->getId()){
            return $result;
        }$this->messageManager->addSuccessMessage(__('Registration successful! Please wait for approval.'));
        $this->customerSession->logout();
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('/');
    }
}
