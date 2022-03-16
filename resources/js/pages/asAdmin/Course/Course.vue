<template>
    <section class="body_content">
        <div class="container">
            <p class="title">Course</p>


            <course-form 
                :editId="editId" ></course-form>

            <course-list 
                :courses="courses" 
                :isNoCourse="isNoCourse"></course-list>
        </div>


        <x-box 
            ref="xbox" 
            :msg="res_status"></x-box>
    </section>
</template>
<script>
import CourseList from "./CourseList.vue"
import CourseForm from "./CourseForm.vue"
import xBox from "../../_include/BoxModal.vue"
export default{
    name:"AdminCourse",
    components:{
        CourseList,
        CourseForm,
        xBox,
    },
    data(){return{
        res_status:'',
        courses:'',
        editId:0,
        isNoCourse:0,
        perpage:10,
    }},
    mounted(){
        this.getCourse()
    },
    methods:{

        getCourse(page){
            let url = ''
            if(page){
                url = `${page}&perpage=${perpage}`
                this.$cookies.set('aold_course_page',url)
            }
            url = this.$cookies.get('aold_course_page')
            if(!url) url = `/api/admin/course`
            axios.get(url)
                .then(res=>{
                    this.course = res.data

                    console.log(this.course)
                })
        },
    },
}
</script>
