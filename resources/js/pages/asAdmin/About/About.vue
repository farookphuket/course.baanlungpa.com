<template>
    <section class="body_content">
        <div class="container">
            <p class="title">admin about page</p>

            <div class="mt-2 mb-4 pb-4 pt-4">
                <about-form 
                    :editId="editId" 
                    @getAboutUs="getAboutUs($event)" 
                    @formReset="formReset($event)"
                    @openBox="openBox($event)"></about-form>
            </div>

            <static-page-list 
                :static_page="static_page" 
                :isNoStaticPage="isNoStaticPage"
                @edit="edit($event)" 
                @del="del($event)"
                ></static-page-list>

        </div>

        <x-box :msg="res_status" ref="xBox" 
            ></x-box>
    </section>
</template>
<script>

import AboutForm from "./AboutForm.vue"
import xBox from "../../_include/BoxModal.vue"
import StaticPageList from './StaticPageList.vue'
export default{
    name:"AdminAbout",
    components:{
        AboutForm,
        xBox,
        StaticPageList,
    },
    data(){
        return{
            editId:0,
            about:'',
            static_page:'',
            isNoStaticPage:0,
            res_status:'',
        }
    },
    mounted(){
        this.getAboutUs()
    },
    methods:{
        getAboutUs(){
            let url = `/api/static-site`
            axios.get(url)
                .then(res=>{
                    //console.log(res.data)
                    this.static_page = res.data.static_page
                    this.isNoStaticPage = Object.values(this.static_page.data).length
                })
        },
        edit(id){
            this.editId = id 

        },
        del(id){
            if(id && id !== 0 && 
                confirm(`delete about id ${id}?`) === true){
                let url = `/api/admin/default/${id}`
                //this.openBox({msg:'msg',myTime:2500})
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        this.openBox({
                            msg:this.res_status,
                            myTime:3200
                        })
                    })

            }
        },
        openBox({msg,myTime = 0}){
            this.res_status = msg
            this.$refs.xBox.showBoxModal()
            if(myTime && myTime !== 0){
                setTimeout(()=>{
                    this.$refs.xBox.hideBoxModal()
                    this.res_status = ''
                    this.getAboutUs()
                },myTime)
            }
        },
        formReset(){
            this.editId = 0 
            this.res_status = ''
            setTimeout(()=>{
                alert(`the page need to be refresh`)
                location.reload()
            },3200)
        },
    },

}
</script>
