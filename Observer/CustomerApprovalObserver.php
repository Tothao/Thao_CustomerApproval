<?php

namespace Thao\CustomerApproval\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Customer\Model\ResourceModel\Customer\AttributeFactory as CustomerAttributeFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\ResourceConnection;

class CustomerApprovalObserver implements ObserverInterface
{
    protected $customerFactory;
    protected $customerResource;
    protected $eavConfig;
    protected $resourceConnection;

    public function __construct(
        CustomerFactory $customerFactory,
        CustomerResource $customerResource,
        EavConfig $eavConfig,
        ResourceConnection $resourceConnection
    ) {
        $this->customerFactory = $customerFactory;
        $this->customerResource = $customerResource;
        $this->eavConfig = $eavConfig;
        $this->resourceConnection = $resourceConnection;
    }

    public function execute(Observer $observer)
    {
        // Lấy khách hàng từ sự kiện
        $customer = $observer->getEvent()->getCustomer();

        if ($customer) {
            // Kiểm tra xem khách hàng có phải là khách hàng mới hay không
            if ($customer->isObjectNew()) {
                // Cập nhật trạng thái approval_status (2 - Pending)
                $customer->setApprovalStatus(2);  // 2 - Pending
                $this->customerResource->save($customer);
            }

            // Cập nhật bảng customer_entity_int
            try {
                $this->updateApprovalStatusInEntityInt($customer);
            } catch (\Exception $e) {
                // Xử lý lỗi nếu không thể lưu trạng thái approval_status
                \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\Session\SessionManagerInterface::class)
                    ->addErrorMessage(__('Unable to update approval status.'));
            }
        }
    }

    protected function updateApprovalStatusInEntityInt($customer)
    {
        // Lấy giá trị approval_status
        $approvalStatus = $customer->getApprovalStatus();

        // Lấy ID của thuộc tính approval_status
        $attributeId = $this->getAttributeId('approval_status');

        // Tạo giá trị dữ liệu cho bảng customer_entity_int
        $data = [
            'entity_id' => $customer->getId(),
            'attribute_id' => $attributeId,
            'value' => $approvalStatus
        ];

        // Lưu vào bảng customer_entity_int
        $connection = $this->resourceConnection->getConnection();
        $table = $this->resourceConnection->getTableName('customer_entity_int');
        $connection->insertOnDuplicate($table, $data, ['value']);
    }

    protected function getAttributeId($attributeCode)
    {
        // Lấy ID của thuộc tính approval_status từ bảng EAV
        $attribute = $this->eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, $attributeCode);
        return $attribute->getId();
    }
}
