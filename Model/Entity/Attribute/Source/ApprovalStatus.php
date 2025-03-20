<?php
namespace Thao\CustomerApproval\Model\Entity\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class ApprovalStatus extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => \Thao\CustomerApproval\Helper\Data::REJECTED_STATUS, 'label' => __('Rejected')],
                ['value' => \Thao\CustomerApproval\Helper\Data::APPROVED_STATUS, 'label' => __('Approved')],
                ['value' => \Thao\CustomerApproval\Helper\Data::PENDING_STATUS, 'label' => __('Pending')],
            ];
        }
        return $this->_options;
    }
}
