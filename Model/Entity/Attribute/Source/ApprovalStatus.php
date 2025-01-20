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
                ['value' => '0', 'label' => __('Từ chối')],
                ['value' => '1', 'label' => __('Phê duyệt')],
                ['value' => '2', 'label' => __('Đang xét duyệt')],
            ];
        }
        return $this->_options;
    }
}
