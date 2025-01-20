<?php

namespace Thao\CustomerApproval\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class CustomerApprovalAtrribute implements DataPatchInterface
{
    private $moduleDataSetup;
    private $customerSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        // Thêm thuộc tính customer: approval_status
        $customerSetup->addAttribute(Customer::ENTITY, 'approval_status', [
            'type' => 'int',
            'label' => 'Approval Status',
            'input' => 'select',
            'source' => '\Thao\CustomerApproval\Model\Entity\Attribute\Source\ApprovalStatus',
            'required' => false,
            'default' => '2',
            'visible' => true,
            'user_defined' => true,
            'system' => false,
            'position' => 100,
            'group' => 'General',  // Thêm vào nhóm "General" nếu cần
        ]);

        $approvalStatusAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'approval_status');
        $approvalStatusAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $approvalStatusAttribute->save();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
