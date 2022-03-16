<template>
    <section class="body_content">
        <div class="container">


            <category-form :editId="editId" 
                @getCategory="getCategory($event)"></category-form>

            <category-list :category="category" 
                @getCategory="getCategory($event)" 
                @del="del($event)" 
                @edit="edit($event)"></category-list>
        </div>
        <x-box ref="xBox" :msg="res_status"></x-box>
    </section>
</template>
<script>
import CategoryForm from "./CategoryForm.vue"
import CategoryList from "./CategoryList.vue"
import xBox from "../../_include/BoxModal.vue"
export default{
    name:"AdminCategory",
    components:{
        CategoryForm,
        CategoryList,
        xBox,
    },
    data(){return{
        editId:0,
        category:'',
        res_status:'',
    }},
    mounted(){
        this.getCategory()
    },
    methods:{
        getCategory(page){
            this.editId = 0
            let url = ''
            if(page){
                url = page+"&perpage=10"
                this.$cookies.set("aca_old_page",url)
            }
            url = this.$cookies.get("aca_old_page")
            if(!url) url = `/api/admin/getcategory?perpage=10`
            axios.get(url)
                .then(res=>{
                    //console.log(res.data)
                    this.category = res.data.category
                })

        },
        edit(id){
            this.editId = id
        },
        del(id){
            if(id && id !== 0 && 
                confirm(`delete category id ${id},Okay?`) === true){
                let url = `/api/admin/category/${id}`
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        this.openBox(this.res_status,2200)
                        setTimeout(()=>{
                            this.getCategory()
                        },2000)
                    })
            }
            return
        },
        openBox(msg,myTime=false){
            !myTime?myTime = 2500:myTime;
            this.res_status = msg
            this.$refs.xBox.showBoxModal(msg,myTime)
            setTimeout(()=>{
                this.$refs.xBox.hideBoxModal()
            },myTime) 
        },
    },

}
</script>
