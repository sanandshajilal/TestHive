document.addEventListener('DOMContentLoaded', function () {
    const paperSelect = document.getElementById('paper_id');
    const topicSelect = document.getElementById('topic_id');
    const subTopicSelect = document.getElementById('sub_topic_id');
    const qType = document.getElementById('question_type');

    const sectionMap = {
        mcq: 'options-section',
        multiple_select: 'options-section',
        one_word: 'oneword-section',
        table_mcq: 'table-mcq-section',
        drag_and_drop: 'drag-drop-section',
        dropdown: 'dropdown-section'
    };

    // 🔒 Hide all sections initially
    Object.values(sectionMap).forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('d-none');
    });

    // 🔁 Load topics on paper change
    paperSelect?.addEventListener('change', function () {
        const paperId = this.value;
        topicSelect.innerHTML = '<option value="">Loading...</option>';
        subTopicSelect.innerHTML = '<option value="">-- Optional Sub Topic --</option>';

        fetch(`/api/topics/${paperId}`)
            .then(res => res.json())
            .then(data => {
                topicSelect.innerHTML = '<option value="">-- Select Topic --</option>';
                data.forEach(topic => {
                    topicSelect.innerHTML += `<option value="${topic.id}">${topic.name}</option>`;
                });
            });
    });

    // 🔁 Load subtopics on topic change
    topicSelect?.addEventListener('change', function () {
        const topicId = this.value;
        subTopicSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`/api/subtopics/${topicId}`)
            .then(res => res.json())
            .then(data => {
                subTopicSelect.innerHTML = '<option value="">-- Optional Sub Topic --</option>';
                data.forEach(sub => {
                    subTopicSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                });
            });
    });

    // 🎯 Handle question type change
    qType?.addEventListener('change', function () {
        const type = this.value;

        // Hide all sections
        Object.values(sectionMap).forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('d-none');
        });

        // Clear required on all section inputs
        Object.keys(sectionMap).forEach(key => {
            document.querySelectorAll(`#${sectionMap[key]} input, #${sectionMap[key]} textarea`)
                .forEach(input => input.required = false);
        });

        // Show selected section & make required
        const sectionId = sectionMap[type];
        if (sectionId) {
            const el = document.getElementById(sectionId);
            if (el) {
                el.classList.remove('d-none');
                el.querySelectorAll('input, textarea').forEach(input => {
                    if (input.type !== 'checkbox') input.required = true;
                });
            }
        }
    });

    // 🧠 Restore section on page load (edit/validation error)
    setTimeout(() => {
        if (qType && qType.value) {
            qType.dispatchEvent(new Event('change'));
        }
    }, 50);

    // ❌ Remove row/option/blank
    document.addEventListener('click', function (e) {
        if  (e.target?.classList.contains('remove-option')) {
            e.target.closest('.drag-drop-option')?.remove();
        } else if (e.target?.classList.contains('remove-a-item')) {
            e.target.closest('.column-a-item')?.remove();
        } else if (e.target?.classList.contains('remove-b-item')) {
            e.target.closest('.column-b-item')?.remove();
        } else if (e.target?.classList.contains('remove-match')) {
            e.target.closest('.match-pair')?.remove();
        }
    });

    // ➕ Drag-and-Drop (Column Matching)
    document.getElementById('addColumnA')?.addEventListener('click', () => {
        const html = `
            <div class="input-group mb-2 column-a-item">
                <input type="text" name="column_a[]" class="form-control" placeholder="Enter Column A Item">
                <button type="button" class="btn btn-outline-danger remove-a-item">X</button>
            </div>`;
        document.getElementById('column-a-list').insertAdjacentHTML('beforeend', html);
    });

    document.getElementById('addColumnB')?.addEventListener('click', () => {
        const html = `
            <div class="input-group mb-2 column-b-item">
                <input type="text" name="column_b[]" class="form-control" placeholder="Enter Column B Item">
                <button type="button" class="btn btn-outline-danger remove-b-item">X</button>
            </div>`;
        document.getElementById('column-b-list').insertAdjacentHTML('beforeend', html);
    });

    document.getElementById('addMatch')?.addEventListener('click', () => {
        const html = `
            <div class="row g-2 align-items-center match-pair mb-2">
                <div class="col-5">
                    <input type="number" name="matching_from[]" class="form-control" placeholder="A Index (e.g. 0)">
                </div>
                <div class="col-5">
                    <input type="number" name="matching_to[]" class="form-control" placeholder="B Index (e.g. 2)">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-outline-danger remove-match">X</button>
                </div>
            </div>`;
        document.getElementById('match-list').insertAdjacentHTML('beforeend', html);
    });

  

    // ✅ Ensure TinyMCE saves content
    document.querySelector('form')?.addEventListener('submit', function () {
        if (window.tinymce) tinymce.triggerSave();
    });
});
