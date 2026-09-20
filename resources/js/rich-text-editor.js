import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';

const editors = new Map();

function initRichTextEditors(scope = document) {
    scope.querySelectorAll('[data-rich-text-editor]').forEach((el) => {
        if (editors.has(el)) return;

        const hiddenInput = el.querySelector('[data-rich-text-input]');
        const editorContainer = el.querySelector('[data-rich-text-container]');
        const toolbar = el.querySelector('[data-rich-text-toolbar]');
        const headingSelect = toolbar.querySelector('[data-rte-action="heading"]');

        const editor = new Editor({
            element: editorContainer,
            extensions: [
                StarterKit,
                Placeholder.configure({
                    placeholder: editorContainer.dataset.placeholder || 'Tulis di sini...',
                }),
            ],
            content: hiddenInput.value || '',
            onUpdate: ({ editor }) => {
                hiddenInput.value = editor.getHTML();
            },
            onTransaction: ({ editor }) => updateToolbarState(editor, toolbar, headingSelect),
        });

        // Tombol format teks
        toolbar.querySelectorAll('.rte-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.rteAction;
                const chain = editor.chain().focus();

                switch (action) {
                    case 'bold': chain.toggleBold().run(); break;
                    case 'italic': chain.toggleItalic().run(); break;
                    case 'underline': chain.toggleUnderline().run(); break;
                    case 'strike': chain.toggleStrike().run(); break;
                    case 'bulletList': chain.toggleBulletList().run(); break;
                    case 'orderedList': chain.toggleOrderedList().run(); break;
                    case 'blockquote': chain.toggleBlockquote().run(); break;
                    case 'undo': chain.undo().run(); break;
                    case 'redo': chain.redo().run(); break;
                }
            });
        });

        // Dropdown heading
        headingSelect.addEventListener('change', () => {
            const val = headingSelect.value;
            const chain = editor.chain().focus();

            if (val === 'paragraph') chain.setParagraph().run();
            else chain.toggleHeading({ level: Number(val) }).run();
        });

        updateToolbarState(editor, toolbar, headingSelect);
        editors.set(el, editor);
    });
}

// Nyalain/matiin tombol sesuai posisi kursor saat ini
function updateToolbarState(editor, toolbar, headingSelect) {
    const map = {
        bold: 'bold',
        italic: 'italic',
        underline: 'underline',
        strike: 'strike',
        bulletList: 'bulletList',
        orderedList: 'orderedList',
        blockquote: 'blockquote',
    };

    toolbar.querySelectorAll('.rte-btn[data-rte-action]').forEach((btn) => {
        const mark = map[btn.dataset.rteAction];
        if (!mark) return;
        btn.classList.toggle('is-active', editor.isActive(mark));
    });

    if (editor.isActive('heading', { level: 1 })) headingSelect.value = '1';
    else if (editor.isActive('heading', { level: 2 })) headingSelect.value = '2';
    else if (editor.isActive('heading', { level: 3 })) headingSelect.value = '3';
    else headingSelect.value = 'paragraph';
}

function setRichTextContent(name, html) {
    const input = document.querySelector(`[data-rich-text-input][name="${name}"]`);
    const el = input?.closest('[data-rich-text-editor]');
    const editor = el && editors.get(el);
    if (editor) editor.commands.setContent(html || '');
}

document.addEventListener('shown.bs.offcanvas', (e) => {
    initRichTextEditors(e.target);
});

window.initRichTextEditors = initRichTextEditors;
window.setRichTextContent = setRichTextContent;