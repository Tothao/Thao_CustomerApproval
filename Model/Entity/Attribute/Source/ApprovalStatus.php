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
                ['value' => '0', 'label' => __('Rejected')],
                ['value' => '1', 'label' => __('Approved')],
                ['value' => '2', 'label' => __('Pending')],
            ];
        }
        return $this->_options;
    }
}
