<?php
// 2016-11-13
namespace Dfe\Omise\Api;
use Dfe\Omise\Card;
use OmiseCustomer as Sb;
class Customer extends Sb {
	/**
	 * 2016-11-15
	 * @param Sb $o
	 * @return string
	 */
	public static function _cardIdLast(Sb $o) {return df_last($o['cards']['data'])['id'];}

	/**
	 * 2016-11-14
	 * Ключ $o['cards']['data'] присутствует в объекте даже при отсутствии карт.
	 * @param Sb $o
	 * @return array(string => string)
	 */
	public static function _cards(Sb $o) {return array_map(function(array $card) {return [
		'id' => $card['id'], 'label' => (string)(new Card($card))
	];}, $o['cards']['data']);}

	/**
	 * 2016-11-14
	 * https://github.com/omise/omise-php/issues/43
	 * @param Sb $o
	 * @return bool
	 */
	public static function _isDestroyed(Sb $o) {return !!$o->isDestroyed();}
}