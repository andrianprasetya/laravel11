import {FilterMatchMode} from 'primevue/api';
import {useConfirm} from "primevue/useconfirm";
import {useToast} from 'primevue/usetoast';
import {ref, onMounted, reactive} from "vue";
import {usePage} from '@inertiajs/vue3';

export default function roleScript() {
    const filters = ref();
    const roles = ref([]);
    const page = ref(0);
    const loading = ref(true);
    const totalRecords = ref(0);
    const rowOptions = [5, 10, 20, 50];
    const rows = ref(rowOptions[1]);
    const {props} = usePage();
    const confirm = useConfirm();
    const toast = useToast();
    const statuses = reactive([
        {label: 'In-active', value: 0},
        {label: 'Active', value: 1},
    ]);

    const menu = props.menu
    const columns = [
        {
            field: "name",
            header: "Name",
            bodyStyle: "text-align: left;",
            style: "width: 15%;",
        },
        {
            field: "description",
            header: "Description",
            bodyStyle: "text-align: left;",
            style: "width: 50%;",
        },
        {
            field: "is_active",
            header: "Status",
            bodyStyle: "text-align: center;",
            style: "width: 15%;",
        },
        {
            field: "actions",
            header: "Action",
            bodyStyle: "text-align: center;",
            style: "width: 20%;",
        },
    ];

    const getSeverity = (value) => {
        switch (value) {
            case 0:
                return 'danger';
            case 1:
                return 'success';
            default:
                return '';
        }
    };

    const getLabel = (value) => {
        const status = statuses.find(s => s.value === value);
        return status ? status.label : '';
    };


    const initFilters = () => {
        filters.value = {
            name: {value: null, matchMode: FilterMatchMode.CONTAINS},
            description: {value: null, matchMode: FilterMatchMode.CONTAINS},
            is_active: {value: null, matchMode: FilterMatchMode.EQUALS},
        };
    };


    const loadData = async ({page, rows, sortField, sortOrder, filters}) => {
        try {
            loading.value = true;
            const response = await axios.get('role/get-datatable', {
                params: {
                    page: page + 1,
                    rows: rows,
                    sortField: sortField,
                    sortOrder: sortOrder === 1 ? 'asc' : 'desc',
                    filters: filters,
                },
            });

            roles.value = response.data.roles;
            totalRecords.value = response.data.total;
            loading.value = false;
        } catch (error) {
            loading.value = false;
            console.error('Error fetching data:', error);
        } finally {
            loading.value = false;
        }
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
            filters.value[field].value = value; // Update nilai value
        } else {
            console.error(`Filter "${field}" tidak ditemukan.`);
        }
        loadData({page: page, rows: rows, sortField: null, sortOrder: null, filters: filters.value});
    }

    function onFilterClear(field) {
        if (filters.value[field]) {
            filters.value[field].value = null; // Update nilai value
        } else {
            console.error(`Filter "${field}" tidak ditemukan.`);
        }
        loadData({page: page.value, rows: rows.value, sortField: null, sortOrder: null, filters: filters.value});
    }

    const handleDelete = async (id) => {
        confirm.require({
            message: 'Do you want to delete this record?',
            header: 'Danger Zone',
            icon: 'pi pi-info-circle',
            rejectLabel: 'Cancel',
            acceptLabel: 'Delete',
            rejectClass: 'p-button-secondary p-button-outlined',
            acceptClass: 'p-button-danger',
            accept: async () => {
                try {
                    const response = await axios.delete(`role/delete/${id}`);
                    // Ambil nama pengguna dari respons
                    const roleName = response?.data?.role?.name || 'N/A';

                    // Buat pesan dinamis
                    const message = `Delete Role ${roleName} successfully`;
                    toast.add({severity: 'success', summary: 'Success', detail: message, life: 3000});

                    await loadData({
                        page: page.value,
                        rows: rows.value,
                        sortField: null,
                        sortOrder: null,
                        filters: filters.value
                    });
                } catch (error) {
                    // Tangani error dan tampilkan pesan error (jika ada)
                    const errorMessage = error?.response?.data?.message || 'An error occurred';
                    toast.add({severity: 'error', summary: 'Error', detail: `${errorMessage}`, life: 3000});
                }
            },
            reject: () => {
                console.log("you reject");
            }
        });
    };

    return {
        columns,
        roles,
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
        handleDelete,
        statuses,
        getSeverity,
        getLabel,
        menu
    };
}
