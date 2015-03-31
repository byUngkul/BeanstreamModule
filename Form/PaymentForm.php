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

namespace BeanstreamModule\Form;

use BeanstreamModule\BeanstreamModule;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Form\OrderPayment;


/**
 * Class PaymentForm
 * @package BeanstreamModule\Form
 * @author Manuel Raynaud <manu@thelia.net>
 */
class PaymentForm extends BaseForm
{

    public function buildForm()
    {
        $translator = Translator::getInstance();
        $this->formBuilder
            ->add(
                'card_name',
                'text',
                [
                    "constraints" => [
                        new NotBlank()
                    ],
                    "label" => $translator->trans("Name on card", [], BeanstreamModule::MESSAGE_DOMAIN),
                    "label_attr" => ["for" => "card_name"]
                ]
            )
            ->add(
                'card_number',
                'text',
                [
                    "constraints" => [
                        new NotBlank()
                    ],
                    "label" => $translator->trans("Credit Card Number", [], BeanstreamModule::MESSAGE_DOMAIN),
                    "label_attr" => ["for" => "card_number"]
                ]
            )
            ->add(
                'card_cvv',
                'text',
                [
                    "constraints" => [
                        new NotBlank()
                    ],
                    "label" => $translator->trans("CVV", [], BeanstreamModule::MESSAGE_DOMAIN),
                    "label_attr" => ["for" => "card_cvv"]
                ]
            )
            ->add(
                'expiration_date_month',
                'choice',
                [
                    'choices' => $this->getMonths(),
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            )
            ->add(
                'expiration_date_year',
                'choice',
                [
                    'choices' => $this->getYears(),
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            )
        ;
    }

    protected function getMonths()
    {
        $ret = [];
        for($i=1; $i<=12; $i++) {
            $key = str_pad($i, 2, '0', STR_PAD_LEFT);
            $ret[$key] = $i;
        }

        return $ret;
    }

    protected function getYears()
    {
        $ret = [];
        for($i=date('Y'), $j=1; $j<=5; $i++, $j++) {
            $key = substr($i, 2, 2);
            $ret[$key] = $i;
        }

        return $ret;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return "beanstream_payment";
    }
}
