<script setup>
import {ref, computed, onMounted, onBeforeUnmount} from 'vue';
import {useLayout} from '../../../js/composables/layout.js';
import {usePage, router} from '@inertiajs/vue3';

const {layoutConfig} = useLayout();
const {props} = usePage();

const outsideClickListener = ref(null);
const profileMenuActive = ref(false);

onMounted(() => {
    bindOutsideClickListener();
});

onBeforeUnmount(() => {
    unbindOutsideClickListener();
});

const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
});


const topbarMenuClasses = computed(() => {
    return {
        'layout-topbar-menu-mobile-active': false
    };
});

const profileMenuClasses = computed(() => {
    return {
        'profile-menu-active': profileMenuActive.value
    };
});

const bindOutsideClickListener = () => {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                profileMenuActive.value = false;
            }
        };
        document.addEventListener('click', outsideClickListener.value);
    }
};
const unbindOutsideClickListener = () => {
    if (outsideClickListener.value) {
        document.removeEventListener('click', outsideClickListener);
        outsideClickListener.value = null;
    }
};
const isOutsideClicked = (event) => {
    if (!profileMenuActive.value) return;

    const topbarEl = document.querySelector('.layout-topbar-menu-button');
    const profileEl = document.querySelector('.profile-menu');

    return !(topbarEl.isSameNode(event.target) || topbarEl.contains(event.target) || profileEl.isSameNode(event.target) || profileEl.contains(event.target));
};

const items = ref([
    {
        items: [
            {
                label: 'Settings',
                icon: 'pi pi-cog',
                link: '/profile'
            },
            {
                label: 'Logout',
                icon: 'pi pi-sign-out'
            }
        ]
    },
    {
        separator: true
    }
]);

const handleLogout = () => {
    router.post('/logout');
}
</script>

<template>
    <div class="layout-topbar">
        <a href="#" class="layout-topbar-logo">
            <img :src="logoUrl" alt="logo"/>
            <span>SAKAI</span>
        </a>

        <div class="layout-topbar-menu" :class="topbarMenuClasses">
            <button class="p-link layout-topbar-button" ref="profileButton" @click="$refs.overlayPanel.toggle($event)">
                <i class="pi pi-user"></i>
                <span>Profile</span>
            </button>
        </div>
        <!-- Profile Menu -->
        <OverlayPanel ref="overlayPanel" class="w-64 shadow-lg">
            <!-- Profile Details -->
            <Menu :model="items" class="w-full md:w-60">
                <template #start>
                        <span class="inline-flex items-center gap-1 px-2 py-2">
                            <span class="text-xl font-semibold">LARAVEL<span class="text-primary">11</span></span>
                        </span>
                </template>
                <template #item="{ item, props }">
                    <a v-if="item.label === 'Logout'" class="flex items-center mb-2" v-bind="props.action"
                       @click.prevent="handleLogout">
                        <span :class="item.icon"/>&nbsp;
                        <span>{{ item.label }}</span>
                    </a>
                    <a v-else v-ripple :href="item.link" class="flex items-center" v-bind="props.action">
                        <span :class="item.icon"/>&nbsp;
                        <span>{{ item.label }}</span>
                    </a>
                </template>
                <template #end>
                    <div class="inline-flex">
                        <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png"
                                class="mr-2 my-2"
                                shape="circle"/>
                        <span class="flex-col items-start">
                        <span class="font-bold full-width-span mb-2 font-span-username">{{
                                $page.props.auth.user.name
                            }}</span>
                        <span class="text-sm full-width-span">Admin</span>
                    </span>
                    </div>
                </template>
            </Menu>
        </OverlayPanel>
        <!--        <div class="layout-topbar-menu" :class="topbarMenuClasses">
                    <button class="p-link layout-topbar-button" @click="onProfileMenuButton()">
                        <i class="pi pi-user"></i>
                        <span>Profile</span>
                    </button>


                    <div class="profile-menu" :class="profileMenuClasses">
                        <Menu :model="items" class="w-full md:w-60">
                            <template #start>
                                <span class="inline-flex items-center gap-1 px-2 py-2">
                                    <span class="text-xl font-semibold">PRIME<span class="text-primary">APP</span></span>
                                </span>
                            </template>
                            <template #item="{ item, props }">
                                <a v-ripple class="flex items-center" v-bind="props.action">
                                    <span :class="item.icon"/>
                                    <span>{{ item.label }}</span>
                                    <Badge v-if="item.badge" class="ml-auto" :value="item.badge"/>
                                </a>
                            </template>
                            <template #end>
                                <span
                                    class="relative overflow-hidden w-full border-0 bg-transparent flex items-start p-2 pl-4 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-none cursor-pointer transition-colors duration-200">
                                    <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png" class="mr-2"
                                            shape="circle"/>
                                    <span class="inline-flex flex-col items-start">
                                <span class="font-bold">{{ $page.props.auth.user.name }}</span>
                                <span class="text-sm">Admin</span>
                            </span>
                                </span>
                            </template>
                        </Menu>
                        &lt;!&ndash;                <div class="profile-details">
                                            <span>{{ $page.props.auth.user.name }}</span>
                                            <Link href="/logout" method="post" as="button" class="logout-button">Logout</Link>
                                        </div>&ndash;&gt;
                    </div>
                </div>-->
    </div>
</template>

<style lang="scss" scoped>
.layout-topbar-logo {
    display: flex; /* Flexbox for alignment */
    align-items: center; /* Vertically center items */
    justify-content: center; /* Horizontally center items */
    text-decoration: none; /* Remove underline from link */
}

.layout-topbar-logo img {
    margin-right: 0.5rem; /* Add spacing between the image and text */
    height: 40px; /* Adjust as per your desired logo size */
}

.layout-topbar-logo span {
    font-size: 1.2rem; /* Adjust text size */
    font-weight: bold; /* Make text bold */
    color: #333; /* Adjust text color */
}

.profile-menu {
    display: none;
    position: absolute;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    right: 0;
    top: 50px;
    z-index: 1000;

    &.profile-menu-active {
        display: block;
    }

    .profile-details {
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;

        span {
            margin-bottom: 10px;
        }

        .logout-button {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;

            &:hover {
                text-decoration: underline;
            }
        }
    }
}

.full-width-span {
    display: block;
    width: 100%;
}

.font-span-username {
    font-size: 18px;
    text-transform: capitalize;
}
</style>
