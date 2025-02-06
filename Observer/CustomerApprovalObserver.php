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

    public function execute(Observer $observer){
        $isEnable = $this->helper->isEnableCustomerApproval();
        if($isEnable){
            $customer = $observer->getEvent()->getCustomer();
            $customer->setCustomAttribute('approval_status', 2);
            $this->customerRepository->save($customer);
        }

    }
}
