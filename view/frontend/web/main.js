// 2016-11-10
define([
	'df'
	,'Df_Checkout/js/data'
	,'Df_StripeClone/main'
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
		Omise.setPublicKey(this.publicKey());
		return this;
	},
    /**
	 * 2017-02-16
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L12-L19
	 * @used-by placeOrder()
	 * @param {Object|Number} status
	 * @returns {Boolean}
	 */
	tokenCheckStatus: function(status) {return 200 === status;},
    /**
	 * 2017-02-16
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L21-L29
	 * @used-by placeOrder()
	 * @param {Object} params
	 * @param {Function} callback
	 * @returns {Function}
	 */
	tokenCreate: function(params, callback) {return Omise.createToken('card', params, callback);},
    /**
	 * 2017-02-16
	 * https://www.omise.co/omise-js-api#createtoken(type,-object,-callback)
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L31-L39
	 * @used-by placeOrder()
	 * @param {Object|Number} status
	 * @param {Object} resp
	 * @returns {String}
	 */
	tokenErrorMessage: function(status, resp) {return resp.message;},
    /**
	 * 2017-02-16
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L41-L48
	 * @used-by placeOrder()
	 * @param {Object} resp
	 * @returns {String}
	 */
	tokenFromResponse: function(resp) {return resp.id;},
    /**
	 * 2017-02-16
	 * @override
	 * @see https://github.com/mage2pro/core/blob/2.0.11/StripeClone/view/frontend/web/main.js?ts=4#L50-L56
	 * @used-by placeOrder()
	 * @returns {Object}
	 */
	tokenParams: function() {return {
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
	};}
});});