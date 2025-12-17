const addTransferCode = (code) => {
const offcanvas = document.querySelector("#getTransferCode");
  const p = document.createElement("p");
  p.textContent = "Your transfer code is: " + code;
  offcanvas.appendChild(p);
};

const transferCodeForm = document.getElementById('getTransferCode');
transferCodeForm.addEventListener('submit', (event) => {

  event.preventDefault();

  const formData = new FormData(transferCodeForm);

  const data = Object.fromEntries(formData.entries());

  const user = data.user;
  const api_key = data.api_key;
  const amount = data.amount;


  fetch('https://www.yrgopelag.se/centralbank/withdraw', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user, api_key, amount })},
    )

    .then((response) => response.json())
    .then((data) => {
      console.log(data)
      if (data.error) {
        console.error(data.error);
      } else {
        addTransferCode(data.transferCode);
      }
    });
});