var config = {
	paths: {
		// 2016-03-02
		// Хитрый трюк: «?» позволяет избежать автоматического добаления расширения «.js».
		// https://coderwall.com/p/y4vk_q/requirejs-and-external-scripts
		// 2016-11-10
		// https://www.omise.co/collecting-card-information#a-full-fledged-example
		// https://github.com/omise/omise.js
		'Dfe_Omise/API': 'https://cdn.omise.co/omise'
	}
	,shim: {'Dfe_Omise/API': {exports: 'Omise'}}
};