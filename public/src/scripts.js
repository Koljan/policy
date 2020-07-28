let DataController = (function () {
	let formErrors = [];
	let formElementError = function (elementLabel, elementError) {
		this.elementLabel = elementLabel;
		this.error = elementError;
	}
	return {
		addFormError: function(elementName, elementError){
			let newItem = new formElementError(elementName, elementError);
			formErrors.push(newItem);
		},
		getFormErrors: function(){
			return formErrors;
		},
		clearFormErrors: function(){
			formErrors = [];
			return formErrors;
		},
		postData: function(formData){
			let xhReq;
			xhReq = new XMLHttpRequest();
			xhReq.open('POST', 'calculator.php?action=calculateInsurance', false);
			xhReq.send(formData);
			return JSON.parse(xhReq.responseText);
			
			// fetch('calculator.php?action=calculateInsurance',{
			// 	method: 'POST',
			// 	body: formData
			// })
			// .then((response) => response.json())
			// .then((responseJSON) => {
			// 	 console.log(responseJSON);
			// });
		}
	}
})();

let UIController = (function () {
	
	let DOMstrings = {
		formCalculation: '#calculate-form',
		calculateAction: '#calculate-action-button',
		estimatedCarValue: '#estimated-car-value',
		taxPercentage: '#tax-percentage',
		nbOfInstalments: '#nb-of-instalments',
		formErrorDiv: '#calculate-form-errors',
	};
	let renderFormErrors = function(formErrors) {
		let formWrapper;
		formWrapper = document.querySelector(DOMstrings.formErrorDiv);
		
		
		formErrors.forEach(function(e){
			let html = `<small class="form-text text-muted">\n${e.elementLabel} : ${e.error}</small>`;
			formWrapper.innerHTML +=(html);
		});
		
	}
	
	return {
		renderFormErrors: function(formErrors)
		{
			return renderFormErrors(formErrors);
		},
		getDOMstrings: function () {
			return DOMstrings;
		},
		clearErrorDiv: function () {
			document.querySelector(DOMstrings.formErrorDiv).innerHTML = '';
		}
	}
})();

let controller = (function (DataCtrl, UICtrl) {
	let setupEventListeners = function () {
		const DOM = UICtrl.getDOMstrings();
		let calculateAction = document.querySelector(DOM.calculateAction);
		calculateAction.addEventListener('click', function(e){
			clearErrorsOnSubmit();
			
			const formElements = e.target.form.elements;
			[...formElements].forEach(function(e){
				if( false === e.validity.valid )
				{
					DataCtrl.addFormError(e.placeholder, e.validationMessage);
				}
			});
			
			if(DataCtrl.getFormErrors().length)
			{
				UICtrl.renderFormErrors(DataCtrl.getFormErrors())
			}else{
				e.preventDefault();
				var formData = new FormData(e.target.form);
				formData.set('hour-submitted', new Date().getHours());
				var test22 = DataCtrl.postData(formData);
				// test22.then(v => console.log(v));
				console.log(test22[0].basePremium);
			}
		});
	};
	
	let clearErrorsOnSubmit = function ()
	{
		DataCtrl.clearFormErrors();
		UICtrl.clearErrorDiv();
	};
	return {
		init: function () {
			setupEventListeners();
		}
	};

})(DataController, UIController);
controller.init();