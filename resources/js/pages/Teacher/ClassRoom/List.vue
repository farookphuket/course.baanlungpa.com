<template>
    <div>
        <div class="box" 
            v-show="isNoRoom">
            <p class="title">No classroom yet!</p>
        </div>
        <article class="box" 
            v-for="c in classrooms.data" 
            >
            <p class="title">
                <a href="" 
                    @click.prevent="$emit('openClass',c.id)">
                    {{c.class_title}}
                </a>
            </p>
        </article>
    </div>
</template>
<script>
var moment = require('moment')
export default{
    name:"ClassList",
    props:["classrooms","isNoRoom"],
    data(){return{
        moment:moment,
    }},


}
</script>
