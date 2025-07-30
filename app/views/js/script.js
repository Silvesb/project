const payment_methods = [];

function addPaymentMethodFields(formId) {
    const form = document.getElementById(formId);
    if (!form) {
        console.error(`Form with ID "${formId}" not found.`);
        return;
    }

    const index = payment_methods.length;
    const fieldset = document.createElement('fieldset');
    fieldset.className = 'border p-3 mb-3 rounded';
    fieldset.id = `payment-method-${index}`;

    const legend = document.createElement('legend');
    legend.textContent = `Payment Method ${index + 1}`;
    fieldset.appendChild(legend);

    const fieldsContainer = document.createElement('div');
    fieldsContainer.className = 'fields-container';

    // Create payment type radios
    const typeWrapper = document.createElement('div');
    const typeLabel = document.createElement('label');
    typeLabel.textContent = 'Payment Type';
    typeWrapper.appendChild(typeLabel);

    const typeValues = ['CreditCard', 'ACH'];
    const typeInputs = [];

    typeValues.forEach((val, i) => {
        const radioWrapper = document.createElement('div');
        radioWrapper.className = 'form-check';

        const radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = `type_${index}`;
        radio.value = val;
        radio.id = `type_${index}_${val}`;
        radio.className = 'form-check-input payment-type-radio';
        radio.required = true; // Always required
        
        if (i === 0) radio.checked = true;

        const label = document.createElement('label');
        label.className = 'form-check-label';
        label.htmlFor = radio.id;
        label.textContent = val;

        radioWrapper.appendChild(radio);
        radioWrapper.appendChild(label);
        typeWrapper.appendChild(radioWrapper);
        typeInputs.push(radio);
    });

    // Create fields for Credit Card
    const creditCardFields = document.createElement('div');
    creditCardFields.id = `credit-card-fields-${index}`;
    
    const cardNumber = createInput('Card Number', 'text', `card_number_${index}`);
    const expDate = createInput('Expiration Date', 'date', `expiration_date_${index}`);
    const cardholderName = createInput('Cardholder Name', 'text', `cardholder_name_${index}`);
    
    creditCardFields.appendChild(cardNumber.wrapper);
    creditCardFields.appendChild(expDate.wrapper);
    creditCardFields.appendChild(cardholderName.wrapper);

    // Create fields for ACH
    const achFields = document.createElement('div');
    achFields.id = `ach-fields-${index}`;
    achFields.style.display = 'none';
    
    const accountNumber = createInput('Account Number', 'text', `account_number_${index}`);
    const routingNumber = createInput('Routing Number', 'text', `routing_number_${index}`);
    const accountHolderName = createInput('Account Holder Name', 'text', `account_holder_name_${index}`);
    
    achFields.appendChild(accountNumber.wrapper);
    achFields.appendChild(routingNumber.wrapper);
    achFields.appendChild(accountHolderName.wrapper);

    fieldsContainer.appendChild(typeWrapper);
    fieldsContainer.appendChild(creditCardFields);
    fieldsContainer.appendChild(achFields);
    fieldset.appendChild(fieldsContainer);
    form.appendChild(fieldset);

    // Function to toggle required attributes
    const toggleRequired = (activeFields) => {
        // All possible fields
        const allFields = [
            cardNumber.input, expDate.input, cardholderName.input,
            accountNumber.input, routingNumber.input, accountHolderName.input
        ];
        
        // Remove required from all
        allFields.forEach(field => field.removeAttribute('required'));
        
        // Set required for active fields
        activeFields.forEach(field => field.setAttribute('required', 'true'));
    };

    // Initial setup
    toggleRequired([cardNumber.input, expDate.input, cardholderName.input]);

    // Add event listeners to radio buttons
    typeInputs.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.checked) {
                if (radio.value === 'CreditCard') {
                    creditCardFields.style.display = 'block';
                    achFields.style.display = 'none';
                    toggleRequired([cardNumber.input, expDate.input, cardholderName.input]);
                } else {
                    creditCardFields.style.display = 'none';
                    achFields.style.display = 'block';
                    toggleRequired([accountNumber.input, routingNumber.input, accountHolderName.input]);
                }
            }
        });
    });

    payment_methods.push({
        get type() {
            return typeInputs.find(input => input.checked)?.value || 'CreditCard';
        },
        get account_number() {
            return accountNumber.input.value;
        },
        get routing_number() {
            return routingNumber.input.value;
        },
        get card_number() {
            return cardNumber.input.value;
        },
        get expiration_date() {
            return expDate.input.value;
        },
        get cardholder_name() {
            return cardholderName.input.value;
        },
        get account_holder_name() {
            return accountHolderName.input.value;
        },
        getData() {
            const type = this.type;
            const data = {
                type: type,
            };
            
            if (type === 'CreditCard') {
                data.card_number = this.card_number;
                data.expiration_date = this.expiration_date;
                data.cardholder_name = this.cardholder_name;
            } else {
                data.account_number = this.account_number;
                data.routing_number = this.routing_number;
                data.account_holder_name = this.account_holder_name;
            }
            
            return data;
        }
    });
}

function createInput(labelText, type, name, attributes = {}) {
    const wrapper = document.createElement('div');
    wrapper.className = 'mb-3';

    const label = document.createElement('label');
    label.textContent = labelText;
    label.className = 'form-label';
    label.htmlFor = name;

    const input = document.createElement('input');
    input.type = type;
    input.name = name;
    input.id = name;
    input.className = 'form-control';

    Object.entries(attributes).forEach(([key, val]) => {
        input.setAttribute(key, val);
    });

    wrapper.appendChild(label);
    wrapper.appendChild(input);
    
    return { wrapper, input, label };
}

function getAllPaymentData() {
    return payment_methods.map(method => method.getData());
}