<template>
    <section class="body_content">
        <div class="container">
            <student-form 
                :classroom="classroom" 
                :student="students" 
                :editId="editId" 
                ></student-form>

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
import StudentForm from "./StudentForm.vue"
import StudentList from './StudentList.vue'
export default{
    name:"TeacherStudent",
    components:{
        StudentList,
        StudentForm,
    },
    data(){
        return{
            res_status:'',
            students:'',
            isNoStudent:false,
            perpage:10,
            classroom:'',
            editId:0,
        }
    },
    mounted(){
        this.getStudent()
    },
    methods:{
        getStudent(page){
            let url = ''
            this.editId = 0
            if(page){
                url = `${page}&perpage=${this.perpage}`
                this.$cookies.set('oldStudent_t_page',url)
            }
            url = this.$cookies.get('oldStudent_t_page')
            if(!url) url = `/api/teacher/student?perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{
                    let sData = res.data.user
                    let c_room = res.data.classroom
                    this.classroom = c_room
                    this.students = sData
                })
        },
    },
}
</script>
