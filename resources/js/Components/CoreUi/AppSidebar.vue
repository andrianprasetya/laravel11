<script setup>
import {Link} from '@inertiajs/vue3';

import {logo} from '@/Assets/brand/logo'
import {sygnet} from '@/Assets/brand/sygnet'
import {AppSidebarNav} from '@/AppSidebarNav.js'
import {useSidebarStore} from '@/sidebar.js'

import {CSidebar, CSidebarHeader, CSidebarBrand, CCloseButton, CSidebarFooter, CSidebarToggler} from '@coreui/vue'
import {CIcon} from "@coreui/icons-vue";

const sidebar = useSidebarStore()
</script>

<template>
    <CSidebar
        class="border-end"
        colorScheme="dark"
        position="fixed"
        :unfoldable="sidebar.unfoldable"
        :visible="sidebar.visible"
        @visible-change="(value) => sidebar.toggleVisible(value)">
        <CSidebarHeader class="border-bottom">
            <Link class="d-flex align-items-center">
                <CSidebarBrand v-bind="$attrs" as="a" >
                    <CIcon custom-class-name="sidebar-brand-full" :icon="logo" :height="32"/>
                    <CIcon custom-class-name="sidebar-brand-narrow" :icon="sygnet" :height="32"/>
                </CSidebarBrand>
            </Link>
            <CCloseButton class="d-lg-none" dark @click="sidebar.toggleVisible()"/>
        </CSidebarHeader>
        <AppSidebarNav/>
        <CSidebarFooter class="border-top d-none d-lg-flex">
            <CSidebarToggler @click="sidebar.toggleUnfoldable()"/>
        </CSidebarFooter>
    </CSidebar>
</template>
