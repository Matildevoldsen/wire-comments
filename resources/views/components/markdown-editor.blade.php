@props(['disabled' => false, 'options' => [], 'wireModel'])

@php
    $editorId = uniqid('markdown-editor-');
@endphp

<div x-data="{ preview: false, content: @entangle($attributes->wire('model')) }"
     class="wysiwyg-editor border rounded-lg shadow-sm dark:border-0 p-4 bg-white dark:bg-gray-900"
     id="editor-container-{{ $editorId }}">
    <div class="toolbar flex space-x-2 mb-4 {{ $options ? 'dark:border-0 border-b' : '' }} pb-2">
        @foreach ($options as $option)
            <button type="button"
                    class="px-2 py-1 hover:bg-gray-200 dark:border-0 dark:hover:bg-gray-800 dark:text-gray-200 text-gray-700 rounded focus:outline-none"
                    id="btn-{{ $option }}-{{ $editorId }}"
                    onclick="applyMarkdownOption('{{ $option }}', '{{ $editorId }}')">
                @if($option === 'b')
                    <b>B</b>
                @elseif($option === 'i')
                    <i>I</i>
                @elseif($option === 's')
                    <s>S</s>
                @elseif($option === 'h1')
                    <span class="font-bold">H1</span>
                @elseif($option === 'h2')
                    <span class="font-bold">H2</span>
                @elseif($option === 'ul')
                    <span>&bull;</span>
                @elseif($option === 'ol')
                    <span>1.</span>
                @elseif($option === 'code')
                    <span>< /></span>
                @else
                    {{ strtoupper($option) }}
                @endif
            </button>
        @endforeach
    </div>
    <template x-if="!preview">
        <textarea
            {{ $disabled ? 'disabled' : '' }}
            wire:model="{{ $attributes->wire('model')->value() }}"
            {!! $attributes->merge(['class' => 'bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 border-none !ring-0 !outline-none rounded-md shadow-sm w-full h-64 p-3']) !!}
            id="{{ $editorId }}"
        ></textarea>
    </template>
    <template x-if="preview">
        <div class="dark:bg-gray-900 dark:text-gray-100 border-none !ring-0 !outline-none rounded-md shadow-sm w-full h-64 p-3" x-html="markdownToHtml(content)"></div>
    </template>
    <button class="dark:text-gray-200" type="button" x-show="!preview" x-on:click="preview = !preview">
        preview
    </button>
    <button class="dark:text-gray-200" type="button" x-show="preview" x-on:click="preview = !preview">
        edit
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/markdown-it/dist/markdown-it.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const md = window.markdownit();

        window.applyMarkdownOption = function (option, editorId) {
            const textarea = document.getElementById(editorId);

            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            let markdownText = '';
            let cursorPosition = start;
            let isActive = false;

            switch (option) {
                case 'b':
                    isActive = selectedText.startsWith('**') && selectedText.endsWith('**');
                    markdownText = isActive ? selectedText.slice(2, -2) : `**${selectedText}**`;
                    cursorPosition = isActive ? start : start + 2;
                    break;
                case 'i':
                    isActive = selectedText.startsWith('*') && selectedText.endsWith('*');
                    markdownText = isActive ? selectedText.slice(1, -1) : `*${selectedText}*`;
                    cursorPosition = isActive ? start : start + 1;
                    break;
                case 's':
                    isActive = selectedText.startsWith('~~') && selectedText.endsWith('~~');
                    markdownText = isActive ? selectedText.slice(2, -2) : `~~${selectedText}~~`;
                    cursorPosition = isActive ? start : start + 2;
                    break;
                case 'h1':
                    isActive = selectedText.startsWith('# ');
                    markdownText = isActive ? selectedText.slice(2) : `# ${selectedText}`;
                    cursorPosition = isActive ? start : start + 2;
                    break;
                case 'h2':
                    isActive = selectedText.startsWith('## ');
                    markdownText = isActive ? selectedText.slice(3) : `## ${selectedText}`;
                    cursorPosition = isActive ? start : start + 3;
                    break;
                case 'ul':
                    isActive = selectedText.startsWith('- ');
                    markdownText = isActive ? selectedText.slice(2) : `- ${selectedText}`;
                    cursorPosition = isActive ? start : start + 2;
                    break;
                case 'ol':
                    isActive = selectedText.match(/^\d+\.\s/);
                    markdownText = isActive ? selectedText.replace(/^\d+\.\s/, '') : `1. ${selectedText}`;
                    cursorPosition = isActive ? start : start + 3;
                    break;
                case 'code':
                    isActive = selectedText.startsWith('```') && selectedText.endsWith('```');
                    markdownText = isActive ? selectedText.slice(3, -3) : `\`\`\`\n${selectedText}\n\`\`\``;
                    cursorPosition = isActive ? start : start + 4;
                    break;
                default:
                    markdownText = selectedText;
            }

            textarea.setRangeText(markdownText, start, end, 'end');
            textarea.selectionStart = textarea.selectionEnd = cursorPosition;
            textarea.focus();
            textarea.dispatchEvent(new Event('input'));

            // Update button highlight
            updateButtonHighlight(editorId);
        };

        const updateButtonHighlight = (editorId) => {
            const textarea = document.getElementById(editorId);
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;

            const beforeCursor = text.substring(0, start);
            const afterCursor = text.substring(end);
            const currentLine = beforeCursor.split('\n').pop() + afterCursor.split('\n')[0];

            const isBetween = (start, end) => {
                return textarea.selectionStart > start && textarea.selectionEnd < end;
            };

            const toggleHighlight = (condition, buttonId) => {
                const button = document.getElementById(buttonId);
                if (button) {
                    if (condition) {
                        button.classList.add('bg-blue-200');
                    } else {
                        button.classList.remove('bg-blue-200');
                    }
                }
            };

            const isBold = currentLine.includes('**') && isBetween(currentLine.indexOf('**'), currentLine.lastIndexOf('**') + 2);
            const isItalic = currentLine.includes('*') && !isBold && isBetween(currentLine.indexOf('*'), currentLine.lastIndexOf('*') + 1);
            const isStrikethrough = currentLine.includes('~~') && isBetween(currentLine.indexOf('~~'), currentLine.lastIndexOf('~~') + 2);
            const isCode = text.includes('```') && isBetween(text.indexOf('```'), text.lastIndexOf('```') + 3);

            toggleHighlight(isBold, `btn-b-${editorId}`);
            toggleHighlight(isItalic, `btn-i-${editorId}`);
            toggleHighlight(isStrikethrough, `btn-s-${editorId}`);
            toggleHighlight(currentLine.startsWith('# '), `btn-h1-${editorId}`);
            toggleHighlight(currentLine.startsWith('## '), `btn-h2-${editorId}`);
            toggleHighlight(currentLine.startsWith('- '), `btn-ul-${editorId}`);
            toggleHighlight(currentLine.match(/^\d+\.\s/), `btn-ol-${editorId}`);
            toggleHighlight(isCode, `btn-code-${editorId}`);
        };

        document.querySelectorAll('.wysiwyg-editor').forEach(editor => {
            const editorId = editor.querySelector('textarea').id;
            const textarea = document.getElementById(editorId);

            textarea.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    const textBefore = textarea.value.substring(0, start);
                    const currentLine = textBefore.split('\n').pop();

                    let newText = '\n';
                    if (currentLine.startsWith('- ')) {
                        newText += '- ';
                    } else if (currentLine.match(/^\d+\.\s/)) {
                        const currentNumber = parseInt(currentLine.match(/^\d+/)[0], 10);
                        newText += (currentNumber + 1) + '. ';
                    } else {
                        return;
                    }

                    e.preventDefault();
                    textarea.setRangeText(newText, start, end, 'end');
                    textarea.selectionStart = textarea.selectionEnd = start + newText.length;
                    textarea.dispatchEvent(new Event('input'));
                }
            });

            textarea.addEventListener('keyup', function () {
                updateButtonHighlight(editorId);
            });

            textarea.addEventListener('mouseup', function () {
                updateButtonHighlight(editorId);
            });

            // Initial highlight update
            updateButtonHighlight(editorId);
        });

        window.markdownToHtml = (content) => {
            return md.render(content);
        };
    });
</script>
