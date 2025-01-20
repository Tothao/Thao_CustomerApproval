<?php
namespace Thao\CustomerApproval\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Thao\CustomerApproval\Model\CustomerFactory;

class CustomerRegisterSuccess implements ObserverInterface
{
    protected $customerFactory;
    public function __construct(CustomerFactory $customerFactory)
    {
        $this->customerFactory = $customerFactory;
    }
    public function execute(Observer $observer)
    {

        $customer = $observer->getEvent()->getCustomer();
        $customer ->setApprovalStatus(2);
        $customer->save();

    }
}

