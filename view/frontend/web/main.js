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
	 * 2016-11-12
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
	 * 2016-11-12
	 * @override
	 * @see https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L127-L159
	 * @used-by https://github.com/magento/magento2/blob/2.1.0/lib/web/knockoutjs/knockout.js#L3863
	 * @param {this} _this
	*/
	placeOrder: function(_this) {
		if (this.validate()) {
			Omise.createToken('card',
				{
					expiration_month: this.dfCardExpirationMonth()
					,expiration_year: this.dfCardExpirationYear()
					// 2016-11-12
					// https://www.omise.co/tokens-api#tokens-create
					// «The cardholder name as printed on the card.»
					// This parameter is required:
					// https://www.omise.co/omise-js-api#createtoken(type,-object,-callback)
					,name: 'TEST TEST'
					,number: this.dfCardNumber()
					,security_code: this.dfCardVerification()
				},
				/**
				 * 2016-11-12
				 * @param {Number} status
				 * @param {Object} response
				 */
				function(status, response) {
					if (200 === status) {
						// 2016-03-02
						// https://stripe.com/docs/custom-form#step-3-sending-the-form-to-your-server
						_this.token = response.id;
						debugger;
						//_this.placeOrderInternal();
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
});});
