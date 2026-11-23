const studio = document.querySelector('[data-studio]');

if (studio) {
    const order = studio.querySelector('[data-studio-order]');
    const rows = () => [...order.querySelectorAll('[data-studio-row]')];
    const panels = [...studio.querySelectorAll('[data-studio-block]')];
    const preview = studio.querySelector('[data-studio-preview]');
    const previewContent = preview.querySelector('[data-studio-preview-content]');
    let active;

    function updatePreview() {
        const value = (key) => active.querySelector('[data-field="' + key + '"]')?.value.trim();
        const heading = document.createElement('h2');
        const description = document.createElement('p');
        heading.textContent = value('title') || value('name') || value('label') || active.querySelector('legend').textContent;
        description.textContent = [value('accent'), value('lead') || value('caption')].filter(Boolean).join(' ');
        previewContent.replaceChildren(heading);
        if (description.textContent) previewContent.append(description);
        const image = active.querySelector('[data-field="image"]');
        if (image?.value) {
            const option = image.selectedOptions[0];
            const photo = document.createElement('img');
            photo.src = option.dataset.imageUrl;
            photo.alt = option.textContent.trim();
            previewContent.append(photo);
        }
    }

    function showBlock(panel) {
        active = panel;
        panels.forEach((item) => { item.hidden = item !== panel; });
        rows().forEach((row) => {
            const link = row.querySelector('[data-studio-select]');
            if (row.dataset.key === panel.dataset.studioBlock) link.setAttribute('aria-current', 'true');
            else link.removeAttribute('aria-current');
        });
        updatePreview();
    }

    function updateControls() {
        rows().forEach((row, index, all) => {
            row.querySelector('[data-move="-1"]').disabled = index === 0;
            row.querySelector('[data-move="1"]').disabled = index === all.length - 1;
        });
    }

    rows().forEach((row) => {
        row.querySelector('[data-studio-move]').hidden = order.hasAttribute('data-fixed-order');
        row.querySelector('[data-studio-select]').addEventListener('click', (event) => {
            event.preventDefault();
            const panel = panels.find((item) => item.dataset.studioBlock === row.dataset.key);
            showBlock(panel);
            if (window.matchMedia('(max-width: 680px)').matches) panel.scrollIntoView({ block: 'start' });
        });
        row.querySelectorAll('[data-move]').forEach((button) => button.addEventListener('click', () => {
            const direction = Number(button.dataset.move);
            const neighbor = direction < 0 ? row.previousElementSibling : row.nextElementSibling;
            if (! neighbor) return;
            if (direction < 0) neighbor.before(row);
            else neighbor.after(row);
            updateControls();
            (button.disabled ? row.querySelector('[data-studio-select]') : button).focus();
        }));
    });

    studio.addEventListener('input', updatePreview);
    studio.addEventListener('invalid', (event) => {
        const panel = event.target.closest('[data-studio-block]');
        if (panel?.hidden) showBlock(panel);
    }, true);
    const initialPanel = panels.find((panel) => panel.querySelector('.workspace-error'))
        || panels.find((panel) => '#' + panel.id === window.location.hash)
        || panels[0];
    showBlock(initialPanel);
    updateControls();
    preview.hidden = false;
}

const magazine = document.querySelector('[data-magazine]');
const writing = magazine?.querySelector('[data-magazine-form]');

if (writing) {
    const text = writing.elements.body;
    const preview = magazine.querySelector('.magazine-preview');
    const title = preview.querySelector('[data-preview-title]');
    const signature = preview.querySelector('[data-preview-signature]');
    const body = preview.querySelector('[data-preview-body]');
    const photo = preview.querySelector('[data-preview-photo]');
    const tools = writing.querySelector('[data-magazine-tools]');
    const originalPhoto = photo?.getAttribute('src');

    function updateMagazine() {
        title.textContent = writing.elements.title.value.trim() || 'Tu título';
        signature.textContent = writing.elements.public_name.value.trim() || 'Tu firma';
        const fragment = document.createDocumentFragment();
        for (const source of text.value.split(/\r?\n/)) {
            const line = source.trim();
            if (! line) continue;
            const heading = line.startsWith('## ');
            const quote = line.startsWith('> ');
            const paragraph = document.createElement(heading ? 'h2' : quote ? 'blockquote' : 'p');
            const content = line.slice(heading ? 3 : quote ? 2 : 0);
            for (const part of content.split(/(\*\*[^*]+\*\*|\*[^*]+\*)/)) {
                const bold = part.startsWith('**') && part.endsWith('**') && part.length > 4;
                const italic = part.startsWith('*') && part.endsWith('*') && part.length > 2;
                if (bold || italic) {
                    const mark = document.createElement(bold ? 'strong' : 'em');
                    mark.textContent = part.slice(bold ? 2 : 1, bold ? -2 : -1);
                    paragraph.append(mark);
                } else paragraph.append(part);
            }
            fragment.append(paragraph);
        }
        if (! fragment.childNodes.length) {
            const placeholder = document.createElement('p');
            placeholder.textContent = 'El texto aparecerá aquí mientras escribes.';
            fragment.append(placeholder);
        }
        body.replaceChildren(fragment);
    }

    tools.hidden = false;
    tools.addEventListener('click', (event) => {
        const control = event.target.closest('button[data-format]');
        if (! control) return;

        let start = text.selectionStart;
        const end = text.selectionEnd;
        const selection = text.value.slice(start, end);
        const choice = control.dataset.format;
        let replacement;
        if (choice === 'bold' || choice === 'italic') {
            const marker = choice === 'bold' ? '**' : '*';
            replacement = marker + (selection || 'texto') + marker;
        } else {
            const marker = choice === 'heading' ? '## ' : '> ';
            start = text.value.lastIndexOf('\n', start - 1) + 1;
            replacement = marker + (text.value.slice(start, end) || (choice === 'heading' ? 'Subtítulo' : 'Cita'));
        }

        text.setRangeText(replacement, start, end, 'end');
        text.focus();
        updateMagazine();
    });

    writing.addEventListener('input', updateMagazine);
    const photoInput = writing.elements.photo;
    let reader;
    function updatePhoto() {
        reader?.abort();
        const file = photoInput.files[0];
        if (file && file.size <= 3 * 1024 * 1024 && ['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            reader = new FileReader();
            reader.addEventListener('load', () => {
                photo.src = reader.result;
                photo.hidden = false;
            });
            reader.readAsDataURL(file);
        } else if (originalPhoto && ! writing.elements.remove_photo?.checked) {
            photo.src = originalPhoto;
            photo.hidden = false;
        } else {
            photo.removeAttribute('src');
            photo.hidden = true;
        }
    }
    photoInput?.addEventListener('change', updatePhoto);
    writing.elements.remove_photo?.addEventListener('change', updatePhoto);

    const mobile = window.matchMedia('(max-width: 680px)');
    const mobileTabs = document.querySelector('[data-magazine-tabs]');
    let screen = 'write';
    function showScreen() {
        mobileTabs.hidden = ! mobile.matches;
        writing.hidden = mobile.matches && screen === 'preview';
        preview.hidden = mobile.matches && screen === 'write';
        mobileTabs.querySelectorAll('button').forEach((button) => {
            button.setAttribute('aria-pressed', String(button.dataset.screen === screen));
        });
    }
    mobileTabs?.addEventListener('click', (event) => {
        const button = event.target.closest('[data-screen]');
        if (! button) return;
        screen = button.dataset.screen;
        showScreen();
    });
    if (mobileTabs) {
        mobile.addEventListener('change', showScreen);
        showScreen();
    }
    updateMagazine();
}
