<?php
namespace Thao\CustomerApproval\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Thao\CustomerApproval\Helper\Data as Helper;

Class CustomerApprovalObserver implements ObserverInterface{

    private $customerRepository;
    private $helper;
    public function __construct(CustomerRepositoryInterface $customerRepository,
                                helper $helper)
    {
        $this->customerRepository = $customerRepository;
        $this->helper = $helper;
    }

    public function execute(Observer $observer, ){
        $customerData = $observer->getEvent()->getData('customer_data_object');
        $prevCustomerData = $observer->getEvent()->getData('orig_customer_data_object');
        $prevApproveStatus = $prevCustomerData->getApproveStatus();
        $currentApproveStatus = $customerData->getApproveStatus();
        if($prevApproveStatus == $currentApproveStatus){
            return;
        }
        if($currentApproveStatus==1){
            $this->helper->sendMail($customerData, 1);
        }
        if($currentApproveStatus==0){
            $this->helper->sendMail($prevCustomerData, 0);
        }

    }
}
