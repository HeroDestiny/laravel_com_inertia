<script setup lang="ts">
import {
    Breadcrumb,
    BreadcrumbItem as UiBreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItemType } from '@/types';

defineProps<{
    breadcrumbs: BreadcrumbItemType[];
}>();
</script>

<template>
    <Breadcrumb>
        <BreadcrumbList>
            <template v-for="(item, index) in breadcrumbs" :key="index">
                <UiBreadcrumbItem>
                    <template v-if="index === breadcrumbs.length - 1">
                        <BreadcrumbPage>{{ item.title }}</BreadcrumbPage>
                    </template>
                    <template v-else>
                        <BreadcrumbLink as-child>
                            <Link :href="item.href ?? '#'">{{ item.title }}</Link>
                        </BreadcrumbLink>
                    </template>
                </UiBreadcrumbItem>
                <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" />
            </template>
        </BreadcrumbList>
    </Breadcrumb>
</template>
