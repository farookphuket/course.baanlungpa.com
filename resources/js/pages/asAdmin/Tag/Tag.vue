<template>
    <section class="body_content">
        <div class="container">
            <div class="field">
                <button class="button" @click.prevent="openBox('love live ')">xBox</button>
            </div>
            <tag-form :editId="editId" 
                @getTag="getTag($event)"></tag-form>

            <tag-list :tag="tag" 
                @getTag="getTag($event)" 
                @edit="edit($event)" 
                @del="del($event)"></tag-list>
        </div>
       <box-modal ref="xBox" :msg="res_status"></box-modal>
    </section>
</template>
<script>
import BoxModal from "../../_include/BoxModal.vue"
import TagForm from "./TagForm.vue"
import TagList from "./TagList.vue"
export default{
    name:"AdminTag",
    components:{
        TagList,
        TagForm,
        BoxModal,
    },
    data(){return{
        tag:'',
        editId:0,
        res_status:''
    }},
    mounted(){
        this.getTag()
    },
    methods:{
        getTag(page){
            this.editId = 0

            let url = ""
            if(page){
                url = page
                this.$cookies.set("aold_tag_page",url)
            }
            url = this.$cookies.get("aold_tag_page")
            if(!url) url = `/api/admin/tag?perpage=10`

            axios.get(url)
                .then(res=>{
                    let rData = res.data.tag
                    if(rData.data.length === 0){
                        this.tag = 0
                    }else{
                        this.tag = rData
                    }
                })
        },
        edit(id){
            this.editId = id

        },
        del(id){
            if(id !== 0 && 
                confirm(`this will delete tag id ${id}?`) === true){
                let url = `/api/admin/tag/${id}`
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        this.openBox(this.res_status,1200)
                        setTimeout(()=>{
                            this.getTag()
                        },2500)
                    })
            }
        },
        openBox(msg,myTime = false){
            if(!myTime) myTime = 3200
            this.res_status = msg
            this.$refs.xBox.showBoxModal()
            setTimeout(()=>{
                this.res_status = ''
                this.$refs.xBox.hideBoxModal()
            },myTime)
        },
    },
}
</script>
