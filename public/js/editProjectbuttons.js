let buttonsAppended = false;

function enableDisabledInputs() {
    const inputs = document.getElementsByTagName('input');
    const editButton = document.getElementById('editButton');
    const saveButton = document.createElement('button');
    const addFieldButton = document.createElement('button');

    if (!buttonsAppended) {
        editButton.textContent = '';
        editButton.className = 'btn btn-sm float-end'

        saveButton.textContent = '✔';
        saveButton.className = 'btn btn-success btn-sm mb-2';
        saveButton.type = 'submit';
        saveButton.onclick = function () {
            removeButtons();
        };


        addFieldButton.textContent = '+';
        addFieldButton.className = 'btn btn-primary btn-sm mb-2 me-2';
        addFieldButton.type = 'button';
        addFieldButton.onclick = addField;

        editButton.appendChild(addFieldButton);
        editButton.appendChild(saveButton);

        buttonsAppended = true;
    }

    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].disabled) {
            inputs[i].value = inputs[i].placeholder;
            inputs[i].disabled = false;
        }
    }
}

function removeButtons() {
    const editButton = document.getElementById('editButton');
    while (editButton.firstChild) {
        editButton.removeChild(editButton.firstChild);
    }
    buttonsAppended = false;
}

function addField() {
    const projectStatusFields = document.getElementsByClassName('status-fields');
    const labelNewField = document.createElement('label');
    const inputField = document.createElement('input');

    labelNewField.for = 'status[]';
    labelNewField.textContent = 'New Status';

    inputField.type = 'text';
    inputField.name = 'status[]';
    inputField.className = 'form-control mt-2';
    inputField.placeholder = 'Status field name...';

    projectStatusFields[0].appendChild(labelNewField);
    projectStatusFields[0].appendChild(inputField);
}
