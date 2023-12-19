async function getProjects(field_id) {
    const response = await fetch('/api/get_all_projects');
    let selectField = document.getElementById(field_id);
    let data = await response.json();

    for (let i = 0; i < data.length; i++) {
        let projectOption = document.createElement('option');
        projectOption.value = data[i].id;
        projectOption.text = data[i].name;
        projectOption.href = data[i].id;
        selectField.append(projectOption);
    }

    if(field_id === 'project_id'){
        selectField.addEventListener('change', function(){
            const selectedValue = this.value;
            window.location.href='/tasks/projects/' + selectedValue;
        })
    }

}

function assignToMe()
{
    const assignToMe = document.getElementById('assign_to');
    assignToMe.value = '{{ Auth::user()->id }}';
    assignToMe.text = '{{Auth::user()->first_name}}'
}

window.onload = function() {
    getProjects('project_id');
    getProjects('project');
}
