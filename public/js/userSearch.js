


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
        updateResult.style = 'background-color: white; text-transform: uppercase; text-decoration: none; color: black';

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

        assigneeResult = document.createElement('a');
        assigneeResult.id = 'assigneeResult';
        assigneeResult.className = 'row';
        assigneeResult.style = 'background-color: #d3d3d3; text-transform: uppercase; text-decoration: none; color: black; border: 1px solid; border-radius: 5px; border-color: gray; ';

        for (let i = 0; i < data.length; i++) {
            assigneeResult.href = '#';
            assigneeResult.innerHTML = data[i].id + ' ' + data[i].first_name + ' ' + data[i].last_name;
            searchBar.appendChild(assigneeResult.cloneNode(true));
        }
        document.getElementById('assigneeResult').addEventListener('click', () => {
            document.getElementById('assign_to').value = assigneeResult.innerHTML;
        });

    }

    if(document.getElementById('searchBarInput').value.length < 3)
    {
        while(document.getElementById('updateResult'))
        {
            document.getElementById('updateResult').remove();
        }
    }

    if(document.getElementById('assign_to').value.length < 3)
    {
        while(document.getElementById('assigneeResult'))
        {
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

