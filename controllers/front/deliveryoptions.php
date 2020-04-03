<?php
/**
 * 2017-2019 DM Productions B.V.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@dmp.nl so we can send you a copy immediately.
 *
 * @author     Michael Dekker <info@mijnpresta.nl>
 * @copyright  2010-2019 DM Productions B.V.
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    return;
}

require_once dirname(__FILE__) . '/../../myparcel.php';

/**
 * Class MyParcelDeliveryoptionsModuleFrontController
 *
 * @since 2.0.0
 */
class MyParcelDeliveryoptionsModuleFrontController extends ModuleFrontController
{
    const SUCCESS = 0;
    const ERR_NO_HOUSE_NUMBER = 1;
    const ERR_NO_CART = 2;
    const ERR_NO_CARRIER = 3;
    const ERR_NOT_LOGGED_IN = 4;
    const ERR_CART_NOT_UPDATED = 5;
    const ERR_SETTING_NOT_FOUND = 6;
    const ERR_NO_ADDRESS = 7;
    const ERR_NO_ACCOUNT = 8;
    const ERR_NO_TIMEFRAMES = 9;
    const ERR_CIF = 10;
    const WARN_NO_MOBILE_PHONE = 11;
    const ERR_NO_LOCATIONS_FOUND = 12;
    const ERR_DELIVERY_OPTION_NOT_FOUND = 13;
    const MAX_PREDICTED_DAYS = 30;
    /** @var MyParcel $module */
    public $module;

    /**
     * Initialize content
     *
     * @throws PrestaShopException
     * @throws Adapter_Exception
     * @throws SmartyException
     * @since 2.0.0
     */
    public function initContent()
    {
        if (!Configuration::get(MyParcel::API_KEY)) {
            die(mypa_json_encode(
                array(
                    'data' => array(
                        'message' => 'Module has not been configured',
                    ),
                )
            ));
        }
        if (!Module::isEnabled('myparcel')) {
            die(mypa_json_encode(
                array(
                    'data' => array(
                        'message' => 'Module is not enabled',
                    ),
                )
            ));
        }
        // @codingStandardsIgnoreStart
        $content = @json_decode(file_get_contents('php://input'), true);
        // @codingStandardsIgnoreEnd
        if (isset($content['ajax']) && $content['ajax']) {
            $this->displayAjax();
        }

        exit;
    }

    /**
     * @throws Adapter_Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function displayAjax()
    {
        header('Content-Type: application/json;charset=utf-8');

        // @codingStandardsIgnoreStart
        $content = @json_decode(file_get_contents('php://input'), true);
        // @codingStandardsIgnoreEnd
        if (!empty($content['updateOption'])) {
            $context = Context::getContext();
            $cart = $context->cart;
            if (!Validate::isLoadedObject($cart)) {
                die(
                mypa_json_encode(
                    array(
                        'success' => false,
                        'status' => static::ERR_NO_CART,
                        'message' => $this->module->l('Cart not found', 'deliveryoptions'),
                    )
                )
                );
            }
            if (empty($context->customer)) {
                die(
                mypa_json_encode(
                    array(
                        'success' => false,
                        'status' => static::ERR_NOT_LOGGED_IN,
                        'message' => $this->module->l('Customer not logged in', 'deliveryoptions'),
                    )
                )
                );
            }

            $deliveryOption = $content['deliveryOption'];
            $deliveryOptionObject = MyParcelDeliveryOption::getByCartId($cart);

            if (!empty($deliveryOption)) {
                if (!Validate::isLoadedObject($deliveryOptionObject)) {
                    $option = new MyParcelDeliveryOption();
                    $option->id_cart = $cart->id;
                    $option->myparcel_delivery_option = mypa_json_encode($deliveryOption);
                    $option->add();
                } else {
                    Db::getInstance()->execute(
                        'INSERT INTO `' . _DB_PREFIX_ . bqSQL(MyParcelDeliveryOption::$definition['table'])
                        . '` (`id_myparcel_delivery_option`, `myparcel_delivery_option`)
                     VALUES (' . (int)$deliveryOptionObject->id . ', \'' . pSQL(mypa_json_encode($deliveryOption)) . '\')
                         ON DUPLICATE KEY UPDATE `myparcel_delivery_option` = \'' . pSQL(mypa_json_encode($deliveryOption)) . '\''
                    );
                }

                $carriers = $this->context->cart->simulateCarriersOutput();
                $return = array_merge(
                    array(
                        'HOOK_TOP_PAYMENT' => Hook::exec('displayPaymentTop'),
                        'HOOK_PAYMENT' => (version_compare(_PS_VERSION_, '1.7.0.0', '<')
                            ? $this->getPaymentMethods()
                            : ''
                        ),
                        'carrier_data' => (version_compare(_PS_VERSION_, '1.7.0.0', '<')
                            ? $this->getCarrierList()
                            : $this->getDeliveryOptionList()
                        ),
                        'HOOK_BEFORECARRIER' => Hook::exec('displayBeforeCarrier', array('carriers' => $carriers)),
                    ),
                    (version_compare(_PS_VERSION_, '1.7.0.0', '<')
                        ? $this->getFormattedSummaryDetail()
                        : array()
                    )
                );
                Cart::addExtraCarriers($return);
                die(mypa_json_encode($return));
            }

            die(
            mypa_json_encode(
                array(
                    'success' => false,
                    'status' => static::ERR_DELIVERY_OPTION_NOT_FOUND,
                )
            )
            );
        }
    }

    /**
     * @return array|string
     * @throws Adapter_Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function getPaymentMethods()
    {
        if ($this->context->cart->orderExists()) {
            return '<p class="warning">'
                . Tools::displayError('Error: This order has already been validated.') . '</p>';
        }
        if (!$this->context->cart->id_customer
            || !Customer::customerIdExistsStatic($this->context->cart->id_customer)
            || Customer::isBanned($this->context->cart->id_customer)
        ) {
            return '<p class="warning">' . Tools::displayError('Error: No customer.') . '</p>';
        }
        $addressDelivery = new Address($this->context->cart->id_address_delivery);
        $addressInvoice = ($this->context->cart->id_address_delivery == $this->context->cart->id_address_invoice
            ? $addressDelivery
            : new Address($this->context->cart->id_address_invoice)
        );
        if (!$this->context->cart->id_address_delivery
            || !$this->context->cart->id_address_invoice
            || !Validate::isLoadedObject($addressDelivery)
            || !Validate::isLoadedObject($addressInvoice)
            || $addressInvoice->deleted
            || $addressDelivery->deleted
        ) {
            return '<p class="warning">' . Tools::displayError('Error: Please select an address.') . '</p>';
        }
        if (count($this->context->cart->getDeliveryOptionList()) == 0 && !$this->context->cart->isVirtualCart()) {
            if ($this->context->cart->isMultiAddressDelivery()) {
                return '<p class="warning">'
                    . Tools::displayError(
                        'Error: None of your chosen carriers deliver ' .
                        'to some of the addresses you have selected.'
                    ) . '</p>';
            } else {
                return '<p class="warning">'
                    . Tools::displayError(
                        'Error: None of your chosen carriers deliver ' .
                        'to the address you have selected.'
                    ) . '</p>';
            }
        }
        if (!$this->context->cart->getDeliveryOption(null, false)
            && !$this->context->cart->isVirtualCart()
        ) {
            return '<p class="warning">' . Tools::displayError('Error: Please choose a carrier.') . '</p>';
        }
        if (!$this->context->cart->id_currency) {
            return '<p class="warning">' . Tools::displayError('Error: No currency has been selected.') . '</p>';
        }
        if (!$this->context->cookie->checkedTOS && Configuration::get('PS_CONDITIONS')) {
            return '<p class="warning">' . Tools::displayError('Please accept the Terms of Service.') . '</p>';
        }

        /* If some products have disappear */
        if (is_array($product = $this->context->cart->checkQuantities(true))) {
            return '<p class="warning">' . sprintf(
                    Tools::displayError(
                        'An item (%s) in your cart is no longer ' .
                        'available in this quantity. You cannot proceed with your order until the quantity is adjusted.'
                    ),
                    $product['name']
                ) . '</p>';
        }

        if ((int)$idProduct = $this->context->cart->checkProductsAccess()) {
            return '<p class="warning">' . sprintf(
                    Tools::displayError(
                        'An item in your cart is no longer ' .
                        'available (%s). You cannot proceed with your order.'
                    ),
                    Product::getProductName((int)$idProduct)
                ) . '</p>';
        }

        /* Check minimal amount */
        $currency = Currency::getCurrency((int)$this->context->cart->id_currency);

        $minimalPurchase = Tools::convertPrice((float)Configuration::get('PS_PURCHASE_MINIMUM'), $currency);
        if ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimalPurchase) {
            return '<p class="warning">' . sprintf(
                    Tools::displayError(
                        'A minimum purchase total of %1s (tax excl.) is required to validate ' .
                        'your order, current purchase total is %2s (tax excl.).'
                    ),
                    Tools::displayPrice($minimalPurchase, $currency),
                    Tools::displayPrice($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS), $currency)
                ) . '</p>';
        }

        /* Bypass payment step if total is 0 */
        if ($this->context->cart->getOrderTotal() <= 0) {
            return '<p class="center"><button class="button btn btn-default button-medium" name="confirmOrder" ' .
                'id="confirmOrder" onclick="confirmFreeOrder();" type="submit"> <span>'
                . Tools::displayError('I confirm my order.') . '</span></button></p>';
        }

        $return = Hook::exec('displayPayment');
        if (!$return) {
            return '<p class="warning">' .
                Tools::displayError('No payment method is available for use at this time. ') . '</p>';
        }

        return $return;
    }

    /**
     * @return array|bool
     * @throws Adapter_Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    protected function getCarrierList()
    {
        $addressDelivery = new Address($this->context->cart->id_address_delivery);

        $cms = new CMS(Configuration::get('PS_CONDITIONS_CMS_ID'), $this->context->language->id);
        $linkConditions = $this->context->link->getCMSLink(
            $cms,
            $cms->link_rewrite,
            Configuration::get('PS_SSL_ENABLED')
        );
        if (!strpos($linkConditions, '?')) {
            $linkConditions .= '?content_only=1';
        } else {
            $linkConditions .= '&content_only=1';
        }

        $carriers = $this->context->cart->simulateCarriersOutput();
        $deliveryOption = $this->context->cart->getDeliveryOption(
            null,
            false,
            false
        );

        $wrappingFees = $this->context->cart->getGiftWrappingPrice(false);
        $wrappingFeesTaxInc = $this->context->cart->getGiftWrappingPrice();
        $oldMessage = Message::getMessageByCartId((int)$this->context->cart->id);

        $freeShipping = false;
        foreach ($this->context->cart->getCartRules() as $rule) {
            if ($rule['free_shipping'] && !$rule['carrier_restriction']) {
                $freeShipping = true;
                break;
            }
        }

        $this->context->smarty->assign('isVirtualCart', $this->context->cart->isVirtualCart());

        $vars = array(
            'advanced_payment_api' => (bool)Configuration::get('PS_ADVANCED_PAYMENT_API'),
            'free_shipping' => $freeShipping,
            'checkedTOS' => (int)$this->context->cookie->checkedTOS,
            'recyclablePackAllowed' => (int)Configuration::get('PS_RECYCLABLE_PACK'),
            'giftAllowed' => (int)Configuration::get('PS_GIFT_WRAPPING'),
            'cms_id' => (int)Configuration::get('PS_CONDITIONS_CMS_ID'),
            'conditions' => (int)Configuration::get('PS_CONDITIONS'),
            'link_conditions' => $linkConditions,
            'recyclable' => (int)$this->context->cart->recyclable,
            'gift_wrapping_price' => (float)$wrappingFees,
            'total_wrapping_cost' => Tools::convertPrice($wrappingFeesTaxInc, $this->context->currency),
            'total_wrapping_tax_exc_cost' => Tools::convertPrice($wrappingFees, $this->context->currency),
            'delivery_option_list' => $this->context->cart->getDeliveryOptionList(),
            'carriers' => $carriers,
            'checked' => $this->context->cart->simulateCarrierSelectedOutput(),
            'delivery_option' => $deliveryOption,
            'address_collection' => $this->context->cart->getAddressCollection(),
            'opc' => true,
            'oldMessage' => isset($oldMessage['message']) ? $oldMessage['message'] : '',
            'HOOK_BEFORECARRIER' => Hook::exec(
                'displayBeforeCarrier',
                array(
                    'carriers' => $carriers,
                    'delivery_option_list' => $this->context->cart->getDeliveryOptionList(),
                    'delivery_option' => $deliveryOption,
                )
            ),
        );

        Cart::addExtraCarriers($vars);

        $this->context->smarty->assign($vars);

        if (!Address::isCountryActiveById((int)$this->context->cart->id_address_delivery)
            && $this->context->cart->id_address_delivery != 0
        ) {
            $this->errors[] = Tools::displayError('This address is not in a valid area.');
        } elseif ((!Validate::isLoadedObject($addressDelivery) || $addressDelivery->deleted)
            && $this->context->cart->id_address_delivery != 0
        ) {
            $this->errors[] = Tools::displayError('This address is invalid.');
        } else {
            $result = array(
                'HOOK_BEFORECARRIER' => Hook::exec(
                    'displayBeforeCarrier',
                    array(
                        'carriers' => $carriers,
                        'delivery_option_list' => $this->context->cart->getDeliveryOptionList(),
                        'delivery_option' =>
                            $this->context->cart->getDeliveryOption(null, true),
                    )
                ),
                'carrier_block' => $this->context->smarty->fetch(_PS_THEME_DIR_ . 'order-carrier.tpl'),
            );

            Cart::addExtraCarriers($result);

            return $result;
        }
        if (count($this->errors)) {
            return array(
                'hasError' => true,
                'errors' => $this->errors,
                'carrier_block' => $this->context->smarty->fetch(_PS_THEME_DIR_ . 'order-carrier.tpl'),
            );
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getDeliveryOptionList()
    {
        $finder = new DeliveryOptionsFinder(
            $this->context,
            $this->getTranslator(),
            $this->objectPresenter,
            new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter()
        );

        return $finder->getDeliveryOptions();
    }

    /**
     * @return array
     * @throws Adapter_Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function getFormattedSummaryDetail()
    {
        $result = array(
            'summary' => $this->context->cart->getSummaryDetails(),
            'customizedDatas' => Product::getAllCustomizedDatas($this->context->cart->id, null, true),
        );

        foreach ($result['summary']['products'] as &$product) {
            $product['quantity_without_customization'] = $product['quantity'];
            if ($result['customizedDatas']) {
                $ip = (int)$product['id_product'];
                $ipa = (int)$product['id_product_attribute'];
                if (isset($result['customizedDatas'][$ip][$ipa])) {
                    foreach ($result['customizedDatas'][$ip][$ipa] as $addresses) {
                        foreach ($addresses as $customization) {
                            $product['quantity_without_customization'] -= (int)$customization['quantity'];
                        }
                    }
                }
            }
        }

        if ($result['customizedDatas']) {
            Product::addCustomizationPrice($result['summary']['products'], $result['customizedDatas']);
        }

        return $result;
    }

    /**
     * @throws Adapter_Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function assignWrappingAndTOS()
    {
        // Wrapping fees
        $wrappingFees = $this->context->cart->getGiftWrappingPrice(false);
        $wrappingFeesTaxInc = $this->context->cart->getGiftWrappingPrice();

        // TOS
        $cms = new CMS(Configuration::get('PS_CONDITIONS_CMS_ID'), $this->context->language->id);
        $this->link_conditions = $this->context->link->getCMSLink(
            $cms,
            $cms->link_rewrite,
            (bool)Configuration::get('PS_SSL_ENABLED')
        );

        $freeShipping = false;
        foreach ($this->context->cart->getCartRules() as $rule) {
            if ($rule['free_shipping'] && !$rule['carrier_restriction']) {
                $freeShipping = true;
                break;
            }
        }
        $this->context->smarty->assign(
            array(
                'free_shipping' => $freeShipping,
                'checkedTOS' => (int)$this->context->cookie->checkedTOS,
                'recyclablePackAllowed' => (int)Configuration::get('PS_RECYCLABLE_PACK'),
                'giftAllowed' => (int)Configuration::get('PS_GIFT_WRAPPING'),
                'cms_id' => (int)Configuration::get('PS_CONDITIONS_CMS_ID'),
                'conditions' => (int)Configuration::get('PS_CONDITIONS'),
                'link_conditions' => array(),
                'recyclable' => (int)$this->context->cart->recyclable,
                'delivery_option_list' => $this->context->cart->getDeliveryOptionList(),
                'carriers' => $this->context->cart->simulateCarriersOutput(),
                'checked' => $this->context->cart->simulateCarrierSelectedOutput(),
                'address_collection' => $this->context->cart->getAddressCollection(),
                'delivery_option' =>
                    $this->context->cart->getDeliveryOption(null, false),
                'gift_wrapping_price' => (float)$wrappingFees,
                'total_wrapping_cost' => Tools::convertPrice($wrappingFeesTaxInc, $this->context->currency),
                'override_tos_display' => Hook::exec('overrideTOSDisplay'),
                'total_wrapping_tax_exc_cost' => Tools::convertPrice($wrappingFees, $this->context->currency),
            )
        );
    }
}
