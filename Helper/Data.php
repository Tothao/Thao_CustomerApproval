<?php
namespace Thao\CustomerApproval\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class Data extends AbstractHelper
{
    const PENDING_STATUS = 2;
    const REJECTED_STATUS = 0;
    const APPROVED_STATUS = 1;
    protected $transportBuilder;
    protected $scopeConfig;
    protected $storeManager;
    protected $inlineTranslation;


    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,

    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
    }

    public function isEnableCustomerApproval()
    {
        $valueFromConfig = $this->scopeConfig->getValue(
            'customer_approval/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );

        return $valueFromConfig;
    }

    public function  isSendEmail(){
        $valuSendEmailConfig = $this->scopeConfig->getValue(
            'customer_approval/general/is_send_email_customer',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
        );
        return $valuSendEmailConfig;
    }

    public function sendMail($customer, $type)
    {
        try {
            // Tắt inline translation trước khi gửi email
            $this->inlineTranslation->suspend();
            $customerEmail = $customer->getEmail();
            $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
            $senderEmail = $this->scopeConfig
                ->getValue('trans_email/ident_general/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $senderName = $this->scopeConfig
                ->getValue('trans_email/ident_general/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
           if($type== 'approve'){
            $templateType = $this->scopeConfig->getValue(
                'customer_approval/general/email_template',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
           } else {
               $templateType = $this->scopeConfig->getValue(
                   'customer_approval/general/rejection_email_template',
                   \Magento\Store\Model\ScopeInterface::SCOPE_STORE
               );
           }

            $sender = [
                'name' => $senderName,
                'email' => $senderEmail,
            ];

            // Gửi email
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateType)
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ])
                ->setTemplateVars([
                    'name' => $customerName,
                    'email' => $customerEmail,
                ])
                ->setFrom($sender)
                ->addTo($customerEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();

            return true;
        } catch (\Exception $e) {

            $this->inlineTranslation->resume();
            return $e->getMessage();
        }
    }
}
