<?php
namespace Zero1\MageworxSeoMarkupAndAmastyPreOrderCompat\Block\Head\Json;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use MageWorx\SeoAll\Helper\SeoFeaturesStatusProvider;

/**
 * Class Product
 */
class Product extends \MageWorx\SeoMarkup\Block\Head\Json\Product
{
    const PRE_ORDER = 'https://schema.org/PreOrder';

    /** @var \Amasty\PreOrder\Helper\Data */
    protected $amastyPreOrderHelper;

    /**
     * Product constructor.
     *
     * @param \Magento\Framework\Registry $registry
     * @param \MageWorx\SeoMarkup\Helper\Product $helperProduct
     * @param \MageWorx\SeoMarkup\Helper\DataProvider\Product $dataProviderProduct
     * @param \Magento\Catalog\Helper\Data $helperCatalog
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     * @param SeoFeaturesStatusProvider $seoFeaturesStatusProvider
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \MageWorx\SeoMarkup\Helper\Product $helperProduct,
        \MageWorx\SeoMarkup\Helper\DataProvider\Product $dataProviderProduct,
        \Magento\Catalog\Helper\Data $helperCatalog,
        \Magento\Framework\View\Element\Template\Context $context,
        PriceCurrencyInterface $priceCurrency,
        SeoFeaturesStatusProvider $seoFeaturesStatusProvider,
        \Amasty\Preorder\Helper\Data $amastyPreOrderHelper,
        array $data = []
    ) {
        $this->amastyPreOrderHelper = $amastyPreOrderHelper;
        return parent::__construct(
            $registry, 
            $helperProduct, 
            $dataProviderProduct,
            $helperCatalog,
            $context,
            $priceCurrency,
            $seoFeaturesStatusProvider,
            $data
        );
    }

    protected function getOfferData()
    {
        $data = parent::getOfferData();

        if(!$this->helperProduct->useMultipleOffer()
            || $this->_product->getTypeId() != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE
        ){
            if($this->amastyPreOrderHelper->getIsProductPreorder($this->_product)){
                $data['availability'] = self::PRE_ORDER;
            }
        }

        return $data;
    }

    protected function getChildProductOfferData($product, $parentProduct)
    {
        $data = parent::getChildProductOfferData($product, $parentProduct);

        if($this->amastyPreOrderHelper->getIsProductPreorder($this->_product)){
            $data['availability'] = self::PRE_ORDER;
        }

        return $data;
    }
}
