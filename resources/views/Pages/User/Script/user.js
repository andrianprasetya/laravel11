import {FilterMatchMode} from 'primevue/api';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from 'primevue/usetoast';
import {ref, onMounted} from "vue";
import {usePage} from '@inertiajs/vue3';

export default function userScript() {
    const filters = ref();
    const users = ref([]);
    const page = ref(0);
    const loading = ref(true);
    const totalRecords = ref(0);
    const rowOptions = [5, 10, 20, 50];
    const rows = ref(rowOptions[1]);
    const {props} = usePage();
    const confirm = useConfirm();
    const toast = useToast();

    const menu = props.menu + " List";
    const columns = [
        {
            field: "name",
            header: "Name",
            bodyStyle: "text-align: left;",
            style: "width: 25%;",
        },
        {
            field: "email",
            header: "Email",
            bodyStyle: "text-align: left;",
            style: "width: 25%;",
        },
        {
            field: "email_verified_at",
            header: "Verify",
            bodyStyle: "text-align: center;",
            style: "width: 10%;",
        },
        {
            field: "created_at",
            header: "Register Date",
            bodyStyle: "text-align: center;",
            style: "width: 20%;",
        },
        {
            field: "actions",
            header: "Action",
            bodyStyle: "text-align: center;",
            style: "width: 20%;",
        },
    ];

    const loadData = async ({page, rows, sortField, sortOrder, filters}) => {
        try {
            loading.value = true;
            const response = await axios.get('user/get-datatable', {
                params: {
                    page: page + 1,
                    rows: rows,
                    sortField: sortField,
                    sortOrder: sortOrder === 1 ? 'asc' : 'desc',
                    filters: filters,
                },
            });

            users.value = response.data.users.map(user => ({
                ...user,
                created_at: new Date(user.created_at),
            }));

            console.log(users.value)
            totalRecords.value = response.data.total;
            loading.value = false;
        } catch (error) {
            loading.value = false;
            console.error('Error fetching data:', error);
        } finally {
            loading.value = false;
        }
    };

    const formatDate = (value) => {
        return value.toLocaleDateString('id', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    };

    const initFilters = () => {
        filters.value = {
            name: {value: null, matchMode: FilterMatchMode.CONTAINS},  // Global filter
            email: {value: null, matchMode: FilterMatchMode.CONTAINS},
            email_verified_at: {value: null, matchMode: FilterMatchMode.EQUALS},
            created_at: {
                value: null,
                matchMode: FilterMatchMode.BETWEEN
            },
        };
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
            if (field === "created_at") {
                let dateValue = new Date(value[1]);
                dateValue.setHours(23, 59, 59, 59); // Set to 12:00:00 for the new time
                value[1] = dateValue;
            }
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
                    const response = await axios.delete(`user/delete/${id}`);
                    // Ambil nama pengguna dari respons
                    const userName = response?.data?.user?.name || 'N/A';

                    // Buat pesan dinamis
                    const message = `Delete User ${userName} successfully`;
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
        users,
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
        handleDelete,
        menu
    };
}
