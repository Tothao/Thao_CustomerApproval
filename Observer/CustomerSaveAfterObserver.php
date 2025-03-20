<?php

namespace Thao\CustomerApproval\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Thao\CustomerApproval\Helper\Data as Helper;

class CustomerSaveAfterObserver implements ObserverInterface
{
    private $customerRepository;
    private $helper;

    public function __construct(CustomerRepositoryInterface $customerRepository, helper $helper)
    {
        $this->customerRepository = $customerRepository;
        $this->helper = $helper;
    }

    public function execute(Observer $observer)
    {
        $isSendEmail= $this->helper->isSendEmail();
        $customerData = $observer->getEvent()->getData('customer_data_object');
        $prevCustomerData = $observer->getEvent()->getData('orig_customer_data_object');
        $prevApproveStatus = $prevCustomerData->getCustomAttribute('approval_status')->getValue();
        $currentApproveStatus = $customerData->getCustomAttribute('approval_status')->getValue();
        if ($prevApproveStatus == $currentApproveStatus) {
            return;
        }
        if(!$isSendEmail){

        }else {
            if ($currentApproveStatus == Helper::APPROVED_STATUS) {
                $this->helper->sendMail($customerData, 'approve');
            }
            if ($currentApproveStatus == 0) {
                $this->helper->sendMail($prevCustomerData, 'reject');
            }
        }

    }
}
