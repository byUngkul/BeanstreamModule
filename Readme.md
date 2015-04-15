# Beanstream

This module integrate the payment gateway [Beanstream](http://www.beanstream.com/home/). For now only the card purchase is developed.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ```BeanstreamModule```.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/beanstream-module:~1.0
```

## Usage

Once activated, click on the configure button. Fill the form with your credentials. Add the minimum and maximum amount authorized by Beanstream. If you don't know
this amount, ask to Beanstream.

## Hooks

### order-payment-gateway.body

Used for displaying the card information form. Here you customer will enter his credit card credentials like the card number, expiration date, etc.

### order-edit.cart-bottom

In the back-office for displaying information if the payment failed. You will see the code and message error. See the documentation for a better explanation of this error : 
[http://support.beanstream.com/docs/response-message-codes-descriptions.htm](http://support.beanstream.com/docs/response-message-codes-descriptions.htm)

## Loop

### beanstream-payment

#### Input arguments

|Argument |Description |
|---      |--- |
|**id**   | A single or a list of ids. |
|**order_id** | A single order id. |

#### Output arguments

|Variable       |Description |
|---            |--- |
|$ID            | The beanstream payment id |
|$ORDER_ID      | the order id related to the payment |
|$MESSAGE_ID    | the message id returned by Beanstream API |
|$MESSAGE       | the message returned by Beanstream API |

#### Example

    {loop name="beanstream" type="beanstream-payment" order_id=$order_id}
    <div class="table-responsive">
        <table class="table table-striped table-condensed table-left-aligned">
            <caption class="clearfix">
                {intl l='Beanstream information' d="beanstreammodule"}
            </caption>
            <tbody>
            <tr>
                <th>{intl l='Error code' d="beanstreammodule"}</th>
                <td>{$MESSAGE_ID}</td>
            </tr>
            <tr>
                <th>{intl l='Error message' d="beanstreammodule"}</th>
                <td>{$MESSAGE}</td>
            </tr>
            </tbody>
        </table>
    </div>
    {/loop}

