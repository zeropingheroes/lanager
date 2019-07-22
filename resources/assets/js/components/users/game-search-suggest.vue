<template>
    <div>
        <vue-simple-suggest
                v-model="chosen"
                :list="games"
                :destyled=true
                :styles="bootstrap"
                :placeholder="'Search'"
                :debounce="250"
                :filter-by-query="false"
                :display-attribute="'name'"
                :min-length="3"
                @select="post">
            <div slot="suggestion-item" slot-scope="{ suggestion }">
               {{ suggestion.name }}
            </div>
        </vue-simple-suggest>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                chosen: '',
                bootstrap : {
                    vueSimpleSuggest: "position-relative",
                    inputWrapper: "",
                    defaultInput : "form-control",
                    suggestions: "position-absolute list-group",
                    suggestItem: "list-group-item"
                }
            }
        },
        methods: {
            games(q) {
                return axios.get('games?name='+ q + '&limit=5')
                    .then((response) => {
                        return response.data.data;
                    }, (error) => {
                        console.log('Error getting events')
                    })
            },
            post(game) {
                axios.post('users/' + userId + '/favourite-games', game)
                    .then((response) => {
                        this.$emit('favourite-added')
                    }, (error) => {
                        console.log('Error adding favourite game')
                        console.log(error.response.status)
                        console.log(error.response.data)
                    })
            },
        }
    }
</script>

<style lang="scss">
    .hover {
        font-weight: bold;
    }
</style>
