<?php
namespace Dfe\Omise\Facade;
use Dfe\Omise\SDK\O as AO;
use OmiseApiResource as _O;
// 2017-02-11
final class O extends \Df\StripeClone\Facade\O {
	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Facade\O::toArray()
	 * @used-by \Df\StripeClone\Method::transInfo()
	 * @param _O $o
	 * @return array(string => mixed)
	 */
	function toArray($o) {return AO::_values($o);}
}