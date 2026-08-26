document.addEventListener('DOMContentLoaded', function () {
    const paperSelect = document.getElementById('paper_id');
    const topicSelect = document.getElementById('topic_id');
    const subTopicSelect = document.getElementById('sub_topic_id');
    const qType = document.getElementById('question_type');
    const scoringSection = document.getElementById('scoring-section');

    const sectionMap = {
        mcq: 'options-section',
        multiple_select: 'options-section',
        one_word: 'oneword-section',
        table_mcq: 'table-mcq-section',
        drag_and_drop: 'drag-drop-section',
        dropdown: 'dropdown-section',
        paragraph: 'paragraph-builder-section'
    };

    //TinyMCE
    
   function initializeTinyMCE(target) {

    tinymce.init({

        target: target,

        plugins: 'lists paste image table',

        toolbar: 'undo redo | bold italic underline | bullist numlist | image table',

        menubar: 'insert table format',

        paste_data_images: true,

        paste_as_text: false,

        table_class_list: [
            {
                title: 'Bootstrap Table',
                value: 'table table-bordered table-sm'
            }
        ],

        table_default_attributes: {
            border: '0'
        },

        table_default_styles: {
            width: '100%',
            borderCollapse: 'collapse'
        },

        setup: function (editor) {

            editor.on('ExecCommand', function (e) {

                if (e.command === 'mceInsertTable') {

                    setTimeout(() => {

                        editor.dom
                            .select('table')
                            .forEach(table => {

                                editor.dom.addClass(
                                    table,
                                    'table table-bordered table-sm'
                                );

                            });

                    }, 10);

                }

            });

                editor.on('PastePostProcess', function (e) {

                    /*
                    |--------------------------------------------------------------------------
                    | Remove pasted font family and font size
                    |--------------------------------------------------------------------------
                    |
                    | This keeps formatting such as:
                    | - Bold
                    | - Italic
                    | - Underline
                    | - Lists
                    | - Tables
                    | - Images
                    |
                    | But removes arbitrary font-family and font-size
                    | brought in from Word, websites, etc.
                    |
                    */

                    e.node.querySelectorAll('*').forEach(element => {

                        // Remove font-related HTML attributes
                        element.removeAttribute('face');
                        element.removeAttribute('size');

                        // Remove font family / size from inline styles
                        if (element.hasAttribute('style')) {

                            element.style.removeProperty('font-family');
                            element.style.removeProperty('font-size');

                            // If style is now empty, remove it completely
                            if (!element.getAttribute('style').trim()) {
                                element.removeAttribute('style');
                            }
                        }

                    });


                    /*
                    |--------------------------------------------------------------------------
                    | Normalize pasted images
                    |--------------------------------------------------------------------------
                    */

                    e.node.querySelectorAll('img').forEach(img => {

                        img.removeAttribute('width');
                        img.removeAttribute('height');

                        img.style.maxWidth = '100%';
                        img.style.height = 'auto';

                    });

                });

            editor.on('change', function () {

                tinymce.triggerSave();

            });

        }

    });

}

document.querySelectorAll('textarea[name="question_text"]').forEach(textarea => {

    initializeTinyMCE(textarea);

});

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

        // Show/Hide Scoring section
        const marksInput = document.querySelector('input[name="marks"]');

        if (scoringSection) {

            if (type === 'paragraph') {

                scoringSection.classList.add('d-none');

                if (marksInput) {
                    marksInput.required = false;
                    marksInput.disabled = true;
                    marksInput.value = 0;
                }

            } else {

                scoringSection.classList.remove('d-none');

                if (marksInput) {
                    marksInput.disabled = false;
                    marksInput.required = true;

                    if (marksInput.value == 0) {
                        marksInput.value = 2;
                    }
                }

            }

        }

            // Change section heading
        const contentHeading = document.getElementById('contentHeading');

        if (contentHeading) {
            contentHeading.innerHTML =
                `<i class="bi bi-pencil-square me-2" style="color:#832b00;"></i>` +
                (type === 'paragraph'
                    ? 'Scenario Builder'
                    : 'Question Content');
        }

        // Clear required on all section inputs
        Object.keys(sectionMap).forEach(key => {
            document.querySelectorAll(`#${sectionMap[key]} input, #${sectionMap[key]} textarea`)
                .forEach(input => input.required = false);
        });

            // Change Question/Paragraph label
            const questionLabel = document.getElementById('questionLabel');

            if (questionLabel) {
                questionLabel.textContent =
                    (type === 'paragraph')
                        ? 'Enter the scenario below'
                        : 'Question';
            }

            // Show selected section & make required
            const sectionId = sectionMap[type];

            if (sectionId) {
                const el = document.getElementById(sectionId);

                if (el) {

                    el.classList.remove('d-none');

                    // Paragraph Builder manages its own validation
                    if (sectionId !== 'paragraph-builder-section') {

                        el.querySelectorAll('input, textarea').forEach(input => {

                            if (input.type !== 'checkbox') {
                                input.required = true;
                            }

                        });

                    }

                }
            }

          
            // Automatically create the first child question
            if (type === 'paragraph'
                && !window.scenarioChildren?.length
                && childContainer.children.length === 0) {

                createChildCard();

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

    
        // ==========================================
        // Scenario Builder
        // ==========================================

        const addChildBtn = document.getElementById('addChildQuestion');
        const childContainer = document.getElementById('childQuestionsContainer');
        const childTemplate = document.getElementById('childQuestionTemplate');

        let nextChildId = 0;

        // ------------------------------------------
        // Add Child Question
        // ------------------------------------------

        function createChildCard(childData = null) {
           
            // Collapse existing cards
            childContainer.querySelectorAll('.child-question-card').forEach(existing => {

                bootstrap.Collapse
                    .getOrCreateInstance(
                        existing.querySelector('.child-body'),
                        { toggle: false }
                    )
                    .hide();

                existing.querySelector('.child-toggle')
                    .classList.add('collapsed');

            });

            // Build template
            let html = childTemplate.innerHTML;

            html = html.replaceAll('__INDEX__', nextChildId);

            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;

            const card = wrapper.firstElementChild;

            card.dataset.index = nextChildId;

            card.querySelector('.child-title').textContent =
                `Question ${childContainer.querySelectorAll('.child-question-card').length + 1}`;

            childContainer.appendChild(card);

            // TinyMCE
            const childEditor = card.querySelector('.child-question-editor');

            childEditor.id = `child-editor-${nextChildId}`;

            initializeTinyMCE(childEditor);

            // --------------------------------------------------
            // EDIT MODE - Populate values
            // --------------------------------------------------

            if (childData) {

                // Hidden ID
                const hiddenId = document.createElement('input');

                hiddenId.type = 'hidden';
                hiddenId.name = `child_questions[${nextChildId}][id]`;
                hiddenId.value = childData.id;

                card.appendChild(hiddenId);

                // Question Type
                card.querySelector('.child-question-type').value =
                    childData.question_type;

                // Question
                const editor = card.querySelector('.child-question-editor');

                editor.value = childData.question_text ?? '';

                // Marks
                card.querySelector(
                    `input[name="child_questions[${nextChildId}][marks]"]`
                ).value = childData.marks;

                // MCQ / Multiple Select
                if (childData.options) {

                    Object.entries(childData.options).forEach(([key, value]) => {

                        const option = card.querySelector(
                            `input[name="child_questions[${nextChildId}][options][${key}]"]`
                        );

                        if (option) option.value = value;

                    });

                }

                // One Word
                if (childData.question_type === 'one_word') {

                    card.querySelector(
                        `input[name="child_questions[${nextChildId}][answer]"]`
                    ).value = childData.correct_answers[0] ?? '';

                }

            }

            // Configure UI
            updateChildQuestionType(card);

            // Tick correct answers
            if (childData?.correct_answers) {

                childData.correct_answers.forEach(answer => {

                    const input = card.querySelector(
                        `.child-correct[value="${answer}"]`
                    );

                    if (input) {
                        input.checked = true;
                    }

                });

            }

            // Expand
            bootstrap.Collapse
                .getOrCreateInstance(
                    card.querySelector('.child-body'),
                    { toggle: false }
                )
                .show();

            card.querySelector('.child-toggle')
                .classList.remove('collapsed');

            nextChildId++;

            return card;
        }

                addChildBtn?.addEventListener('click', function () {

                    createChildCard();

                });


        // ------------------------------------------
        // Child Question Type Change
        // ------------------------------------------

        document.addEventListener('change', function (e) {

            if (!e.target.classList.contains('child-question-type')) return;

            const card = e.target.closest('.child-question-card');

            updateChildQuestionType(card);

        });


        // ------------------------------------------
        // Update Child UI
        // ------------------------------------------

        function updateChildQuestionType(card) {

            const type = card.querySelector('.child-question-type').value;

            const optionSection = card.querySelector('.child-options-section');
            const oneWordSection = card.querySelector('.child-oneword-section');
            const helpText = card.querySelector('.child-option-help');
            const subtitle = card.querySelector('.child-subtitle');

            subtitle.textContent =
                card.querySelector('.child-question-type').selectedOptions[0].text;

            if (type === 'one_word') {

                optionSection.classList.add('d-none');
                oneWordSection.classList.remove('d-none');

                return;

            }

            optionSection.classList.remove('d-none');
            oneWordSection.classList.add('d-none');

            const inputs = card.querySelectorAll('.child-correct');

            if (type === 'mcq') {

                helpText.textContent = 'Select one correct answer.';

                inputs.forEach(input => {
                    input.type = 'radio';
                });

            } else {

                helpText.textContent = 'Select one or more correct answers.';

                inputs.forEach(input => {
                    input.type = 'checkbox';
                });

            }

        }


        // ------------------------------------------
        // Expand / Collapse
        // ------------------------------------------

        document.addEventListener('click', function (e) {

            const header = e.target.closest('.child-header');

            if (!header) return;

            if (e.target.closest('.remove-child')) return;

            const card = header.closest('.child-question-card');

            const body = card.querySelector('.child-body');

            const icon = card.querySelector('.child-toggle');

            const isCollapsed = !body.classList.contains('show');

            // Collapse every card
            childContainer.querySelectorAll('.child-question-card').forEach(c => {

                bootstrap.Collapse
                    .getOrCreateInstance(
                        c.querySelector('.child-body'),
                        { toggle: false }
                    )
                    .hide();

                c.querySelector('.child-toggle')
                .classList
                .add('collapsed');

            });

            // Expand selected
            if (isCollapsed) {

                bootstrap.Collapse
                    .getOrCreateInstance(body, { toggle: false })
                    .show();

            icon.classList.remove('collapsed');

            }

        });


        // ------------------------------------------
        // Remove Child Question
        // ------------------------------------------

        document.addEventListener('click', function (e) {

            const removeBtn = e.target.closest('.remove-child');

            if (!removeBtn) return;

            if (!confirm('Delete this child question?')) return;

            const card = removeBtn.closest('.child-question-card');

            const textarea = card.querySelector('.child-question-editor');

            const editor = tinymce.get(textarea.id);

            if (editor) {
                editor.remove();
            }

            card.remove();

            renumberChildQuestions();

            const cards = childContainer.querySelectorAll('.child-question-card');

            if (cards.length) {

                const last = cards[cards.length - 1];

                bootstrap.Collapse
                    .getOrCreateInstance(
                        last.querySelector('.child-body'),
                        { toggle: false }
                    )
                    .show();

                last.querySelector('.child-toggle')
                .classList
                .remove('collapsed');

            }

        });


        // ------------------------------------------
        // Renumber
        // ------------------------------------------

        function renumberChildQuestions() {

            childContainer
                .querySelectorAll('.child-question-card')
                .forEach((card, index) => {

                    card.querySelector('.child-title').textContent =
                        `Question ${index + 1}`;

                });

        }

            // ------------------------------------------
            // Initial Load
            // ------------------------------------------

            // Edit Page
            if (window.scenarioChildren?.length) {

                window.scenarioChildren.forEach(child => {

                    createChildCard(child);

                });

            }
            // Create Page
            else {

                createChildCard();

            }

    // ✅ Ensure TinyMCE saves content
    document.querySelector('form')?.addEventListener('submit', function () {
        if (window.tinymce) tinymce.triggerSave();
    });
});
