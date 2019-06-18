<template>
    <div class="pagination">
        <RouterLink
                v-if="! isFirstPage"
                :to="`/?page=${currentPage - 1}${tagQuery}`"
                class="button"
        >&laquo; prev</RouterLink>
        <RouterLink
                v-if="! isLastPage"
                :to="`/?page=${currentPage + 1}${tagQuery}`"
                class="button"
        >next &raquo;</RouterLink>
    </div>
</template>

<script>
    export default {
        props: {
            currentPage: {
                type: Number,
                required: true
            },
            lastPage: {
                type: Number,
                required: true
            },
            tagQuery: {
                type: String,
                required: true,
            }
        },
        computed: {
            isFirstPage () {
                return this.currentPage === 1
            },
            isLastPage () {
                return this.currentPage === this.lastPage
            }
        }
    }
</script>