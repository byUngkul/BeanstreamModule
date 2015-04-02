<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace BeanstreamModule\Form;

use BeanstreamModule\Form\Base\BeanstreamModuleConfigForm as BaseBeanstreamModuleConfigForm;

/**
 * Class BeanstreamModuleConfigForm
 * @package BeanstreamModule\Form\Base
 */
class BeanstreamModuleConfigForm extends BaseBeanstreamModuleConfigForm
{
    public function getTranslationKeys()
    {
        return array(
            "merchant_id" => "Merchant id",
            "passcode" => "Passcode",
            "min_amount" => "Minimum amount authorized",
            "max_amount" => "Maximum amount authorized",
        );
    }
}
