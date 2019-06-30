<template>
    <div>
        <SearchForm/>
        <div class="photo-list">
            <div class="grid">
                <Photo
                        class="grid__item"
                        v-for="photo in photos"
                        :key="photo.key_id"
                        :item="photo"
                        @like="onLikeClick"
                />
            </div>
            <Pagination :current-page="currentPage" :last-page="lastPage" :tagQuery="tagQuery" />
        </div>
    </div>
</template>


<script>
    import { OK } from '../util'
    import Photo from '../components/Photo.vue'
    import Pagination from '../components/Pagination.vue' // ★ 追加
    import SearchForm from '../components/SearchForm.vue'

    export default {
        components: {
            Photo,
            Pagination, // ★ 追加
            SearchForm
        },
        props: {
            page: {
                type: Number,
                required: false,
                default: 1
            },
            tag: {
                type: String,
                required: false,
                default: ''
            }
        },
        data () {
            return {
                photos: [],
                currentPage: 0,
                lastPage: 0,
                tagQuery: '',
            }
        },
        methods: {
            async fetchPhotos () {
                this.tagQuery = this.tag ? '&tag=' + this.tag : ''
                const response = await axios.get(`/api/photos/?page=${this.page}${this.tagQuery}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.photos = response.data.data
                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
                console.log('fetchphots')
                this.$forceUpdate();
                // this.$set(this, 'photos', response.data.data)

                this.photos.forEach(data => {
                        let d = new Date()
                        console.log(d.getTime())
                        data.key_id = data.id + d.getTime()
                    }
                )
                this.photos = Object.assign({}, this.photos, response.data.data )

            },
            onLikeClick ({ id, liked }) {
                if (! this.$store.getters['auth/check']) {
                    alert('いいね機能を使うにはログインしてください。')
                    return false
                }

                if (liked) {
                    this.unlike(id)
                } else {
                    this.like(id)
                }
            },
            async like (id) {
                const response = await axios.put(`/api/photos/${id}/like`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.photos = this.photos.map(photo => {
                    if (photo.id === response.data.photo_id) {
                        photo.likes_count += 1
                        photo.liked_by_user = true
                    }
                    return photo
                })
            },
            async unlike (id) {
                const response = await axios.delete(`/api/photos/${id}/like`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.photos = this.photos.map(photo => {
                    if (photo.id === response.data.photo_id) {
                        photo.likes_count -= 1
                        photo.liked_by_user = false
                    }
                    return photo
                })
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchPhotos()
                },
                immediate: true
            }
        }
    }
</script>