import {FilterMatchMode} from 'primevue/api';
import {unserialize} from 'php-serialize';
import {ref, onMounted} from "vue";
import {usePage} from '@inertiajs/vue3';

export default function sessionScript() {
    const filters = ref();
    const sessions = ref([]);
    const page = ref(0);
    const loading = ref(true);
    const totalRecords = ref(0);
    const rowOptions = [5, 10, 20, 50];
    const rows = ref(rowOptions[1]);
    const {props} = usePage();
    const menu = props.menu

    const columns = [
        {
            field: "ip_address",
            header: "IP Address",
            bodyStyle: "text-align: center;",
            style: "width: 15%;",
        },
        {
            field: "name",
            header: "Name",
            bodyStyle: "text-align: left;",
            style: "width: 15%;",
        },
        {
            field: "email",
            header: "Email",
            bodyStyle: "text-align: left;",
            style: "width: 15%;",
        },
        {
            field: "payload",
            header: "Payload",
            bodyStyle: "text-align: left;",
            style: "width: 35%;",
        },
        {
            field: "last_activity",
            header: "Last Activity",
            bodyStyle: "text-align: center;",
            style: "width: 15%;",
        },
        {
            field: "actions",
            header: "Action",
            bodyStyle: "text-align: center;",
            style: "width: 5%;",
        },
    ];


    const decodeAndUnserializePayload = (payload) => {
        try {
            const decoded = atob(payload);// Decode Base64
            const unserialized = unserialize(decoded); // Unserialize
            return JSON.stringify(unserialized, null, 2);
        } catch (error) {
            console.error('Error decoding or unserializing payload:', error);
            return 'Invalid data';
        }
    };

    const getDecodedPayload = (payload) => {
        return decodeAndUnserializePayload(payload);
    };

    const loadData = async ({page, rows, sortField, sortOrder, filters}) => {
        try {
            loading.value = true;
            const response = await axios.get('session/get-datatable', {
                params: {
                    page: page + 1,
                    rows: rows,
                    sortField: sortField,
                    sortOrder: sortOrder === 1 ? 'asc' : 'desc',
                    filters: filters,
                },
            });

            sessions.value = response.data.sessions;
            totalRecords.value = response.data.total;
            loading.value = false;
        } catch (error) {
            loading.value = false;
            console.error('Error fetching session data:', error);
        }
    };

    const initFilters = () => {
        filters.value = {
            ip_address: {value: null, matchMode: FilterMatchMode.CONTAINS},  // Global filter
            'name': {value: null, matchMode: FilterMatchMode.CONTAINS},
            'email': {value: null, matchMode: FilterMatchMode.CONTAINS},
            last_activity: {value: null, matchMode: FilterMatchMode.BETWEEN},
        };
    };

    const formatDate = (value) => {
        const dateObj = new Date(value.replace(' ', 'T'));
        return dateObj.toLocaleDateString('id', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    };

    const clearFilters = () => {
        initFilters()
        loadData({page: page.value, rows: rows.value, sortField: null, sortOrder: null, filters: filters.value});
    };

    function onPageChange(event) {
        page.value = event.page;
        rows.value = event.rows;
        loadData({
            page: page.value,
            rows: rows.value,
            sortField: event.sortField,
            sortOrder: event.sortOrder,
            filters: event.filters
        });
    }

    const onSort = (event) => {
        page.value = 0;
        rows.value = event.rows;
        loadData({
            page: page.value,
            rows: rows.value,
            sortField: event.sortField,
            sortOrder: event.sortOrder,
            filters: filters.value,
        });
    };

    function onFilterApply(field, value) {
        if (filters.value[field]) {
            filters.value[field].value = value;
        } else {
            console.error(`Filter "${field}" tidak ditemukan.`);
        }
        loadData({page: page.value, rows: rows.value, sortField: null, sortOrder: null, filters: filters.value});
    }

    function onFilterClear(field) {
        if (filters.value[field]) {
            filters.value[field].value = null; // Update nilai value
        } else {
            console.error(`Filter "${field}" tidak ditemukan.`);
        }
        loadData({page: page.value, rows: rows.value, sortField: null, sortOrder: null, filters: filters.value});
    }



    return {
        columns,
        sessions,
        totalRecords,
        filters,
        page,
        rows,
        rowOptions,
        loading,
        loadData,
        initFilters,
        clearFilters,
        onPageChange,
        onSort,
        onFilterApply,
        onFilterClear,
        onMounted,
        formatDate,
        getDecodedPayload,
        menu
    };
}
