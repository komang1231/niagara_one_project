/* =========================================================
   FILE UPLOAD
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
    const uploaders = document.querySelectorAll('[data-file-upload]');

    uploaders.forEach((uploader) => {
        initFileUpload(uploader);
    });
});


function initFileUpload(uploader) {
    const input = uploader.querySelector('[data-file-input]');
    const dropzone = uploader.querySelector('[data-file-dropzone]');
    const emptyState = uploader.querySelector('[data-file-empty]');
    const fileList = uploader.querySelector('[data-file-list]');
    const errorElement = uploader.querySelector('[data-file-error]');

    if (!input || !dropzone) {
        return;
    }

    const maxFiles = Number(
        uploader.dataset.maxFiles || 10
    );

    const maxSize = Number(
        uploader.dataset.maxSize || 20
    );

    const maxTotalSize = Number(
        uploader.dataset.maxTotalSize || 100
    );

    const multiple =
        uploader.dataset.multiple === 'true';

    let selectedFiles = [];

    const maxSizeBytes =
        maxSize * 1024 * 1024;

    const maxTotalSizeBytes =
        maxTotalSize * 1024 * 1024;


    /* =====================================================
       CLICK
       ===================================================== */

    dropzone.addEventListener('click', (event) => {
        if (
            event.target.closest('[data-file-remove]')
        ) {
            return;
        }

        input.click();
    });


    /* =====================================================
       KEYBOARD
       ===================================================== */

    dropzone.addEventListener('keydown', (event) => {
        if (
            event.key === 'Enter' ||
            event.key === ' '
        ) {
            event.preventDefault();

            input.click();
        }
    });


    /* =====================================================
       FILE INPUT
       ===================================================== */

    input.addEventListener('change', () => {
        const files = Array.from(input.files);

        addFiles(files);
    });


    /* =====================================================
       DRAG OVER
       ===================================================== */

    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();

        dropzone.classList.add('is-dragover');
    });


    /* =====================================================
       DRAG ENTER
       ===================================================== */

    dropzone.addEventListener('dragenter', (event) => {
        event.preventDefault();

        dropzone.classList.add('is-dragover');
    });


    /* =====================================================
       DRAG LEAVE
       ===================================================== */

    dropzone.addEventListener('dragleave', (event) => {
        if (
            !dropzone.contains(event.relatedTarget)
        ) {
            dropzone.classList.remove('is-dragover');
        }
    });


    /* =====================================================
       DROP
       ===================================================== */

    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();

        dropzone.classList.remove('is-dragover');

        const files = Array.from(
            event.dataTransfer.files
        );

        addFiles(files);
    });


    /* =====================================================
       ADD FILES
       ===================================================== */

    function addFiles(files) {
        clearError();

        if (!files.length) {
            return;
        }

        /*
         * Single file
         */
        if (!multiple) {
            const file = files[0];

            const validationError =
                validateFile(file);

            if (validationError) {
                showError(validationError);

                resetInput();

                return;
            }

            selectedFiles = [file];

            syncInput();
            renderFiles();

            return;
        }


        /*
         * Multiple files
         */

        const newFiles = [];

        for (const file of files) {
            const validationError =
                validateFile(file);

            if (validationError) {
                showError(validationError);

                continue;
            }

            /*
             * Jangan masukkan file yang sama
             * dua kali.
             */
            const duplicate =
                selectedFiles.some((existingFile) => {
                    return (
                        existingFile.name === file.name &&
                        existingFile.size === file.size &&
                        existingFile.lastModified ===
                            file.lastModified
                    );
                });

            if (!duplicate) {
                newFiles.push(file);
            }
        }

        if (!newFiles.length) {
            syncInput();

            return;
        }


        /*
         * Check jumlah file
         */

        if (
            selectedFiles.length +
                newFiles.length >
            maxFiles
        ) {
            const availableSlots =
                maxFiles -
                selectedFiles.length;

            if (availableSlots <= 0) {
                showError(
                    `Maksimal ${maxFiles} file.`
                );

                syncInput();

                return;
            }

            newFiles.splice(
                availableSlots
            );

            showError(
                `Maksimal ${maxFiles} file.`
            );
        }


        /*
         * Check total size
         */

        const currentTotal =
            getTotalSize(selectedFiles);

        const acceptedFiles = [];

        let totalAfterAdding =
            currentTotal;

        for (const file of newFiles) {
            if (
                totalAfterAdding + file.size >
                maxTotalSizeBytes
            ) {
                showError(
                    `Total ukuran file maksimal ${maxTotalSize} MB.`
                );

                continue;
            }

            acceptedFiles.push(file);

            totalAfterAdding += file.size;
        }


        selectedFiles = [
            ...selectedFiles,
            ...acceptedFiles,
        ];

        syncInput();
        renderFiles();
    }


    /* =====================================================
       VALIDATE FILE
       ===================================================== */

    function validateFile(file) {
        if (file.size > maxSizeBytes) {
            return `${file.name} melebihi batas ${maxSize} MB.`;
        }

        return null;
    }


    /* =====================================================
       RENDER
       ===================================================== */

    function renderFiles() {
        fileList.innerHTML = '';

        if (!selectedFiles.length) {
            fileList.classList.add('d-none');

            emptyState.classList.remove('d-none');

            return;
        }

        emptyState.classList.add('d-none');

        fileList.classList.remove('d-none');

        selectedFiles.forEach((file, index) => {
            const item =
                document.createElement('div');

            item.className =
                'file-upload__item';

            item.innerHTML = `
                <div class="file-upload__file-icon">
                    <i class="bi bi-file-earmark"></i>
                </div>

                <div class="file-upload__file-info">
                    <div
                        class="file-upload__file-name"
                        title="${escapeHtml(file.name)}"
                    >
                        ${escapeHtml(file.name)}
                    </div>

                    <div class="file-upload__file-size">
                        ${formatFileSize(file.size)}
                    </div>
                </div>

                <button
                    type="button"
                    class="file-upload__remove"
                    data-file-remove="${index}"
                    aria-label="Hapus ${escapeHtml(file.name)}"
                >
                    <i class="bi bi-x"></i>
                </button>
            `;

            fileList.appendChild(item);
        });
    }


    /* =====================================================
       REMOVE
       ===================================================== */

    fileList.addEventListener('click', (event) => {
        const button =
            event.target.closest(
                '[data-file-remove]'
            );

        if (!button) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const index =
            Number(
                button.dataset.fileRemove
            );

        selectedFiles.splice(index, 1);

        clearError();

        syncInput();
        renderFiles();
    });


    /* =====================================================
       SYNC INPUT
       ===================================================== */

    function syncInput() {
        const dataTransfer =
            new DataTransfer();

        selectedFiles.forEach((file) => {
            dataTransfer.items.add(file);
        });

        input.files =
            dataTransfer.files;
    }


    /* =====================================================
       RESET INPUT
       ===================================================== */

    function resetInput() {
        input.value = '';

        selectedFiles = [];

        renderFiles();
    }


    /* =====================================================
       TOTAL SIZE
       ===================================================== */

    function getTotalSize(files) {
        return files.reduce(
            (total, file) => {
                return total + file.size;
            },
            0
        );
    }


    /* =====================================================
       ERROR
       ===================================================== */

    function showError(message) {
        errorElement.textContent = message;

        errorElement.classList.remove(
            'd-none'
        );

        uploader.classList.add(
            'is-invalid'
        );
    }


    function clearError() {
        errorElement.textContent = '';

        errorElement.classList.add(
            'd-none'
        );

        uploader.classList.remove(
            'is-invalid'
        );
    }


    /* =====================================================
       FILE SIZE
       ===================================================== */

    function formatFileSize(bytes) {
        if (bytes === 0) {
            return '0 KB';
        }

        const units = [
            'Bytes',
            'KB',
            'MB',
            'GB',
        ];

        const index =
            Math.floor(
                Math.log(bytes) /
                    Math.log(1024)
            );

        const size =
            bytes /
            Math.pow(1024, index);

        return (
            `${size.toFixed(
                index === 0 ? 0 : 1
            )} ${units[index]}`
        );
    }


    /* =====================================================
       ESCAPE HTML
       ===================================================== */

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
}