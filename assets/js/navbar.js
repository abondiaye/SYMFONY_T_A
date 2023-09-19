const indicator = document.querySelector(".nav-indicator");
const items = document.querySelectorAll(".nav-item");

function handleIndicator(el) {
  // Boucler sur tous items -> retirer la classe "is-active"
  items.forEach((item) => {
    item.classList.remove("is-active");
    item.removeAttribute("style");
  });

  const elementColor = el.dataset.activeColor;
  const target = el.dataset.target;

  console.log(target);

  // Styliser l'indicateur
  indicator.style.width = `${el.offsetWidth}px`;
  indicator.style.backgroundColor = elementColor;
  indicator.style.left = `${el.offsetLeft}px`;

  // Ajout la classe is-active
  el.classList.add("is-active");
  el.style.color = elementColor;
}

items.forEach((item, index) => {
  item.addEventListener("click", (e) => {
    handleIndicator(e.target);
  });
  item.classList.contains("is-active") && handleIndicator(item);
});
