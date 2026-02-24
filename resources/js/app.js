import "./bootstrap";

/**
 * Tom Select — Auto-initialize on .select-search elements
 * Usage: Add class "select-search" to any <select> element
 *        Set data-placeholder="..." for placeholder text
 *        Add class "select-filter" for auto-submit on change
 */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".select-search").forEach((el) => {
        if (el.tomselect) return;

        const currentValue = el.value;
        const isFilter = el.classList.contains("select-filter");

        const instance = new TomSelect(el, {
            plugins: ["clear_button"],
            allowEmptyOption: true,
            placeholder: el.dataset.placeholder || "Pilih...",
            searchField: ["text"],
            sortField: { field: "$order" },
            dropdownParent: "body",
            items: currentValue ? [currentValue] : [],
            controlInput: isFilter ? undefined : undefined,
            render: {
                no_results: function () {
                    return '<div class="no-results">Tidak ditemukan</div>';
                },
            },
            onChange: function () {
                // Auto-submit if this select is a filter
                if (isFilter) {
                    const form = el.closest("form");
                    if (form) form.submit();
                }
            },
        });
    });

    // Debounced auto-search for text input
    const searchInput = document.getElementById("search");
    const filterForm = document.getElementById("filter-form");

    if (searchInput && filterForm) {
        let debounceTimer;
        searchInput.addEventListener("input", () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    }
});
