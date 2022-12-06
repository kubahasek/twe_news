const params = new URLSearchParams(window.location.search);
if (params.has("toast")) {
  let color = params.get("color");
  let message = params.get("message");
  let redirect = params.get("redirect");
  displayToast(color, message);
  window.history.replaceState({}, "", redirect);
}

function displayToast(color, message) {
  Toastify({
    text: message,
    duration: 3000,
    gravity: "bottom", // `top` or `bottom`
    position: "right", // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    style: {
      background: color,
      borderRadius: "16px",
    },
  }).showToast();
}