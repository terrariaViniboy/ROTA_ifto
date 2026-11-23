function normalizeSearch(value) {
    return value
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/\p{M}/gu, '');
}

function initGuideSearch() {
    const input = document.getElementById('busca-guia');
    const list = document.getElementById('guia-lista');
    const empty = document.getElementById('guia-busca-vazia');

    if (!input || !list || input.disabled) {
        return;
    }

    const items = Array.from(list.querySelectorAll('[data-guia-item]'));

    const applyFilter = () => {
        const query = normalizeSearch(input.value);
        let visible = 0;

        items.forEach((item) => {
            const haystack = item.dataset.search ?? '';
            const match = query === '' || haystack.includes(query);
            item.hidden = !match;

            if (match) {
                visible += 1;
            }
        });

        if (empty) {
            empty.hidden = visible > 0;
        }

        list.hidden = visible === 0;
    };

    input.addEventListener('input', applyFilter);
    input.addEventListener('search', applyFilter);
}

document.addEventListener('DOMContentLoaded', initGuideSearch);
