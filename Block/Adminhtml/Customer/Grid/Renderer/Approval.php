<?php
namespace Thao\CustomerApproval\Block\Adminhtml\Customer\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Thao\CustomerApproval\Model\Entity\Attribute\Source\ApprovalStatus;

class Approval extends AbstractRenderer
{
    /**
     * @var ApprovalStatus
     */
    protected $approvalStatus;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param ApprovalStatus $approvalStatus
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        ApprovalStatus $approvalStatus,
        array $data = []
    ) {
        $this->approvalStatus = $approvalStatus;
        parent::__construct($context, $data);
    }

    /**
     * Render approval status value for grid
     *
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
        $approvalStatusValue = $row->getData('approval_status'); // Lấy giá trị của approval_status từ row dữ liệu
        $statusOptions = $this->approvalStatus->getOptionArray(); // Lấy tất cả các giá trị từ nguồn ApprovalStatus

        // Trả về tên tương ứng với giá trị của approval_status, hoặc 'Unknown' nếu không tìm thấy
        return isset($statusOptions[$approvalStatusValue]) ? $statusOptions[$approvalStatusValue] : __('Unknown');
    }
}
