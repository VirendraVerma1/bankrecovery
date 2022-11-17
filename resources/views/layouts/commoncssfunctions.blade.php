<style>
/* this css is used in checkout address box -------------------------------------- */
.highlight {
  background-color: #D6EAF8 ;
}
.highlight_billing {
  background-color: #FCF3CF ;
}



                   /* notification sticky */

.poppop-sticky
{
    position: fixed;
    z-index: 159;
    right: 0;
    top: 120px;
    left: 0;

    display: -ms-flexbox;
    display: flex;
        flex-direction: column;

    pointer-events: none; 

    -ms-flex-direction: column;
}


body.fancybox-active .payment-notification-wrap,
body.fancybox-active .back-to-top,
body.fancybox-active .js-stickyAddToCart
{
    display: none;
}


.bnslider-product-name,
.payment-notification-name,
.prd-title,
h2.prd-title,
.h2-style.prd-title,
.prd-block_title,
h2.minicart-prd-name,
h2.sticky-addcart_title,
h1.sticky-addcart_title,
.popup-addedtocart_title,
.dropdn-content ul.search-results li .title
{
    font-family: 'Georgia', sans-serif;
}


.payment-notification-wrap
{
    position: absolute;
    bottom: 0;
    left: 20px;

    margin-bottom: 20px;

    transition: .2s;
    pointer-events: none;
}

.payment-notification
{
    position: relative;
    z-index: 1;

    padding: 4px;

    transform: translateY(120%);
    pointer-events: all; 

    opacity: 0;
    border-radius: 50%;
}
.payment-notification:after
{
    position: absolute;
    top: 0;
    left: 0;

    width: 100%;
    height: 100%;

    content: '';

    opacity: .35; 
    border-radius: 50%;
    background: #647482;
}

.payment-notification-inside
{
    position: relative;
    z-index: 1;

    width: 190px;
    height: 190px;

    border-radius: 50%; 
    background-color: #fff;
}
@media (max-width: 575px)
{
    .payment-notification-inside
    {
        width: 170px;
        height: 170px;
    }
}

.payment-notification-container
{
    padding: 12px;

    text-align: center;
}
@media (max-width: 575px)
{
    .payment-notification-container
    {
        padding: 8px;
    }
}

.payment-notification-image img
{
    overflow: hidden; 

    max-width: 90px;
    max-height: 80px;
}
@media (max-width: 575px)
{
    .payment-notification-image img
    {
        max-width: 75px;
        max-height: 65px;
    }
}

.payment-notification-content
{
    display: -ms-flexbox;
    display: flex;
        flex-direction: column;

    margin: 0;
    padding: 15px 12px; 

    -ms-flex-direction: column;
}
@media (max-width: 575px)
{
    .payment-notification-content
    {
        padding: 12px 8px;
    }
}

.payment-notification-close
{
    font-size: 18px;

    position: absolute;
    top: 5px;
    right: 8px;

    display: -ms-flexbox;
    display: flex;

    width: 34px;
    height: 34px;

    cursor: pointer;
    transition: .2s; 

    color: #000;
    border-radius: 50%;
    background-color: #fff;
    box-shadow: 0 3px 3px rgba(0, 0, 0, .07);

    -ms-flex-align: center;
    align-items: center;
    -ms-flex-pack: center;
    justify-content: center;
}
.payment-notification-close:hover
{
    text-decoration: none;

    opacity: .75; 
    color: #fff;
    background-color: #49d8d9;
}

.payment-notification-qw
{
    font-size: 18px;

    position: absolute;
    right: 8px;
    bottom: 5px;

    display: -ms-flexbox;
    display: flex;

    width: 34px;
    height: 34px;

    cursor: pointer;
    transition: .2s; 

    color: #000;
    border-radius: 50%;
    background-color: #fff;
    box-shadow: 0 3px 3px rgba(0, 0, 0, .07);

    -ms-flex-align: center;
    align-items: center;
    -ms-flex-pack: center;
    justify-content: center;
}
.payment-notification-qw:hover
{
    text-decoration: none; 

    opacity: .75;
    color: #fff;
    background-color: #49d8d9;
}

.payment-notification-content > * + *
{
    margin-top: 4px;
}

.payment-notification-text
{
    font-size: 10px;
    line-height: 1em;

    color: #000;
}

.payment-notification-name
{
    font-size: 13px;
    font-weight: 600;
    line-height: 1em;

    display: inline-block;
    overflow: hidden;

    max-width: 100%;

    white-space: nowrap;
    text-decoration: none; 
    text-overflow: ellipsis;

    color: #000;
}
.payment-notification-name:hover
{
    text-decoration: none; 

    color: #49d8d9;
}

.payment-notification-bottom
{
    display: -ms-flexbox;
    display: flex;

    margin: auto -2px 0;
    padding-top: 15px;
}
.payment-notification-bottom > *
{
    padding-right: 2px; 
    padding-left: 2px;
}

.payment-notification-when
{
    font-size: 11px;
    line-height: 1em;

    color: #49d8d9;
}

.payment-notification-from
{
    font-size: 11px;
    line-height: 1em;

    margin-top: 0;

    color: #000;
}

.payment-notification.payment-notification--squared .payment-notification-inside
{
    width: 280px;
    height: auto;

    border-radius: 5px;
    box-shadow: 0 5px 8px rgba(0, 0, 0, .2);
}

.payment-notification.payment-notification--squared .payment-notification-container
{
    display: -ms-flexbox;
    display: flex;

    padding: 5px 30px 5px 5px;

    text-align: left;

    -ms-flex-align: center;
    align-items: center;
}

.payment-notification.payment-notification--squared .payment-notification-image img
{
    width: auto;
    height: 100%;

    object-fit: contain;
}

.payment-notification.payment-notification--squared .payment-notification-content
{
    margin: 0;
    padding: 0 10px;
}

.payment-notification.payment-notification--squared .payment-notification-content-wrapper
{
    height: auto;
}

.payment-notification.payment-notification--squared .payment-notification-name
{
    line-height: 1.2em; 

    display: block;

    white-space: normal;
}

.payment-notification.payment-notification--squared .payment-notification-close
{
    font-size: 14px;

    top: 5px;
    right: 5px;

    width: 24px;
    height: 24px;

    box-shadow: none;
}

.payment-notification.payment-notification--squared .payment-notification-qw
{
    font-size: 16px;

    right: 5px;
    bottom: 5px;

    width: 24px;
    height: 24px;

    box-shadow: none;
}

.payment-notification.payment-notification--squared .payment-notification-from
{
    font-size: 11px;
    line-height: 1em;
}

.payment-notification.payment-notification--squared:after
{
    display: none;
}

</style>