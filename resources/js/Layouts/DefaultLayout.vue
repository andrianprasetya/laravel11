<script setup>
import { CContainer } from '@coreui/vue'
import AppFooter from '@/Components/CoreUi/AppFooter.vue'
import AppHeader from '@/Components/CoreUi/AppHeader.vue'
import AppSidebar from '@/Components/CoreUi/AppSidebar.vue'

import { onBeforeMount } from 'vue'
import { useColorModes } from '@coreui/vue'

import { useThemeStore } from '@/theme.js'

const { isColorModeSet, setColorMode } = useColorModes(
    'coreui-free-vue-admin-template-theme',
)
const currentTheme = useThemeStore()

onBeforeMount(() => {
    const urlParams = new URLSearchParams(window.location.href.split('?')[1])
    let theme = urlParams.get('theme')

    if (theme !== null && theme.match(/^[A-Za-z0-9\s]+/)) {
        theme = theme.match(/^[A-Za-z0-9\s]+/)[0]
    }

    if (theme) {
        setColorMode(theme)
        return
    }

    if (isColorModeSet()) {
        return
    }

    setColorMode(currentTheme.theme)
})
</script>

<template>
  <div>
    <AppSidebar />
    <div class="wrapper d-flex flex-column min-vh-100">
      <AppHeader />
      <div class="body flex-grow-1">
        <CContainer class="px-4" lg>
            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </CContainer>
      </div>
      <AppFooter/>
    </div>
  </div>
</template>

<style lang="scss">
// Import Main styles for this application
@import '../Styles/style';
// We use those styles to show code examples, you should remove them in your application.
@import '../Styles/examples';
</style>
