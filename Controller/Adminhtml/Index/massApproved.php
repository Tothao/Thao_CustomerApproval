<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Thao\CustomerApproval\Controller\Adminhtml\Index;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Controller\Adminhtml\Index\AbstractMassAction;


/**
 * Class MassDelete
 */
class MassApproved extends AbstractMassAction implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Customer::delete';

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    protected $emailHelper;
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CustomerRepositoryInterface $customerRepository,
        EmailHelper $emailHelper
    ) {
        parent::__construct($context, $filter, $collectionFactory);
        $this->customerRepository = $customerRepository;
        $this->emailHelper = $emailHelper;
    }

    /**
     * @inheritdoc
     */
    protected function massAction(AbstractCollection $collection)
    {
        $customersApproved = 0;
        foreach ($collection->getAllIds() as $customerId) {
            $customer = $this->customerRepository->getById($customerId);
            $customer->setCustomAttribute('approval_status',1);
            $this->customerRepository->save($customer);
            $this->emailHelper->sendMail($customer);
            $customersApproved++;
        }

        if ($customersApproved) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were approved.', $customersApproved));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}
