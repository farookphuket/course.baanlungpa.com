<template>
    <section class="body_content">
        <div class="container">
            <p class="title">เรียนกับครูมดแดง</p>


            <course-list></course-list>

        </div>
    </section>

</template>
<script>

import CourseList from './Course/CourseList.vue'

export default{
    name:"PubCourse",
    components:{
        CourseList,
    },
    data(){
        return{
            courses:'',
            
        }
    },
    mounted(){
        this.getWelcome()
    },
    methods:{
        getWelcome(){
            document.title = "เรียนกับครูมดแดง"
        },
    },
}
</script>
