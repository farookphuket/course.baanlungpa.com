<template>
    <section class="body_content">
        <div class="container">
            <class-form 
                :editId="editId" 
                @showBox="showBox($event)" 
                @getClassRoom="getClassRoom($event)" 
                ></class-form>

            <class-list 
                :isNoRoom="isNoRoom" 
                :classrooms="classrooms"></class-list>
        </div>
        <x-box :msg="res_status" 
            ref="xbox"></x-box>
    </section>
</template>
<script>
import xBox from "../../_include/BoxModal.vue"
import ClassForm from './Form.vue'
import ClassList from './List.vue'
export default{
    name:"ClassRoom",
    components:{
        ClassForm,
        xBox,
        ClassList,
    },
    data(){
        return{
            editId:0,
            res_status:'',
            perpage:10,
            classrooms:'',
            isNoRoom:false,
        }
    },
    mounted(){
        this.getClassRoom()
    },
    methods:{

        getClassRoom(page){
            this.isNoRoom = false

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
    },
}
</script>
