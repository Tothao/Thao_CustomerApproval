<?php

namespace Thao\CustomerApproval\Ui\Component\Listing;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class CustomerGrid extends \Magento\Customer\Ui\Component\Listing\Listing
{
    protected $customerCollectionFactory;

    public function __construct(
        CollectionFactory $customerCollectionFactory,
        ContextInterface $context,
        Collection $collection,
        array $components = [],
        array $data = []
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
        parent::__construct($context, $components, $data);
    }

    public function prepare()
    {
        parent::prepare();

        // Thêm cột 'approval_status' vào Grid
        $this->addColumn('approval_status', [
            'header' => __('Approval Status'),
            'index' => 'approval_status',
            'renderer' => 'Thao\CustomerApproval\Ui\Component\Listing\Column\Approval',
            'sortable' => true,
            'filter' => false,
            'width' => '150',
        ]);
    }
}
