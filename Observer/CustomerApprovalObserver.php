<?php

namespace Thao\CustomerApproval\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Thao\CustomerApproval\Helper\Data as Helper;

class CustomerApprovalObserver implements ObserverInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * Constructor
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param Helper $helper
     */
    public function __construct(CustomerRepositoryInterface $customerRepository, Helper $helper)
    {
        $this->customerRepository = $customerRepository;
        $this->helper = $helper;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function execute(Observer $observer)
    {
        $isEnable = $this->helper->isEnableCustomerApproval();
        if ($isEnable) {
            $customer = $observer->getEvent()->getCustomer();
            $customer->setCustomAttribute('approval_status', Helper::PENDING_STATUS);
            $this->customerRepository->save($customer);
        }
    }
}
