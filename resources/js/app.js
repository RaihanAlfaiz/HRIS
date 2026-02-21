import "./bootstrap";

/**
 * Tom Select — Auto-initialize on .select-search elements
 * Usage: Add class "select-search" to any <select> element
 *        Set data-placeholder="..." for placeholder text
 */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".select-search").forEach((el) => {
        if (el.tomselect) return;

        // Check if any option has the 'selected' attribute (e.g. edit form / old() values)
        var hasSelected = el.querySelector("option[selected]") !== null;

        new TomSelect(el, {
            plugins: ["clear_button"],
            allowEmptyOption: false,
            placeholder: el.dataset.placeholder || "Pilih...",
            searchField: ["text"],
            sortField: { field: "$order" },
            dropdownParent: "body",
            items: hasSelected ? undefined : [],
            render: {
                no_results: function () {
                    return '<div class="no-results">Tidak ditemukan</div>';
                },
            },
        });
    });
});
