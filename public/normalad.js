/* =================================
   NORMAL ADMIN JS (FINAL)
   - Pagination
   - Search
   - Empty row stabil
   - Remember page after edit
   - Remember collapse
   - Maxlength counter
   - Auto logout
================================= */

/* =================================
   PAGINATION CONFIG
================================= */
const rowsPerPageMap = {
    agenda: 4,
};

/* =================================
   GLOBAL PAGINATION SYSTEM
================================= */
function initTablePagination(tableName) {
    const tbody = document.getElementById(tableName + "Tbody");
    const searchInput = document.getElementById(tableName + "Search");
    const prevBtn = document.getElementById(tableName + "PrevBtn");
    const nextBtn = document.getElementById(tableName + "NextBtn");

    if (!tbody) return;

    const allRows = Array.from(tbody.querySelectorAll("tr"));
    const allDataRows = allRows.filter(row => {
        const firstCell = row.querySelector("td");
        return firstCell && !firstCell.hasAttribute("colspan");
    });

    let filteredRows = [...allDataRows];
    let currentPage = 0;

    window.__paginationState = window.__paginationState || {};
    const rowsPerPage = rowsPerPageMap[tableName] || 4;

    const emptyRowClass = tableName + "-empty-row";
    const colCount = allDataRows[0]
        ? allDataRows[0].querySelectorAll("td").length
        : 6;

    let emptyRows = tbody.querySelectorAll("." + emptyRowClass);
    if (emptyRows.length < rowsPerPage) {
        for (let i = emptyRows.length; i < rowsPerPage; i++) {
            const tr = document.createElement("tr");
            tr.className = emptyRowClass;
            tr.innerHTML = `<td colspan="${colCount}" style="height:60px;border:none;"></td>`;
            tbody.appendChild(tr);
        }
    }

    emptyRows = tbody.querySelectorAll("." + emptyRowClass);

    function showPage(page) {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

        if (page < 0) page = 0;
        if (page >= totalPages && totalPages > 0) page = totalPages - 1;

        currentPage = page;
        window.__paginationState[tableName] = currentPage;

        allDataRows.forEach(r => r.style.display = "none");
        emptyRows.forEach(r => r.style.display = "none");

        const start = currentPage * rowsPerPage;
        const visible = filteredRows.slice(start, start + rowsPerPage);

        visible.forEach(r => r.style.display = "table-row");

        emptyRows
            .slice(0, rowsPerPage - visible.length)
            .forEach(r => r.style.display = "table-row");

        if (prevBtn && nextBtn) {
            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = currentPage >= totalPages - 1 || totalPages === 0;
        }
    }

    function performSearch() {
        const term = searchInput ? searchInput.value.toLowerCase().trim() : "";
        filteredRows = term === ""
            ? [...allDataRows]
            : allDataRows.filter(row =>
                (row.getAttribute("data-search") || "").includes(term)
            );

        showPage(0);
    }

    if (searchInput) searchInput.addEventListener("input", performSearch);
    if (prevBtn) prevBtn.addEventListener("click", () => showPage(currentPage - 1));
    if (nextBtn) nextBtn.addEventListener("click", () => showPage(currentPage + 1));

    /* 🔥 RESTORE PAGE AFTER EDIT */
    const savedPage = sessionStorage.getItem(tableName + "_lastPage");
    if (savedPage !== null) {
        showPage(parseInt(savedPage));
        sessionStorage.removeItem(tableName + "_lastPage");
    } else {
        showPage(0);
    }
}

/* =================================
   INIT AGENDA
================================= */
document.addEventListener("DOMContentLoaded", () => {
    initTablePagination("agenda");

    const clearBtn = document.getElementById("agendaClearSearch");
    const searchInput = document.getElementById("agendaSearch");

    if (clearBtn && searchInput) {
        clearBtn.addEventListener("click", () => {
            searchInput.value = "";
            initTablePagination("agenda");
        });
    }
});

/* =================================
   REMEMBER PAGE AFTER EDIT
================================= */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            if (
                window.__paginationState &&
                window.__paginationState.agenda !== undefined
            ) {
                sessionStorage.setItem(
                    "agenda_lastPage",
                    window.__paginationState.agenda
                );
            }
        });
    });
});

/* =================================
   REMEMBER COLLAPSE (AGENDA)
================================= */
document.addEventListener("DOMContentLoaded", () => {
    const STORAGE_KEY = "open-section";
    const agendaCollapse = document.getElementById("agendaTableBody");

    if (!agendaCollapse) return;

    // RESTORE
    if (localStorage.getItem(STORAGE_KEY) === "agendaTableBody") {
        bootstrap.Collapse.getOrCreateInstance(agendaCollapse, {
            toggle: false
        }).show();
    }

    // SAVE
    agendaCollapse.addEventListener("show.bs.collapse", () => {
        localStorage.setItem(STORAGE_KEY, "agendaTableBody");
    });

    agendaCollapse.addEventListener("hidden.bs.collapse", () => {
        localStorage.removeItem(STORAGE_KEY);
    });
});

/* =================================
   MAXLENGTH COUNTER
================================= */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("input[maxlength], textarea[maxlength]").forEach(input => {
        const max = parseInt(input.getAttribute("maxlength"));
        if (!max) return;

        const counter = document.createElement("small");
        counter.className = "text-muted d-block counter-tight";
        input.after(counter);

        const update = () => {
            if (input.value.length > max) {
                input.value = input.value.slice(0, max);
            }
            counter.innerText = `${input.value.length}/${max} karakter`;
        };

        input.addEventListener("input", update);
        update();
    });
});

/* =================================
   AUTO LOGOUT
================================= */
const AUTO_LOGOUT_INTERVAL = 1 * 60 * 1000;
let autoLogoutTimer;

function resetAutoLogoutTimer() {
    const form = document.getElementById("auto-logout-form");
    if (!form) return;

    clearTimeout(autoLogoutTimer);
    autoLogoutTimer = setTimeout(() => form.submit(), AUTO_LOGOUT_INTERVAL);
}

["mousemove", "keydown", "click", "scroll", "touchstart"]
    .forEach(e => document.addEventListener(e, resetAutoLogoutTimer, true));

resetAutoLogoutTimer();
