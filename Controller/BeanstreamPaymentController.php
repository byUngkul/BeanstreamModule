<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace BeanstreamModule\Controller;

use BeanstreamModule\BeanstreamModule;
use BeanstreamModule\Model\BeanstreamPayment;
use BeanstreamModule\Model\Config\BeanstreamModuleConfigValue;
use Symfony\Component\Form\Form;
use Thelia\Core\HttpKernel\Exception\RedirectException;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\Order;
use Thelia\Model\OrderQuery;
use Thelia\Module\BasePaymentModuleController;


/**
 * Class BeanstreamPaymentController
 * @package BeanstreamModule\Controller
 * @author Manuel Raynaud <manu@thelia.net>
 */
class BeanstreamPaymentController extends BasePaymentModuleController
{

    /**
     * try to pay to Beanstream gateway
     */
    public function payAction()
    {
        $form = $this->createForm('beanstreammodule.payment');
        $errorMessage = null;
        try {
            $orderId = $this->getSession()->get(BeanstreamModule::BEANSTREAM_ORDER_ID, false);

            if (false === $orderId) {
                throw new \RuntimeException(Translator::getInstance()->trans('impossible to retrieve the placed order', [], BeanstreamModule::MESSAGE_DOMAIN));
            }
            $order = OrderQuery::create()->findPk($orderId);

            if (null === $order) {
                throw new \RuntimeException(Translator::getInstance()->trans('impossible to retrieve the placed order', [], BeanstreamModule::MESSAGE_DOMAIN));
            }
            $beanstreamForm = $this->validateForm($form);

            $this->doPay($beanstreamForm, $order);
            $this->redirectToSuccessPage($order->getId());
        } catch (FormValidationException $e) {
            $errorMessage = $e->getMessage();
        } catch (RedirectException $e) {
            throw $e;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }

        $this->redirectToFailurePage($order->getId(), $errorMessage);
    }

    private function doPay(Form $form, Order $order)
    {
        $payment = $this->callPaymentGateway($form, $order);

        if ($payment['status_code'] != '200') {
            $beanstreamPayment = new BeanstreamPayment();

            $beanstreamPayment
                ->setOrderId($order->getId())
                ->setMessageId($payment['response']['code'])
                ->setMessage($payment['response']['message'])
                ->save();
            ;

            throw new \RuntimeException($this->getTranslator()->trans('An error occurred during order process. You can retry.', [], BeanstreamModule::MESSAGE_DOMAIN));
        }

        $response = $payment['response'];

        if ($response['approved'] == 1) {
            $this->confirmPayment($order->getId());
        }
    }

    /**
     * initialize a curl session and send the data in post for processing a payment
     *
     * Return the Beanstream response, the HTTP status code and curl error number and message.
     *
     * @param Form $form
     * @param Order $order
     * @return array
     */
    private function callPaymentGateway(Form $form, Order $order)
    {
        $data = [];
        $ch = curl_init(BeanstreamModule::BASE_URL."/payments");
        $auth = base64_encode(BeanstreamModule::getConfigValue(BeanstreamModuleConfigValue::MERCHANT_ID).":".BeanstreamModule::getConfigValue(BeanstreamModuleConfigValue::PASSCODE));
        $headers = [
            'Content-Type:application/json',
            sprintf('Authorization: Passcode %s', $auth)
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($this->getPaymentData($form, $order)));

        $response = curl_exec($ch);
        $data = [
            'response' => json_decode($response, true),
            'status_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'error_code' => curl_errno($ch),
            'error_message' => curl_error($ch),
        ];
        curl_close($ch);

        return $data;
    }

    /**
     * Populate an array needed for Beanstream transaction
     *
     * @param Form $form
     * @param Order $order
     * @return array
     */
    private function getPaymentData(Form $form, Order $order)
    {

        $invoiceAddress = $order->getOrderAddressRelatedByInvoiceOrderAddressId();
        $deliveryAddress = $order->getOrderAddressRelatedByDeliveryOrderAddressId();
        $customer = $order->getCustomer();
        $data = [
            'order_number' => $order->getRef(),
            'amount' => $order->getTotalAmount(),
            'payment_method' => 'card',
            'customer_ip' => $this->getRequest()->getClientIp(),
            'card' => [
                'complete' => true,
                'name' => $form->get('card_name')->getData(),
                'number' => $form->get('card_number')->getData(),
                'expiry_month' => $form->get('expiration_date_month')->getData(),
                'expiry_year' => $form->get('expiration_date_year')->getData(),
                'cvd' => $form->get('card_cvv')->getData()
            ],
            'billing' => [
                'name' => $invoiceAddress->getFirstname()." ".$invoiceAddress->getLastname(),
                'address_line1' => $invoiceAddress->getAddress1(),
                'address_line2' => $invoiceAddress->getAddress2(),
                'city' => $invoiceAddress->getCity(),
                'province' => '--',
                'postal_code' => $invoiceAddress->getZipcode(),
                'email_address' => $customer->getEmail(),
                'country' => $invoiceAddress->getCountry()->getIsoalpha2()
            ],
            'shipping' => [
                'name' => $deliveryAddress->getFirstname()." ".$deliveryAddress->getLastname(),
                'address_line1' => $deliveryAddress->getAddress1(),
                'address_line2' => $deliveryAddress->getAddress2(),
                'city' => $deliveryAddress->getCity(),
                'province' => '--',
                'postal_code' => $deliveryAddress->getZipcode(),
                'country' => $deliveryAddress->getCountry()->getIsoalpha2(),
                'phone_number' => $deliveryAddress->getPhone(),
                'email_address' => $customer->getEmail()
            ]
        ];

        return $data;
    }

    /**
     * Return a module identifier used to calculate the name of the log file,
     * and in the log messages.
     *
     * @return string the module code
     */
    protected function getModuleCode()
    {
        return 'BeanstreamModule';
    }
}
