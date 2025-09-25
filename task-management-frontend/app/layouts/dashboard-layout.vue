<template>
    <div>
        <BaseNavbar/>
        <div class="p-2">
            <div class="flex">
                <BaseSidebar/>
                <div class="w-full h-[calc(100vh-100px)] px-3">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
