<?php

namespace Thao\CustomerApproval\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class ApprovalStatus extends Column
{
    private $options = [
        0 => 'Rejected',
        1 => 'Approved',
        2 => 'Pending'
    ];

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['approval_status'])) {
                    $status = (int) $item['approval_status'];
                    $item['approval_status'] = $this->options[$status] ?? __('Pending');
                }
            }
        }
        return $dataSource;
    }
}
