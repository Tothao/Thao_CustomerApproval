<?php

namespace Thao\CustomerApproval\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class CustomerRegisterSuccess implements ObserverInterface
{

    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $customer->setApprovalStatus(2);
        $customer->save();
    }
}
