/**
    Author: Darwin Casanova
    Date: March 9, 2025
    Time: 8:30 PM
    Description: Datatable utility class for managing pagination, sorting, and searching.
*/

import _ from 'underscore';
import { computed, ref } from 'vue';

const ASC = 1;
const DESC = -1;

const rowsPerPage = ref(10);
const page = ref(1)
const sortField = ref(null);
const sortOrder = ref(ASC);
const searchForm = ref({});

const params = computed(() => ({
    count: rowsPerPage.value,
    page: page.value,
    sortField: sortField.value,
    sortOrder: sortOrder.value === ASC ? 'asc' : 'desc',
    searchForm: searchForm.value,
}));

class DatatableUtil {
    onSort(field, order) {
        sortField.value = field;
        sortOrder.value = order;
    }

    onPage(pageNumber, rows) {
        rowsPerPage.value = rows;
        page.value = pageNumber + 1;
    }

    getParams() {
        return params.value;
    }

    resetTable() {
        Object.keys(searchForm.value).forEach(key => {
            searchForm.value[key] = null;
        });
    }

    setSearchForm(search) {
        searchForm.value = search;
    }

    getSortOrder() {
        return sortOrder.value;
    }

    setSortField(field) {
        sortField.value = field;
    }

    getRowsPerPage() {
        return rowsPerPage.value;
    }
    
    // ✅ New method to reset everything when switching pages
    resetAll() {
        rowsPerPage.value = 10;
        page.value = 1;
        sortField.value = null;
        sortOrder.value = ASC;
        this.resetTable(); // clears search form
    }
}
export default new DatatableUtil();
