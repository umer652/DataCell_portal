function setActiveSidebar(route) {
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.classList.remove('active');
    });

    let activeLink = document.querySelector(`.sidebar a[data-route="${route}"]`);

    if (activeLink) {
        activeLink.classList.add('active');
    }
}

// global banana zaroori hai
window.setActiveSidebar = setActiveSidebar;