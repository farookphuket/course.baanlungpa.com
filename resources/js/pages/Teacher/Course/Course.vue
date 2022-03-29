<template>
    <section class="body_content">
        <div class="container">

            <course-form :editId="editId"  
                :classrooms="classrooms" 
                :isNoClassRoom="isNoClassRoom" 
                @showBox="showBox($event)"  
                @getCourse="getCourse($event)" ></course-form>

            <course-list 
                :isNoCourse="isNoCourse" 
                :courses="courses" 
                @getCourse="getCourse($event)" 
                @edit="edit($event)" 
                @del="del($event)" ></course-list>
        </div>
        <x-box :msg="res_status" ref="xbox"></x-box>
    </section>
</template>
<script>

import CourseForm from "./CourseForm.vue"
import CourseList from "./CourseList.vue"
import xBox from "../../_include/BoxModal.vue"
export default{
    name:"TeacherCourse",
    components:{
        CourseForm,
        CourseList,
        xBox,
    },
    data(){
        return{
            editId:0,
            perpage:10,
            classrooms:'',
            courses:'',
            isNoClassRoom:false,
            isNoCourse:false,
            res_status:'',
        }
    },
    mounted(){
        this.getCourse()
    },
    methods:{
        getCourse(page){
            this.editId = 0
            this.isNoCourse = false

            let url = ''
            if(page){
                url = `${page}&perpage=${this.perpage}`
                this.$cookies.set('t_oldcourse_page',url)
            }
            url = this.$cookies.get('t_oldcourse_page')
            if(!url) url = `/api/teacher/course?perpage=${this.perpage}`
            axios.get(url)
                .then(res=>{
                    let coData = res.data.course
                    let clData = res.data.classroom


                    if(coData.data.length === 0 || 
                    Object.values(coData.data).length === 0){
                        //console.log(coData.data.length)
                        this.isNoCourse = true
                    }else{
                        //console.log(coData.data.length)
                        this.isNoCourse = false
                    }


                    if(Object.values(clData).length === 0){
                        this.isNoClassRoom = true
                    }
                
                    this.classrooms = clData
                    this.courses = coData

                })
        },
        showBox(v){
            let timeout = v.timeout 
            let msg = v.msg 
            this.res_status = msg

            this.$refs.xbox.showBoxModal()
            if(timeout && timeout !== 0){
                setTimeout(()=>{
                    this.$refs.xbox.hideBoxModal()
                },timeout)
            }

            this.getCourse()
        },
        edit(id){
            this.editId = id
        },
        del(id){
            if(id && id !== 0 && 
                confirm(`delete course id ${id}?`) !== false){
                let url = `/api/teacher/course/${id}`
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        this.showBox({msg:this.res_status,timeout:3200})
                    })
            }
        },
    },
}
</script>
