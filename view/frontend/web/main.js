// 2016-11-10
define([
	'df'
	,'Df_Checkout/js/data'
	,'Df_Payment/card'
	,'Dfe_Omise/API'
], function(df, dfc, parent,
	// 2016-11-12
	// У Omise, как и Stripe, API-объект является глобальной переменной,
	// но мы, благодаря shim, получаем его локально.
	// При этом он всё равно доступен в виде window.Omise
	Omise
) {'use strict'; return parent.extend({
	/**
	 * 2016-11-10
	 * https://www.omise.co/which-credit-cards-does-omise-accept
	 * @returns {String[]}
	 */
	getCardTypes: function() {return ['VI', 'MC', 'JCB'];},
	/**
	 * 2016-11-10
	 * @override
	 * @returns {Object}
	*/
	initialize: function() {
		this._super();
		Omise.setPublicKey(this.config('publicKey'));
		return this;
	},
	/**
	 * 2016-11-10
	 * @override
	 * @see https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L127-L159
	 * @used-by https://github.com/magento/magento2/blob/2.1.0/lib/web/knockoutjs/knockout.js#L3863
	 * @param {this} _this
	*/
	placeOrder: function(_this) {
		if (this.validate()) {
			if (!this.isNewCardChosen()) {
				/**
				 * 2016-08-23
				 * Идентификаторы карт начинаются с приставки «card_»
				 * (например: «card_18lGFRFzKb8aMux1Bmcjsa5L»),
				 * а идентификаторы токенов — с приставки «tok_»
				 * (например: «tok_18lWSWFzKb8aMux1viSqpL5X»),
				 * тем самым по приставке мы можем отличить карты от токенов,
				 * и поэтому для карт и токенов мы можем использовать одну и ту же переменную.
				 */
				this.token = this.currentCard();
				this.placeOrderInternal();
			}
			else {
				// 2016-03-02
				// https://stripe.com/docs/custom-form#step-2-create-a-single-use-token
				/**
				 * 2016-03-07
				 * https://support.stripe.com/questions/which-cards-and-payment-types-can-i-accept-with-stripe
				 * Which cards and payment types can I accept with Stripe?
				 * With Stripe, you can charge almost any kind of credit or debit card:
				 * U.S. businesses can accept:
				 * 		Visa, MasterCard, American Express, JCB, Discover, and Diners Club.
				 * Australian, Canadian, European, and Japanese businesses can accept:
				 * 		Visa, MasterCard, and American Express.
				 */
				Stripe.card.createToken(
					{
						cvc: this.dfCardVerification()
						,exp_month: this.dfCardExpirationMonth()
						,exp_year: this.dfCardExpirationYear()
						,number: this.dfCardNumber()
					},
					/**
					 * 2016-03-02
					 * @param {Number} status
					 * @param {Object} response
					 */
					function(status, response) {
						if (200 === status) {
							// 2016-03-02
							// https://stripe.com/docs/custom-form#step-3-sending-the-form-to-your-server
							_this.token = response.id;
							_this.placeOrderInternal();
						}
						else {
							// 2016-03-02
							// https://stripe.com/docs/api#errors
							_this.showErrorMessage(response.error.message);
						}
					}
				);
			}
		}
	}
});});
