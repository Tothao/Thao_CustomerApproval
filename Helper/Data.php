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
    protected $transportBuilder;
    protected $scopeConfig;
    protected $storeManager;
    protected $inlineTranslation;
    protected $quoteRepository;

    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->quoteRepository = $quoteRepository;
    }

    public function sendMail($customer)
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
            $templateType = $this->scopeConfig->getValue(
                'customer_approval/general/email_template',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

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
                    'customer_name' => $customerName,
                    'quote_id' => $customer->getId(),
                ])
                ->setFrom($sender)
                ->addTo($customerEmail)
                ->getTransport();
            $transport->sendMessage();

            // Bật lại inline translation
            $this->inlineTranslation->resume();

            return true;
        } catch (\Exception $e) {
            // Bật lại inline translation nếu có lỗi xảy ra
            $this->inlineTranslation->resume();
            return $e->getMessage();
        }
    }
}
