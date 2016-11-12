// 2016-11-10
/* global Omise */
define([
	'df'
	,'Df_Checkout/js/data'
	,'Df_Payment/card'
	,'Dfe_Omise/API'
], function(df, dfc, parent,
	/**
	 * 2016-11-12
	 * This parameter is not used and (as I have checked with a debugger) even is not initialized.
	 * The real Omise API is coming with the «Omise» global variable:
	 * https://www.omise.co/collecting-card-information#a-full-fledged-example
	 */
	stub
) {'use strict'; return parent.extend({
	/**
	 * 2016-11-10
	 * @returns {String[]}
	 */
	getCardTypes: function() {return ['VI', 'MC', 'AE', 'JCB', 'DI', 'DN', 'CUN'];},
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
			this.placeOrderInternal();
		}
	}
});});
