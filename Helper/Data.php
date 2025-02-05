<?php
namespace Thao\CustomerApproval\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\LocalizedException;
class Data extends AbstractHelper

{
    protected $transportBuilder;
    protected $scopeConfig;
    protected $storeManager;
    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }
    public function isEnableCustomerApproval()
    {
        $valueFromConfig = $this->scopeConfig->getValue(
            'customer_approval/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );

        return $valueFromConfig;
    }
    public function sendMail(){

    }

}
