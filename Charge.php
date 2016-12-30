<?php
// 2016-11-13
namespace Dfe\Omise;
use Df\Core\Exception as DFE;
use Dfe\Omise\Api\Customer as AC;
use Dfe\Omise\Settings as S;
use Magento\Sales\Model\Order\Payment as OP;
class Charge extends \Df\StripeClone\Charge {
	/**
	 * 2016-11-13
	 * https://www.omise.co/charges-api#charges-create
	 * @override
	 * @see \Df\StripeClone\Charge::_request()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @return array(string => mixed)
	 */
	final protected function _request() {/** @var Settings $s */ $s = S::s(); return [
		'amount' => $this->amountF()
		// 2016-11-16
		// «(optional) Whether or not you want the charge to be captured right away,
		// when not specified it is set to true.»
		// 2016-11-17
		// «If you have created a charge and passed capture=false,
		// you'll have an authorized only charge that you can capture anytime within 7 days.
		// After that, the charge will expire.»
		// https://www.omise.co/charges-api#charges-capture
		,'capture' => $this->needCapture()
		// 2016-03-07
		// «(required or optional) A valid unused TOKEN_ID or CARD_ID
		// In the case of the CARD_ID the customer parameter must be present and be the owner of the card.
		// For the TOKEN_ID, the customer must not be passed.»
		,'card' => $this->cardId()
		,'customer' => $this->customerId()
		,'currency' => $this->currencyC()
		// 2016-11-16
		// «(optional) A custom description for the charge.
		// This value can be searched for in your dashboard.»
		,'description' => $this->text($s->description())
	] + (!$s->_3DS() ? [] : [
		/**
		 * 2016-12-24
		 * «(optional) The url where we will return the customer
		 * after the charge has been authorized with 3-D Secure.»
		 * https://www.omise.co/charges-api#charges-create
		 * https://www.omise.co/how-to-implement-3-D-Secure
		 * 2016-12-25
		 * Omise does not pass any parameters with this request.
		 * but it looks like we do not ever need it,
		 * because we can find out the customer's last order in his session.
		 *
		 * Note that in the Checkout.com module we use the same URL
		 * for both the 3D Secure return and for the Webhooks.
		 * https://github.com/checkout/checkout-magento2-plugin/blob/eee8ad66/Controller/Index/Index.php?ts=4#L19-L23
		 * We do it because Checkout.com does not allow to set the URL for 3D Secure return dynamically,
		 * (it needed to be set manually by the Checkout.com support),
		 * so we want to make these URLs simpler for humans.
		 *
		 * In the Omise case we set the URL for 3D Secure return dynamically (here),
		 * so we prefer to use separate URLs for the Webhooks and 3D Secure return.
		 * @see \Dfe\Omise\Controller\CustomerReturn\Index
		 */
		'return_uri' => $this->customerReturn()
	]);}

	/**
	 * 2016-11-15
	 * Даже если покупатель в момент покупки ещё не имеет учётной записи в магазине,
	 * то всё равно разумно зарегистрировать его в Omise и сохранить данные его карты,
	 * потому что Magento уже после оформления заказа предложит такому покупателю зарегистрироваться,
	 * и покупатель вполне может согласиться: https://mage2.pro/t/1967
	 *
	 * Если покупатель согласится создать учётную запись в магазине,
	 * то мы попадаем в @see \Df\Customer\Observer\CopyFieldset\OrderAddressToCustomer::execute()
	 * и там из сессии передаём данные в свежесозданную учётную запись.
	 *
	 * @return \OmiseCustomer
	 * @throws DFE
	 */
	private function apiCustomer() {return dfc($this, function() {
		/** @var \OmiseCustomer|null $result */
		$result = null;
		if ($this->savedCustomerId()) {
			$result = \OmiseCustomer::retrieve($this->savedCustomerId());
			if ($result->isDestroyed()) {
				ApiCustomerId::save(null);
				$result = null;
				$this->rejectPreviousCard();
			}
			/**
			 * 2016-11-15
			 * Покупатель уже зарегистрирован в Omise, но он в этот раз хочет платить новой картой.
			 * Сохраняем её.
			 */
			if (!$this->usePreviousCard()) {
				$result->update(['card' => $this->token()]);
			}
		}
		if (!$result) {
			$this->rejectPreviousCard();
			$result = \OmiseCustomer::create($this->apiCustomerParams());
			ApiCustomerId::save($result['id']);
		}
		return $result;
	});}

	/**
	 * 2016-11-15
	 * @return array(string => mixed)
	 */
	private function apiCustomerParams() {return [
		// 2016-11-15
		// «(optional) A card token in case you want to add a card to the customer.»
		// https://www.omise.co/customers-api#customers-create
		'card' => $this->token()
		,'description' => $this->customerName()
		,'email' => $this->customerEmail()
	];}

	/**
	 * 2016-11-15
	 * @return string
	 */
	private function cardId() {return
		$this->usePreviousCard() ? $this->token() : AC::_cardIdLast($this->apiCustomer())
	;}

	/**
	 * 2016-11-15
	 * @return string
	 */
	private function customerId() {
		/** @var string $result */
		$result = $this->savedCustomerId();
		if (!$result) {
			df_assert(!$this->usePreviousCard());
			$result = $this->apiCustomer()['id'];
		}
		return $result;
	}

	/**
	 * 2016-11-15
	 * @return string
	 */
	private function savedCustomerId() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = ApiCustomerId::get($this->c());
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-11-15
	 * Если покупатель был удалён в Omise,
	 * то использовать его ранее сохранённую карту мы не можем.
	 * В принципе, в эту исключительную ситуацию мы практически не должны попадать,
	 * потому что для отображения покупателю списка его сохранённых карт
	 * мы запрашиваем этот список у Omise в реальном времени:
	 * @see \Dfe\Omise\ConfigProvider::savedCards()
	 * Получается, чтобы сюда попасть, мы должны были удалить покупателя
	 * уже после отображения страницы оформления заказа покупателю,
	 * но до завершения оформления заказа покупателем.
	 * @throws DFE
	 */
	private function rejectPreviousCard() {
		if ($this->usePreviousCard()) {
			df_error(
				'Sorry, your previous card data are unavailable. '
				. 'Please reenter the data again, or use another card.'
			);
		}
	}

	/**
	 * 2016-11-15
	 * Отныне параметр «token» может содержать не только токен новой карты
	 * (например: «tokn_<...>»),
	 * но и идентификатор ранее использовавшейся карты
	 * (например: «card_<...>»).
	 * У Omise префикс токена — «tokn_», а у Stripe — «tkn_».
	 * @return bool
	 */
	private function usePreviousCard() {return dfc($this, function() {return
		df_starts_with($this->token(), 'card_')
	;});}
}