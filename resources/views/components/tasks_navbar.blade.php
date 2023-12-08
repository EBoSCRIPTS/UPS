<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="">CREATE A NEW TASK</a>

    <select id="project_id">
        <option disabled selected>PROJECTS</option>
    </select>
</nav>


<script>
    async function getProjects() {
        const response = await fetch('/api/get_all_projects');
        const selectField = document.getElementById('project_id');
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
        getProjects();
    }
</script>
