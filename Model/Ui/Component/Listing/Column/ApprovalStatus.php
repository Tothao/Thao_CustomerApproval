<?php

namespace Thao\CustomerApproval\Ui\Component\Listing\Column;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ApprovalStatus extends Column
{
    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface            $context,
        UiComponentFactory          $uiComponentFactory,
        CustomerRepositoryInterface $customerRepository,
        array                       $components = [],
        array                       $data = []
    ) {
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * PrepareDataSource
     *
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        $dataSource = parent::prepareDataSource($dataSource);
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if ($customerId = $item['entity_id']) {
                    /** @var Customer $customer */
                    if ($customer = $this->customerRepository->getById($customerId)) {
                        if ($data = $customer->getCustomAttribute('approval_status')) {
                            $item[$this->getData('name')] = $data->getValue() ? (int)$data->getValue() : '';
                        }
                    }
                }
            }
        }
        return $dataSource;
    }
}
