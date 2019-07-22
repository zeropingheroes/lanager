<template>
    <div>
        <vue-simple-suggest
                v-model="chosen"
                :list="games"
                :destyled=true
                :styles="bootstrap"
                :placeholder="'Search for games'"
                :debounce="350"
                :filter-by-query="false"
                :display-attribute="'name'"
                :max-suggestions="3"
                :min-length="3"
                @select="post">
            <div slot="suggestion-item" slot-scope="{ suggestion }">
                <div class="game-image">
                    <img :src="suggestion.logo.small" onerror="this.style.display='none'">
                </div>
                <div class="game-name">
                    {{ suggestion.name }}
                </div>
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
                        this.chosen = ''
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
    @import "../../../sass/_variables.scss";
    ul.suggestions {
        width: 100%;
        z-index: 1000; /* fix buttons showing over dropdown */
    }
    li.suggest-item {
        padding-left: 12px; /* make images line up with table */
    }
    li.hover {
        background-color: $gray-600;
    }
    div.game-image {
        display: inline-block;
        float: left;
        width: 184px;
        height: 69px;
    }
    div.game-name {
        padding-left: 12px;
        display: inline-block;
        height: 69px;
        line-height: 69px;
        float: left;
        font-size: x-large;
    }
</style>
