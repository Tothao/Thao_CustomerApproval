<?php
namespace Thao\CustomerApproval\Ui\Component\Listing;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class DataProvider extends SearchResult
{
    protected $customerCollectionFactory;

    public function __construct(
        CollectionFactory $customerCollectionFactory,
        // Các dependency khác...
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
        parent::__construct();
    }

    /**
     * Fetch customer collection and join approval status from customer_entity_int
     */
    public function getData()
    {
        $collection = $this->customerCollectionFactory->create();

        // Join bảng customer_entity_int để lấy giá trị approval_status
        $collection->getSelect()->joinLeft(
            ['approval_status_table' => $collection->getTable('customer_entity_int')],
            'main_table.entity_id = approval_status_table.entity_id AND approval_status_table.attribute_id = :approval_status_attribute_id',
            ['approval_status' => 'approval_status_table.value']
        );

        // Thay :approval_status_attribute_id với ID của thuộc tính 'approval_status' trong bảng eav_attribute
        $approvalStatusAttributeId = $this->getApprovalStatusAttributeId();

        $collection->getSelect()->bind(['approval_status_attribute_id' => $approvalStatusAttributeId]);

        return $collection->getData();
    }

    /**
     * Lấy ID của thuộc tính 'approval_status' từ bảng eav_attribute
     */
    private function getApprovalStatusAttributeId()
    {
        $connection = $this->customerCollectionFactory->create()->getConnection();
        $select = $connection->select()
            ->from(['eav' => $connection->getTableName('eav_attribute')])
            ->where('eav.attribute_code = ?', 'approval_status')
            ->where('eav.entity_type_id = ?', 1); // 1 là ID cho customer entity

        $result = $connection->fetchRow($select);
        return $result ? $result['attribute_id'] : null;
    }
}
