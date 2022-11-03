var quill = new Quill('#editor', {
    theme: 'snow'
});

let text;

quill.on("text-change", function() {
    text = quill.root.innerHTML;
    document.getElementById("articleContent").value = text;
});