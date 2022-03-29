<template>
    <section class="body_content">
        <div class="container" v-if="isShowClassroom === true">
            <ClassroomView 
            :classroom_id="classroom_id" 
            @getClassRoom="getClassRoom($event)"></ClassroomView>
        </div>
        <div class="container" v-else>
            <class-form 
                :editId="editId"  
                :student="student"
                @showBox="showBox($event)" 
                @getClassRoom="getClassRoom($event)" 
                ></class-form>

            <class-list 
                :isNoRoom="isNoRoom" 
                :classrooms="classrooms" 
                @openClass="openClass($event)"></class-list>
        </div>
        <x-box :msg="res_status" 
            ref="xbox"></x-box>
    </section>
</template>
<script>
import xBox from "../../_include/BoxModal.vue"
import ClassForm from './Form.vue'
import ClassList from './List.vue'
import ClassroomView from './ClassRoomView.vue'
export default{
    name:"ClassRoom",
    components:{
        ClassForm,
        xBox,
        ClassList,
        ClassroomView,
    },
    data(){
        return{
            editId:0,
            res_status:'',
            perpage:10,
            classrooms:'',
            isNoRoom:false,
            isShowClassroom:false,
            classroom_id:0,
            student:'',
        }
    },
    mounted(){
        this.getClassRoom()
    },
    methods:{

        getClassRoom(page){
            this.isNoRoom = false
            this.isShowClassroom = false
            let url = ''
            if(page){
                url = `${page}&perpage=${this.perpage}`
                this.$cookies.set('t_classroom_old_page',url)
            }
            url = this.$cookies.get("t_classroom_old_page")
            if(!url) url = `/api/teacher/classroom?perpage=${this.perpage}`

            axios.get(url)
                .then(res=>{
                    let rData = res.data.classroom
                    let sData = res.data.student
                    this.student = sData
                    this.classrooms = rData
                    if(Object.values(rData.data).length === 0){
                        this.isNoRoom = true
                    }
                })
        },
        showBox(val){
            let msg = val.msg 
            let timeout = val.timeout
            this.res_status = msg
            this.$refs.xbox.showBoxModal()
            if(timeout && timeout !== 0){
                setTimeout(()=>{
                    this.$refs.xbox.hideBoxModal()
                },timeout)
            }
            this.getClassRoom()
        },
        openClass(id){
            this.classroom_id = id
            this.isShowClassroom = true
        },
    },
}
</script>
