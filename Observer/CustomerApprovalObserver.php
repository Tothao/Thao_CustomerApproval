<?php

namespace Thao\CustomerApproval\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CustomerApprovalObserver implements ObserverInterface
{
    protected $customerRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Xử lý sự kiện khi khách hàng được lưu.
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer(); // Lấy đối tượng khách hàng từ sự kiện

        // Kiểm tra xem khách hàng có thuộc tính approval_status không và nếu có, lưu giá trị
        if ($customer->getCustomAttribute('approval_status')) {
            $approvalStatus = $customer->getCustomAttribute('approval_status')->getValue();

            // Thực hiện các hành động khác nếu cần, ví dụ: kiểm tra hoặc thay đổi giá trị
            // Ví dụ, nếu chưa có giá trị approval_status, đặt mặc định
            if ($approvalStatus === null) {
                $customer->setCustomAttribute('approval_status', 2); // Giá trị mặc định là 'Đang xét duyệt'
            }

            // Lưu khách hàng lại
            $this->customerRepository->save($customer);
        }
    }
}
