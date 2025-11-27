document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.querySelector('input[name="title"]');
    const datalist = document.getElementById('title-suggestions');

    if (!titleInput || !datalist) {
        return;
    }

    let lastQuery = '';
    let timeoutId = null;

    titleInput.addEventListener('input', function () {
        const query = titleInput.value.trim();

        if (query.length < 2) {
            datalist.innerHTML = '';
            return;
        }

        if (query === lastQuery) {
            return;
        }

        lastQuery = query;

        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        // Small delay to avoid firing on every keystroke
        timeoutId = setTimeout(function () {
            fetch('ajax/title_suggestions.php?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    datalist.innerHTML = '';

                    data.forEach(function (title) {
                        const option = document.createElement('option');
                        option.value = title;
                        datalist.appendChild(option);
                    });
                })
                .catch(() => {
                    datalist.innerHTML = '';
                });
        }, 200);
    });
});
