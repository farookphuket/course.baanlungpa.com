<template>
    <div>
        <div class="box" 
            v-if="isNoStudent === true">
            <p class="title has-text-danger">
                There is no Student yet!
            </p>
        </div>
    </div>
</template>
<script>
var moment = require('moment')
export default{
    name:"StudentList",
    props:["students","isNoStudent"],
    data(){return{
        moment:moment,
    }}
}
</script>
