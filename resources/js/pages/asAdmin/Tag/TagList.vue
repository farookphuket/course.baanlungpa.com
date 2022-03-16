<template>
    <div>
        <div class="box mt-2 mb-2" v-if="tag === 0">
            <p class="title has-text-danger">
                There is no Tag
            </p>
        </div>
        <article class="box mt-2 mb-2 pb-5" 
            v-for="ta in tag.data"
            v-else>
            <p class="title">{{ta.tag_name}}</p>
            
            <div class="mt-2">

            </div>

            <div class="pb-4 mt-4 is-mobile">
                <div class="columns is-mobile">
                    <div class="column">
                        <div class="field is-pulled-left">
                            <span class="mr-2">
                                <font-awesome-icon icon="calendar-week"></font-awesome-icon>
                            </span>
                            <span>{{moment(ta.created).format("YYYY-MM-DD")}}</span>
                            <span class="ml-2">{{moment(ta.created).fromNow()}}</span>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field is-pulled-right">
                            <button class="button is-rounded is-link is-small" 
                                @click.prevent="$emit('edit',ta.id)">
                                <font-awesome-icon icon="edit"></font-awesome-icon>
                            </button>
                            <button class="button ml-2 is-rounded is-danger is-small" 
                                @click.prevent="$emit('del',ta.id)">
                                <font-awesome-icon icon="trash"></font-awesome-icon>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </article>
    </div>
</template>
<script>
var moment = require("moment")
export default{
    name:"TagList",
    props:["tag"],
    data(){return{
        moment:moment,
    }}
}
</script>
