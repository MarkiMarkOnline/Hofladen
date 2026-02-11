//  POS SYSTEM

//  DOM CACHE
const DOM = {
  searchInput: document.querySelector(".pos-search-input"),
  searchResults: document.querySelector(".pos-search-results"),
  cartItems: document.querySelector(".pos-cart-items"),
  cartTotal: document.getElementById("cartTotal"),
  checkoutTotal: document.getElementById("checkoutTotal"),
  paymentPanel: document.getElementById("paymentPanel"),
  paymentTitle: document.getElementById("paymentTitle"),
  barOptions: document.getElementById("barOptions"),
  cashInput: document.getElementById("cashAmount"),
};

//   STATE
const cart = new Map();

// HELPER

function getCartTotal() {
  let total = 0;
  cart.forEach((item) => {
    total += item.price * item.quantity;
  });
  return total;
}

function formatMoney(value) {
  return value.toFixed(2) + " €";
}

// SEARCH

function debounce(fn, delay = 250) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

DOM.searchInput.addEventListener("input", debounce(handleSearch));

async function handleSearch() {
  const query = DOM.searchInput.value.trim();
  if (!query) {
    DOM.searchResults.style.display = "none";
    return;
  }

  try {
    // ✅ NEU: Router-URL statt direkter Datei
    const res = await fetch(`?action=pos_search&q=${encodeURIComponent(query)}`);
    const data = await res.json();
    renderSearchResults(data);
  } catch (err) {
    console.error("Search error:", err);
  }
}

function renderSearchResults(items) {
  DOM.searchResults.innerHTML = "";

  if (!items.length) {
    DOM.searchResults.style.display = "none";
    return;
  }

  DOM.searchResults.style.display = "block";

  items.forEach((item) => {
    const li = document.createElement("li");
    li.textContent = `${item.artikelbezeichnung} - ${Number(item.preis).toFixed(2)} €`;
    li.addEventListener("click", () => {
      addToCart(item);
      DOM.searchInput.value = "";
      DOM.searchResults.style.display = "none";
      DOM.searchInput.focus();
    });
    DOM.searchResults.appendChild(li);
  });
}

// hide dropdown on outside click
document.addEventListener("click", (e) => {
  if (
    !DOM.searchInput.contains(e.target) &&
    !DOM.searchResults.contains(e.target)
  ) {
    DOM.searchResults.style.display = "none";
  }
});

// CART

function addToCart(item) {
  const id = item.id_artikel;
  if (cart.has(id)) {
    cart.get(id).quantity++;
  } else {
    cart.set(id, {
      id,
      name: item.artikelbezeichnung,
      price: Number(item.preis),
      quantity: 1,
    });
  }
  renderCart();
}

function increaseQty(id) {
  const item = cart.get(id);
  item.quantity++;
  renderCart();
}

function decreaseQty(id) {
  const item = cart.get(id);
  if (item.quantity > 1) {
    item.quantity--;
  } else {
    cart.delete(id);
  }
  renderCart();
}

function removeFromCart(id) {
  cart.delete(id);
  renderCart();
}

function renderCart() {
  DOM.cartItems.innerHTML = "";
  const total = getCartTotal();

  cart.forEach((item) => {
    const row = document.createElement("div");
    row.className = "pos-item";
    const subtotal = item.price * item.quantity;

    row.innerHTML = `
      <span>${item.name}</span>
      <span style="display:flex; align-items:center; gap:6px;">
        <button class="qty-btn minus">-</button>
        <strong>${item.quantity}</strong>
        <button class="qty-btn plus">+</button>
        <span style="width:90px; text-align:right;">
          ${formatMoney(subtotal)}
        </span>
        <button class="remove-item-btn">✕</button>
      </span>
    `;

    row.querySelector(".plus").onclick = () => increaseQty(item.id);
    row.querySelector(".minus").onclick = () => decreaseQty(item.id);
    row.querySelector(".remove-item-btn").onclick = () =>
      removeFromCart(item.id);

    DOM.cartItems.appendChild(row);
  });

  DOM.cartTotal.textContent = formatMoney(total);
  DOM.checkoutTotal.textContent = formatMoney(total);
}

// PAYMENT

function openPayment(type) {
  if (cart.size === 0) {
    alert("Warenkorb ist leer!");
    return;
  }

  DOM.paymentTitle.textContent =
    type === "bar" ? "Barzahlung" : "Kartenzahlung";
  DOM.barOptions.style.display = type === "bar" ? "block" : "none";
  DOM.paymentPanel.classList.add("active");

  if (type === "bar") {
    DOM.cashInput.value = "";
    DOM.cashInput.focus();
  }
}

function closePayment() {
  DOM.paymentPanel.classList.remove("active");
}

function pay(option) {
  const total = getCartTotal();

  if (DOM.barOptions.style.display === "block") {
    const given = parseFloat(DOM.cashInput.value.replace(",", "."));

    if (isNaN(given)) {
      alert("Bitte Betrag eingeben!");
      return;
    }

    if (given < total) {
      alert("Nicht genügend Betrag!");
      return;
    }

    const change = given - total;

    alert(
      `Bezahlung abgeschlossen (${option})\n\nGesamt: ${formatMoney(total)}\nErhalten: ${formatMoney(given)}\nRückgeld: ${formatMoney(change)}`
    );
  } else {
    alert(`Kartenzahlung erfolgreich!\n\nGesamt: ${formatMoney(total)}`);
  }

  // RESET POS
  cart.clear();
  renderCart();
  DOM.cashInput.value = "";
  closePayment();
}

// NUMPAD

function addNumber(num) {
  DOM.cashInput.value += num;
}
function clearCash() {
  DOM.cashInput.value = "";
}
function removeLast() {
  DOM.cashInput.value = DOM.cashInput.value.slice(0, -1);
}
