<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
});

const sortClass = computed(() => {
    const urlParams = new URLSearchParams(window.location.search);
    let sortBy = urlParams.get("sort_by") || "";
    let sortDir = sortBy.charAt(0);
    let sortClass = "";

    // remove '-'
    return sortBy.replace(/^\-+/, "") === props.name
        ? sortDir === "-"
            ? "desc"
            : "asc"
        : "";
});

const navigate = () => {
    const urlParams = new URLSearchParams(window.location.search);
    let sortBy = urlParams.get("sort_by") || "";
    let sortDir = sortBy.charAt(0);

    sortBy = !sortBy || sortDir === "-" ? props.name : `-${props.name}`;

    urlParams.set("sort_by", sortBy);
    const params = Object.fromEntries(urlParams.entries());

    router.get(route(route().current()), params);
};
</script>

<template>
    <a href="#" @click.prevent="navigate" class="sortable" :class="sortClass">{{
        props.label
    }}</a>
</template>
