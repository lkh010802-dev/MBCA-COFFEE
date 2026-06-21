document.addEventListener("DOMContentLoaded", () => {
    const seasonBg = document.querySelector(".season-bg");
    const seasonSlides = document.querySelectorAll(".season-slide");

    function setSeasonBackground(index) {
        const activeSlide = seasonSlides[index];
        if (!seasonBg || !activeSlide) return;

        seasonBg.style.backgroundImage = `url("${activeSlide.dataset.bg}")`;
    }

    if (!document.querySelector(".season-swiper")) return;

    new Swiper(".season-swiper", {
        loop: true,
        speed: 800,
        grabCursor: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        pagination: {
            el: ".season-pagination",
            clickable: true
        },
        on: {
            init(swiper) {
                setSeasonBackground(swiper.realIndex);
            },
            slideChange(swiper) {
                setSeasonBackground(swiper.realIndex);
            }
        }
    });
});