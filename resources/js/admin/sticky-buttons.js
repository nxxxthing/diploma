const el = document.querySelector(".bottom-line-sticky")
if (el) {
    let observer = new IntersectionObserver(
        ([e]) => e.target.classList.toggle("is-pinned", e.intersectionRatio < 1),
        { threshold: [1] }
    );

    observer.observe(el);
}
