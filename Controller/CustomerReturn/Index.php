<?php
// 2016-12-25
// We get here on a customer's return from the 3D Secure verification.
// Omise does not pass any parameters with this request,
// but it looks like we do not ever need it,
// because we can find out the customer's last order in his session.
namespace Dfe\Omise\Controller\CustomerReturn;
class Index extends \Df\Payment\R\CustomerReturn {}