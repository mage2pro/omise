<?php
// 2016-11-13
namespace Dfe\Omise\Api;
use Dfe\Omise\Response;
use OmiseCustomer as Sb;
class Customer extends Sb {
	/**
	 * 2016-11-14
	 * Ключ $o['cards']['data'] присутствует в объекте даже при отсутствии карт.
	 * @param Sb $o
	 * @return array(string => string)
	 */
	public static function _cards(Sb $o) {return array_map(function(array $card) {return [
		'id' => $card['id'], 'label' => Response::cardS($card)
	];}, $o['cards']['data']);}

	/**
	 * 2016-11-14
	 * https://github.com/omise/omise-php/issues/43
	 * @param Sb $o
	 * @return bool
	 */
	public static function _isDestroyed(Sb $o) {return !!$o->isDestroyed();}
}