const searchInput = document.querySelector("#eventSearchInput");
const searchButton = document.querySelector("#eventSearchButton");
const eventCards = [...document.querySelectorAll(".event-card")];
const emptyMessage = document.querySelector("#eventEmpty");

function filterEvents() {
    const keyword = searchInput.value.trim().toLowerCase();
    let visibleCount = 0;

    eventCards.forEach((card) => {
        const title = card.dataset.title.toLowerCase();
        const description = card.dataset.description.toLowerCase();
        const isVisible = title.includes(keyword) || description.includes(keyword);

        card.style.display = isVisible ? "" : "none";
        if (isVisible) visibleCount += 1;
    });

    emptyMessage.classList.toggle("is-show", visibleCount === 0);
}

searchButton?.addEventListener("click", filterEvents);

searchInput?.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
        event.preventDefault();
        filterEvents();
    }
});