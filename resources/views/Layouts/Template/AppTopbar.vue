<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useLayout } from '../../../js/composables/layout.js';
import { usePage, Link } from '@inertiajs/vue3';

const { layoutConfig, onMenuToggle } = useLayout();
const { props } = usePage();

const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);
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

const onTopBarMenuButton = () => {
    topbarMenuActive.value = !topbarMenuActive.value;
};
const onProfileMenuButton = () => {
    profileMenuActive.value = !profileMenuActive.value;
};

const topbarMenuClasses = computed(() => {
    return {
        'layout-topbar-menu-mobile-active': topbarMenuActive.value
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
                topbarMenuActive.value = false;
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
    if (!topbarMenuActive.value && !profileMenuActive.value) return;

    const sidebarEl = document.querySelector('.layout-topbar-menu');
    const topbarEl = document.querySelector('.layout-topbar-menu-button');
    const profileEl = document.querySelector('.profile-menu');

    return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target) || profileEl.isSameNode(event.target) || profileEl.contains(event.target));
};
</script>

<template>
    <div class="layout-topbar">
        <a href="/" class="layout-topbar-logo">
            <img :src="logoUrl" alt="logo" />
            <span>SAKAI</span>
        </a>

        <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()">
            <i class="pi pi-bars"></i>
        </button>

        <button class="p-link layout-topbar-menu-button layout-topbar-button" @click="onTopBarMenuButton()">
            <i class="pi pi-ellipsis-v"></i>
        </button>

        <div class="layout-topbar-menu" :class="topbarMenuClasses">
            <button class="p-link layout-topbar-button" @click="onProfileMenuButton()">
                <i class="pi pi-user"></i>
                <span>Profile</span>
            </button>

            <div class="profile-menu" :class="profileMenuClasses">
                <div class="profile-details">
                    <span>{{ $page.props.auth.user.name }}</span>
                    <Link href="/logout" method="post" as="button" class="logout-button">Logout</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
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
</style>
