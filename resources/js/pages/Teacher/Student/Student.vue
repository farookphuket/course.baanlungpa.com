<template>
    <section class="body_content">
        <div class="container">
            <student-list 
                :isNoStudent="isNoStudent" 
               :students="students" 
               @edit="edit($event)" 
               @del="del($event)" 
               @getStudent="getStudent($event)"> 
            </student-list>
        </div>

    </section>
</template>
<script>
import StudentList from './StudentList.vue'
export default{
    name:"TeacherStudent",
    components:{
        StudentList,
    },
    data(){
        return{
            res_status:'',
            students:'',
            isNoStudent:false,
            perpage:10,
        }
    },
    mounted(){
        this.getStudent()
    },
    methods:{
        getStudent(page){
            let url = ''
            if(page){
                url = `${page}&perpage=${this.perpage}`
                this.$cookies.set('oldStudent_t_page',url)
            }
            url = this.$cookies.get('oldStudent_t_page')
            if(!url) url = `/api/teacher/student?perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{
                    console.log(res.data)
                })
        },
    },
}
</script>
