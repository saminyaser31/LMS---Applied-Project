<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.0/"
        }
    }
</script>
<script type="module">
      import {
        ClassicEditor,
        Essentials,
        Heading,
        Paragraph,
        Bold,
        Italic,
        Strikethrough,
        Subscript,
        Superscript,
        Font,
        List,
        Link,
        Indent,
        IndentBlock,
        BlockQuote,
        Code,
        CodeBlock,
        Alignment,
        TodoList,
        Table,
        MediaEmbed
    } from 'ckeditor5';

    document.querySelectorAll('textarea.ckeditor-classic').forEach(textarea => {
        var textarea_id = textarea.getAttribute('id');
        initializeCkEditor5(textarea_id);
    });

    function initializeCkEditor5(textarea_id) {
        ClassicEditor.create( document.querySelector('#' + textarea_id), {
            plugins: [
                Essentials,
                Heading,
                Paragraph,
                Bold,
                Italic,
                Strikethrough,
                Subscript,
                Superscript,
                Font,
                Link,
                List,
                Indent,
                IndentBlock,
                BlockQuote,
                Code,
                CodeBlock,
                Alignment,
                TodoList,
                Table,
                MediaEmbed
            ],
            // toolbar: [ 'bold', 'italic', 'link', 'outdent', 'indent', 'blockquote' ],
            toolbar: {
                items: [
                    'undo', 'redo',
                    '|',
                    'heading',
                    '|',
                    {
                        label: 'Basic styles',
                        // Note that the "icon" property is not configured and defaults to three dots.
                        withText: true,
                        items: [ 'bold', 'italic', 'strikethrough', 'superscript', 'subscript', 'code' ]
                    },
                    '|',
                    'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                    '|',
                    'insertTable', 'mediaEmbed',
                    '|',
                    'link', 'blockQuote', 'codeBlock',
                    '|',
                    'alignment',
                    '|',
                    {
                        label: 'List',
                        // Note that the "icon" property is not configured and defaults to three dots.
                        withText: true,
                        items: [ 'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent' ]
                    }
                ],
                shouldNotGroupWhenFull: false
            },
            menuBar: {
                isVisible: true
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            },
            fontFamily: {
                options: [
                    "default",
                    "Arial, Helvetica, sans-serif",
                    "Courier New, Courier, monospace",
                    "Georgia, serif",
                    "Lucida Sans Unicode, Lucida Grande, sans-serif",
                    "Tahoma, Geneva, sans-serif",
                    "Times New Roman, Times, serif",
                    "Trebuchet MS, Helvetica, sans-serif",
                    "Verdana, Geneva, sans-serif",
                ],
                supportAllValues: true,
            },
            fontSize: {
                options: [10, 12, 14, "default", 18, 20, 22],
                supportAllValues: true,
            },
            fontColor: {
                colorPicker: {
                    // Use 'hex' format for output instead of 'hsl'.
                    format: 'hex'
                }
            },
            fontBackgroundColor: {
                colorPicker: {
                    format: 'hex', // Use 'hex' format for consistency
                    colors: [
                        '#000000', '#FFFFFF', '#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF', // Common color options
                        'transparent' // Add transparent as an option
                    ]
                }
            },
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: "https://",
                    toggleDownloadable: {
                        mode: "manual",
                        label: "Downloadable",
                        attributes: {
                            download: "file",
                        },
                    },
                },
            }
        })
        .catch( error => {
            console.error( error );
        });
    }
</script>

{{-- <script>
    // Synchronize CKEditor data with textareas before submitting the form
    document.querySelector('form').addEventListener('submit', function(event) {
        // Sync CKEditor data with the original textarea
        document.querySelectorAll('textarea.ckeditor-classic').forEach(textarea => {
            // if (ClassicEditor.instances[textarea.id]) {
            //     textarea.value = ClassicEditor.instances[textarea.id].getData();
            // }
            const editorData = ClassicEditor.instances[textarea.id].getData();
            textarea.value = editorData;
        });
    });
</script> --}}
