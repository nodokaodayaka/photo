<template>
    <div class="search-form">
        <form class="form" @submit.prevent="submit">
        <input class="search-form__input" type="text" v-model="tag_text" placeholder="検索したいタグを入力">
        <button class="search-form__button" type="submit">検索</button>
        </form>
    </div>

</template>

<script>
    export default {
        name: "SearchForm",
        data: () => {
            return {
                tag_text: '',
            }
        },
        methods: {
            async submit () {
                console.log('submit')
                // this.$router.push(`/?page=2`)
                this.$router.push({ path: '/', query: {tag : this.tag_text }});

            },
        }
    }
</script>

<style scoped>

</style>