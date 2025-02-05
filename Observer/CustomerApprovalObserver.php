<?php
namespace Thao\CustomerApproval\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
Class CustomerApprovalObserver implements ObserverInterface{

    private $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }
    public function execute(Observer $observer){
        $customer = $observer->getEvent()->getCustomer();
        $customer->setCustomAttribute('approval_status', 2);
        $this->customerRepository->save($customer);
    }
}
