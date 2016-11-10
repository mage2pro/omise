// 2016-11-10
define ([
	'df'
	,'Df_Checkout/js/data'
	,'Df_Payment/card'
	,'Dfe_Omise/API'
	,'jquery'
	,'Magento_Payment/js/model/credit-card-validation/credit-card-data'
], function(df, dfc, parent, API, $, creditCardData) {'use strict'; return parent.extend({
	/**
	 * 2016-11-10
	 * @returns {String[]}
	 */
	getCardTypes: function() {return ['VI', 'MC', 'AE', 'JCB', 'DI', 'DN', 'CUN'];},
	/**
	 * 2016-11-10
	 * @return {Object}
	*/
	initialize: function() {
		this._super();
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
