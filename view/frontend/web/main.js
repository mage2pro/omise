// 2016-11-10
define([
	'df'
	,'Df_Checkout/js/data'
	,'Df_Payment/card'
	,'https://cdn.omise.co/omise.js'
], function(df, dfc, parent) {'use strict'; return parent.extend({
	// 2016-11-12
	// Omise requires the cardholder name:
	// https://www.omise.co/omise-js-api#createtoken(type,-object,-callback)
	defaults: {df: {card: {requireCardholder: true}}},
	/**
	 * 2016-11-12
	 * https://www.omise.co/which-credit-cards-does-omise-accept
	 * 2017-02-05
	 * The bank card network codes: https://mage2.pro/t/2647
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
	 * 2016-12-24
	 * 3D Secure redirection.
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @used-by placeOrderInternal()
	 */
	onSuccess: function(redirectUrl) {
		redirectUrl && redirectUrl.length
			? window.location.replace(redirectUrl)
			: this._super()
		;
	},
	/**
	 * 2016-11-12
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
				Omise.createToken('card',
					{
						expiration_month: this.creditCardExpMonth()
						,expiration_year: this.creditCardExpYear()
						// 2016-11-12
						// https://www.omise.co/tokens-api#tokens-create
						// «The cardholder name as printed on the card.»
						// This parameter is required:
						// https://www.omise.co/omise-js-api#createtoken(type,-object,-callback)
						,name: this.cardholder()
						,number: this.creditCardNumber()
						,security_code: this.creditCardVerificationNumber()
					},
					/**
					 * 2016-11-12
					 * @param {Number} status
					 * @param {Object} response
					 */
					function(status, response) {
						if (200 === status) {
							_this.token = response.id;
							_this.placeOrderInternal();
						}
						else {
							// 2016-11-12
							// https://www.omise.co/omise-js-api#createtoken(type,-object,-callback)
							_this.showErrorMessage(response.message);
						}
					}
				);
			}
		}
	}
});});