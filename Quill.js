var toolbarOptions = [
  [{ header: "2" }],
  ["bold", "italic", "underline"],
  ["link", "image"],
  ["code"],
];
let quill = new Quill("#editor-container", {
  theme: "snow",
  modules: {
    toolbar: toolbarOptions,
  },
});

let text;

try {
  quill.root.innerHTML = htmlFromDB;
} catch (e) {}
quill.on("text-change", function () {
  text = quill.root.innerHTML;
  document.getElementById("articleContent").value = text;
});
