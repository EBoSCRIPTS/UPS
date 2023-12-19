
async function userSearch() {
    if (document.getElementById('searchBarInput').value.length >= 3) {
        removeExistingResults();
        const searchBar = document.querySelector('.searchbar');
        let updateResult = document.getElementById('updateResult');

        if (updateResult) {
            searchBar.removeChild(updateResult);
        }

        const response = await fetch('http://127.0.0.1:8000/api/all_users_json/' + document.getElementById('searchBarInput').value);
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
}

function removeExistingResults() {
    const searchBar = document.querySelector('.searchbar');
    const existingResults = document.getElementById('updateResult');

    if (existingResults) {
        searchBar.removeChild(existingResults);
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

