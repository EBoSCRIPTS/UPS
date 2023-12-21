async function getDepartments() {
    const response = await fetch('/api/get_all_departments');
    const selectField = document.getElementById('department_id');
    let data = await response.json();

    console.log(data);

    for (let i = 0; i < data.length; i++) {
        let departmentOption = document.createElement('option');
        departmentOption.value = data[i].id;
        departmentOption.text = data[i].name;
        selectField.append(departmentOption);
    }
}

window.onload = function() {
    getDepartments();
    sessionStorage.clear()
    sessionStorage.setItem('count', 0);
}

function addField()
{
    let count = sessionStorage.getItem('count');
    count = Number(count);
    count = count + 1;
    sessionStorage.setItem('count', count);
    const projectStatusFields = document.getElementsByClassName('project-status-fields');
    const inputField = document.createElement('input');
    const counter = document.createElement('input');
    inputField.type = 'text';
    inputField.name = 'project_status_field' + count;
    inputField.className = 'form-control mt-2';
    inputField.placeholder = 'Status field name...';

    projectStatusFields[0].appendChild(inputField);

    counter.type = 'hidden';
    counter.name = 'counter';
    counter.value = count;
    projectStatusFields[0].appendChild(counter);

}
