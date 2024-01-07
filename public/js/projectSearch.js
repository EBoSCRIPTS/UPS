async function getProjects(fieldId) {
    try {
        const response = await fetch('/api/get_all_projects');

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const projects = await response.json();
        const selectField = document.getElementById(fieldId);

        projects.forEach(project => {
            const option = new Option(project.name, project.id);
            selectField.appendChild(option);
        });

        if (fieldId === 'project_id') { //redirect user to the chosen project
            selectField.addEventListener('change', () => {
                window.location.href = `/tasks/projects/${selectField.value}`;
            });
        }
    } catch (error) {
        console.error('Failed to fetch projects:', error);
    }
}



window.onload = function () {
    getProjects('project_id');
    getProjects('project');
}
