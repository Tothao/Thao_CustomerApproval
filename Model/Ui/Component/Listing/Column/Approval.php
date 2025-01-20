<?php

namespace Thao\CustomerApproval\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Approval extends Column
{
    /**
     * Render the approval status column value
     *
     * @param array $item
     * @return string
     */
    public function render(\Magento\Framework\DataObject $item)
    {
        $approvalStatus = $item->getData($this->getName());

        // Trả về giá trị trạng thái approval_status theo ID
        switch ($approvalStatus) {
            case 1:
                return 'Approved';
            case 2:
                return 'Pending';
            case 3:
                return 'Rejected';
            default:
                return 'Unknown';
        }
    }
}
