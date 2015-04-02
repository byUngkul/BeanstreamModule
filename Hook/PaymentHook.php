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

namespace BeanstreamModule\Hook;

use BeanstreamModule\Model\BeanstreamPaymentQuery;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class PaymentHook
 * @package BeanstreamModule\Hook
 * @author Manuel Raynaud <manu@thelia.net>
 */
class PaymentHook extends BaseHook
{

    public function showPaymentInfo(HookRenderEvent $event)
    {
        $orderId = $event->getArgument('order_id');

        if (null !== $order = BeanstreamPaymentQuery::create()->findOneByOrderId($orderId)) {
            $event->add($this->render(
                'order-info.html',
                [
                    'order_id' => $orderId
                ]
            ));
        }
    }

    public function renderCardForm(HookRenderEvent $event)
    {
        $event->add($this->render('card.html', [
            'year' => date('Y'),
            'yearMax' => date('Y')+5
        ]));
    }
}
