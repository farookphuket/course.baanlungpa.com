<template>
    <div>
        <div class="box" 
            v-if="isNoStudent === true">
            <p class="title has-text-danger">
                There is no Student yet!
            </p>
        </div>
        <div v-else>
            <div class="columns is-mobile is-multiline">
                <!-- show student Object START -->
                <div class="column is-4" 
                    v-for="st in students">
                    <p class="title">{{st.name}}</p>
                </div>
                <!-- show student Object End -->

            </div>
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
        isSetStudent:[],
    }},
    methods:{
        setStudentToClass(id){
            this.isSetStudent.push(id)
        },
    },
}
</script>
