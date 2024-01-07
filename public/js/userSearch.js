async function userSearch() {
    if (document.getElementById('searchBarInput').value.length >= 3) {
        const searchBar = document.querySelector('.searchbar');
        let updateResult = document.getElementById('updateResult');

        if (updateResult) {
            searchBar.removeChild(updateResult);
        }

        const response = await fetch('/api/all_users_json/' + document.getElementById('searchBarInput').value);
        let data = await response.json();

        updateResult = document.createElement('a');
        updateResult.id = 'updateResult';
        updateResult.className = 'row';
        updateResult.style = 'background-color: white; text-transform: uppercase; text-decoration: none; color: black; margin: 0 auto';

        for (let i = 0; i < data.length; i++) {
            updateResult.href = '/profile/' + data[i].id;
            updateResult.innerHTML = data[i].first_name + ' ' + data[i].last_name;
            searchBar.appendChild(updateResult.cloneNode(true));
        }
    }

    if (document.getElementById('assign_to').value.length >= 3) {
        const searchBar = document.querySelector('.searchAssignee');
        let assigneeResult = document.getElementById('updateAssignees');

        if (assigneeResult) {
            searchBar.removeChild(assigneeResult);
        }

        const response = await fetch('/api/all_users_json/' + document.getElementById('assign_to').value);
        let data = await response.json();


        for (let i = 0; i < data.length; i++) {
            let assigneeResult = document.createElement('a');
            assigneeResult.className = 'row';
            assigneeResult.id = 'assigneeResult';
            assigneeResult.style =
                'background-color: #d3d3d3; text-transform: uppercase; ' +
                'text-decoration: none; ' +
                'color: black; border: 1px solid; border-radius: 5px; ' +
                'border-color: gray; margin: 0 auto';
            assigneeResult.href = '#';
            assigneeResult.innerHTML = data[i].id + ' ' + data[i].first_name + ' ' + data[i].last_name;

            assigneeResult.addEventListener('click', (event) => {
                event.preventDefault();
                document.getElementById('assign_to').value = assigneeResult.innerHTML;
            });

            searchBar.appendChild(assigneeResult);
        }

    }

    if (document.getElementById('searchBarInput').value.length < 3) {
        while (document.getElementById('updateResult')) {
            document.getElementById('updateResult').remove();
        }
    }

    if (document.getElementById('assign_to').value.length < 3) {
        while (document.getElementById('assigneeResult')) {
            console.log(' remove');
            document.getElementById('assigneeResult').remove();
        }
    }
}


function debounce(func, delay) {
    let timeoutId;

    return function (...args) {
        clearTimeout(timeoutId);

        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}

const userSearchDebounced = debounce(userSearch, 500);

