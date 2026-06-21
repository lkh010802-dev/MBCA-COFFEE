const modal = document.querySelector(".menu-modal");
const closeButton = document.querySelector(".modal-close");

document.querySelectorAll(".menu-item").forEach((item) => {
    item.addEventListener("click", () => {
        modal.classList.add("is-open");
        modal.setAttribute("aria-hidden", "false");

        document.querySelector(".modal-image").src = item.dataset.image;
        document.querySelector(".modal-image").alt = item.dataset.name;
        document.querySelector(".modal-name").textContent = item.dataset.name;
        document.querySelector(".modal-price").textContent = item.dataset.price;
        document.querySelector(".modal-desc").textContent = item.dataset.desc;
        document.querySelector(".modal-nutrition").textContent = item.dataset.nutrition;
    });
});

closeButton?.addEventListener("click", () => {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
});