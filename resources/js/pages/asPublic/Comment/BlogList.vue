<template>
    <div>
        <div class="box" v-if="isNoBlog === true">
            <p class="title has-text-centered">
                There is no data.
            </p>
        </div>
        <article class="box" 
            v-for="bl in blogs.data"
            v-else>
            <p class="title">
                <span>
                    <a href="" 
                        @click.prevent="$emit('openBlog',bl.bl_slug)">
                        {{bl.bl_title}}
                    </a>
                </span>
                
                <span class="tag is-small has-text-success">
                    <font-awesome-icon
                        icon="eye"></font-awesome-icon>
                </span>
            </p>

            <!-- show user,read,create START -->
            <div class="mt-4 mb-4">
                <div class="columns is-mobile">
                    <div class="column"><!-- create,read time START -->
                        <div class="mb-4 pb-4">
                            <div class="field is-pulled-left">
                                <span class="mr-2">
                                    <font-awesome-icon 
                                        icon="calendar-week"></font-awesome-icon>
                                </span>
                                <span>
                                    {{moment(bl.created_at)
                                    .format("YYYY-MMM-DD - HH:mm:ss")}}
                                </span>
                                <span class="ml-2 pr-2">
                                    {{moment(bl.created_at).fromNow()}}
                                </span>
                            </div>
                            <div class="field is-pulled-right pr-2">
                                <span class="mr-2">
                                    <font-awesome-icon 
                                        icon="eye"></font-awesome-icon>
                                </span>
                                <span class="mr-2">
                                    {{Object.values(bl.read).length}} 
                                </span>
                                <span class="ml-3 mr-2">
                                    <font-awesome-icon 
                                        icon="user"></font-awesome-icon>
                                </span>
                                <span>{{bl.user.name}}</span>
                            </div>
                        </div>

                    </div><!-- create,read time END -->

                </div>
            </div>
            <!-- show user,read,create END -->
        </article>
    </div>
</template>
<script>
var moment = require('moment')
export default{
    name:"BlogList",
    props:["blogs","isNoBlog"],
    data(){
        return{
            moment:moment,
        }
    },

}
</script>
