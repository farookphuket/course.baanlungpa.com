<template>
    <section class="body_content">
        <div class="container">
            <course-list 
                :course="course" 
                :num_course="num_course" 
                ></course-list>
        </div>
    </section>

</template>
<script>
import CourseList from "./CourseList.vue"
export default{
    name:"Course",
    components:{
        CourseList,
    },
    data(){
        return{
            course:'',
            num_course:0,
            perpage:15,
        }
    },
    mounted(){
        this.getCourse()
    },
    methods:{
        getCourse(page){
            let url = ''
            if(page){
                url = `${page}&perpage=${this.perpage}`
                this.$cookies.set('course_old_page',url)
            }
            url = this.$cookies.get("course_old_page")
            if(!url) url = `/api/free-course?perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{
                    let coData = res.data.course

                    if(coData.data.length > 0){
                        this.num_course = coData.data.length
                    }

                    document.title = "Free online course "

                    this.course = coData
                })
        },
    },
}
</script>
