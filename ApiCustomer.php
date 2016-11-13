<?php
// 2016-11-13
namespace Dfe\Omise;
class ApiCustomer extends \OmiseCustomer {
	/**
	 * 2016-11-13
	 * @return array(string => string)
	 */
	/*public function cards() {return array_map(function(\OmiseCard $card) {return [
		'id' => $card['id'], 'label' => Response::cardS(ApiObject::values($card))
	];}, $this->sources->{'data'});} */

	/**
	 * 2016-11-13
	 * «When requesting the ID of a customer that has been deleted,
	 * a subset of the customer’s information will be returned,
	 * including a deleted property, which will be true.»
	 * https://stripe.com/docs/api/php#retrieve_customer
	 *
	 * @used-by \Dfe\Omise\Charge::sCustomer()
	 * @used-by \Dfe\Omise\ConfigProvider::savedCards()
	 *
	 * @return bool
	 */
	public function isDeleted() {return $this->isDestroyed();}
}